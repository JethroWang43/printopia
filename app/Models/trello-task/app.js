// Trello API Configuration
let TRELLO_API_KEY = '';
let TRELLO_API_URL = 'https://api.trello.com/1';
let DEFAULT_TOKEN = '';
let TRELLO_TOKEN = null;
let employeeAccessMode = false;
let currentEmployeeName = '';
const EMBED_VIEW_MODE = (new URLSearchParams(window.location.search).get('view') || 'all').toLowerCase();
const CHECKED_BY_STORAGE_KEY = 'checklistCheckedBy';
const PROGRESSION_ORDER_STORAGE_KEY = 'orderProgress';
const activeTaskFilters = {
    status: 'all',
    assignee: 'all',
    project: 'all',
    due: 'all'
};

let currentBoardLists = [];
let currentChecklistDragItem = null;

function emitHciInteraction(action, payload = {}) {
    const detail = {
        action,
        source: 'trello-task-filters',
        timestamp: new Date().toISOString(),
        payload
    };

    document.dispatchEvent(new CustomEvent('hci:interaction', { detail }));

    if (window.parent && window.parent !== window) {
        window.parent.postMessage({
            type: 'hci:interaction',
            detail
        }, '*');
    }
}

function slugifyFilterValue(value) {
    return String(value || '')
        .trim()
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-');
}

function extractProjectNameFromCard(card) {
    const match = String(card?.name || '').match(/#([a-z0-9][a-z0-9-]*)/i);
    if (match && match[1]) {
        return match[1].toLowerCase();
    }
    return slugifyFilterValue(card?.listName || 'general');
}

function matchesDueFilter(card, dueFilter) {
    if (dueFilter === 'all') return true;

    const now = new Date();
    const todayStart = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    const tomorrowStart = new Date(todayStart);
    tomorrowStart.setDate(tomorrowStart.getDate() + 1);
    const nextWeek = new Date(todayStart);
    nextWeek.setDate(nextWeek.getDate() + 7);

    if (!card?.due) {
        return dueFilter === 'noDue';
    }

    const dueDate = new Date(card.due);
    if (Number.isNaN(dueDate.getTime())) {
        return dueFilter === 'noDue';
    }

    if (dueFilter === 'overdue') {
        return dueDate < todayStart;
    }
    if (dueFilter === 'today') {
        return dueDate >= todayStart && dueDate < tomorrowStart;
    }
    if (dueFilter === 'next7') {
        return dueDate >= todayStart && dueDate < nextWeek;
    }

    return true;
}

function cardMatchesFilters(card) {
    const statusMatch = activeTaskFilters.status === 'all' || slugifyFilterValue(card.listName) === activeTaskFilters.status;

    const assigneeNames = Array.from(extractAssigneeNamesFromCard(card)).map(name => slugifyFilterValue(name));
    const assigneeMatch = activeTaskFilters.assignee === 'all' || assigneeNames.includes(activeTaskFilters.assignee);

    const projectMatch = activeTaskFilters.project === 'all' || extractProjectNameFromCard(card) === activeTaskFilters.project;
    const dueMatch = matchesDueFilter(card, activeTaskFilters.due);

    return statusMatch && assigneeMatch && projectMatch && dueMatch;
}

function getFilteredLists(lists) {
    return (lists || []).map(list => {
        const filteredCards = (list.cards || []).filter(card => cardMatchesFilters({ ...card, listName: list.name }));
        return {
            ...list,
            cards: filteredCards
        };
    });
}

function updateFilterButtonLabels() {
    const statusLabel = document.querySelector('#statusFilterLabel');
    const assigneeLabel = document.querySelector('#assigneeFilterLabel');
    const projectLabel = document.querySelector('#projectFilterLabel');
    const dueLabel = document.querySelector('#dueFilterLabel');

    if (statusLabel) {
        const active = document.querySelector('#statusFilterMenu .dropdown-item.active');
        statusLabel.textContent = active ? active.textContent.trim() : 'All statuses';
    }
    if (assigneeLabel) {
        const active = document.querySelector('#assigneeFilterMenu .dropdown-item.active');
        assigneeLabel.textContent = active ? active.textContent.trim() : 'All assignees';
    }
    if (projectLabel) {
        const active = document.querySelector('#projectFilterMenu .dropdown-item.active');
        projectLabel.textContent = active ? active.textContent.trim() : 'All projects';
    }
    if (dueLabel) {
        const active = document.querySelector('#dueFilterMenu .dropdown-item.active');
        dueLabel.textContent = active ? active.textContent.trim() : 'Due date';
    }
}

function mapListNameToDashboardStatus(listName) {
    const normalized = slugifyFilterValue(listName);

    if (['done', 'completed', 'complete'].includes(normalized)) {
        return 'completed';
    }
    if (['cancelled', 'canceled'].includes(normalized)) {
        return 'cancelled';
    }
    if (['backlog', 'todo', 'to-do', 'pending'].includes(normalized)) {
        return 'pending';
    }

    return 'processing';
}

function emitBoardSummary(lists) {
    const summary = {
        pending: 0,
        processing: 0,
        completed: 0,
        cancelled: 0
    };

    let total = 0;
    (lists || []).forEach((list) => {
        const cards = list.cards || [];
        if (!cards.length) {
            return;
        }

        const mappedStatus = mapListNameToDashboardStatus(list.name);
        summary[mappedStatus] += cards.length;
        total += cards.length;
    });

    const percentages = Object.fromEntries(
        Object.entries(summary).map(([status, count]) => {
            const pct = total > 0 ? Math.round((count / total) * 100) : 0;
            return [status, pct];
        })
    );

    if (window.parent && window.parent !== window) {
        window.parent.postMessage({
            type: 'trello:board-summary',
            payload: {
                boardId: currentBoardId,
                boardName: (allBoards.find(b => b.id === currentBoardId) || {}).name || '',
                total,
                counts: summary,
                percentages
            }
        }, '*');
    }
}

function refreshBoardViewsWithFilters() {
    if (!currentBoardLists.length) {
        return;
    }
    const filteredLists = getFilteredLists(currentBoardLists);
    loadTableView(filteredLists);
    loadKanbanView(filteredLists, currentBoardId);
    loadCalendarView(filteredLists);
    loadCalendarOverviewView(filteredLists);
    emitBoardSummary(filteredLists);
}

function initializeFilterMenus(lists) {
    const statusMenu = document.querySelector('#statusFilterMenu');
    const assigneeMenu = document.querySelector('#assigneeFilterMenu');
    const projectMenu = document.querySelector('#projectFilterMenu');

    if (!statusMenu || !assigneeMenu || !projectMenu) {
        return;
    }

    const statuses = new Set();
    const assignees = new Set();
    const projects = new Set();

    (lists || []).forEach(list => {
        statuses.add(slugifyFilterValue(list.name));
        (list.cards || []).forEach(card => {
            extractAssigneeNamesFromCard(card).forEach(name => assignees.add(slugifyFilterValue(name)));
            projects.add(extractProjectNameFromCard({ ...card, listName: list.name }));
        });
    });

    const statusItems = Array.from(statuses).filter(Boolean).sort();
    const assigneeItems = Array.from(assignees).filter(Boolean).sort();
    const projectItems = Array.from(projects).filter(Boolean).sort();

    statusMenu.innerHTML = '<li><button class="dropdown-item active" type="button" data-filter-type="status" data-filter-value="all">All statuses</button></li>' +
        statusItems.map(value => `<li><button class="dropdown-item" type="button" data-filter-type="status" data-filter-value="${value}">${value.replace(/-/g, ' ')}</button></li>`).join('');

    assigneeMenu.innerHTML = '<li><button class="dropdown-item active" type="button" data-filter-type="assignee" data-filter-value="all">All assignees</button></li>' +
        assigneeItems.map(value => `<li><button class="dropdown-item" type="button" data-filter-type="assignee" data-filter-value="${value}">${value.replace(/-/g, ' ')}</button></li>`).join('');

    projectMenu.innerHTML = '<li><button class="dropdown-item active" type="button" data-filter-type="project" data-filter-value="all">All projects</button></li>' +
        projectItems.map(value => `<li><button class="dropdown-item" type="button" data-filter-type="project" data-filter-value="${value}">${value.replace(/-/g, ' ')}</button></li>`).join('');

    updateFilterButtonLabels();
}

function setFilterValue(type, value) {
    if (!Object.prototype.hasOwnProperty.call(activeTaskFilters, type)) {
        return;
    }
    activeTaskFilters[type] = value;

    const menu = document.querySelector(`#${type}FilterMenu`);
    if (menu) {
        menu.querySelectorAll('.dropdown-item').forEach(item => {
            item.classList.toggle('active', item.dataset.filterValue === value);
        });
    }

    updateFilterButtonLabels();
    refreshBoardViewsWithFilters();
    emitHciInteraction('filter_changed', {
        type,
        value,
        activeFilters: { ...activeTaskFilters }
    });
}

function resetAllFilters() {
    setFilterValue('status', 'all');
    setFilterValue('assignee', 'all');
    setFilterValue('project', 'all');
    setFilterValue('due', 'all');
    emitHciInteraction('filters_cleared', {
        activeFilters: { ...activeTaskFilters }
    });
}

function setupFilterControls() {
    const filtersSection = document.querySelector('#filtersSection');
    if (!filtersSection || filtersSection.dataset.bound === 'true') {
        return;
    }
    filtersSection.dataset.bound = 'true';

    filtersSection.addEventListener('click', (event) => {
        const item = event.target.closest('.dropdown-item[data-filter-type][data-filter-value]');
        if (!item) return;
        setFilterValue(item.dataset.filterType, item.dataset.filterValue);
    });

    const clearBtn = document.querySelector('#clearFiltersBtn');
    if (clearBtn) {
        clearBtn.addEventListener('click', resetAllFilters);
    }

    updateFilterButtonLabels();
}

function activateTab(tabId) {
    const tabButton = document.querySelector(`#${tabId}`);
    if (!tabButton) {
        return;
    }

    if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
        bootstrap.Tab.getOrCreateInstance(tabButton).show();
        return;
    }

    tabButton.classList.add('active');
}

function setTabVisibility(tabId, paneId, visible) {
    const tabButton = document.querySelector(`#${tabId}`);
    const tabItem = tabButton ? tabButton.closest('.nav-item') : null;
    const pane = document.querySelector(`#${paneId}`);

    if (tabItem) {
        tabItem.style.display = visible ? '' : 'none';
    }

    if (tabButton && !visible) {
        tabButton.classList.remove('active');
    }

    if (pane) {
        pane.style.display = visible ? '' : 'none';
        if (!visible) {
            pane.classList.remove('show', 'active');
        }
    }
}

function applyEmbedViewMode() {
    const headerTitle = document.querySelector('#pageTitle');
    const headerSubtitle = headerTitle ? headerTitle.nextElementSibling : null;
    const actionsSection = document.querySelector('#actionsSection');
    const projectSelectWrap = document.querySelector('#projectSelectWrap');
    const trelloSidebar = document.querySelector('#trelloSidebar');
    const filtersSection = document.querySelector('#filtersSection');
    const headerProjectSelect = document.querySelector('#headerProjectSelect');
    const newOrderBtn = document.querySelector('#newOrderBtn');

    // Keep calendar overview tab dedicated to calendar embed mode.
    setTabVisibility('calendar-overview-tab', 'calendar-overview-view', false);

    if (EMBED_VIEW_MODE === 'tasks') {
        setTabVisibility('calendar-tab', 'calendar-view', false);
        setTabVisibility('calendar-overview-tab', 'calendar-overview-view', false);
        activateTab('table-tab');
        if (actionsSection) actionsSection.style.display = '';
        if (projectSelectWrap) projectSelectWrap.style.display = 'none';
        if (trelloSidebar) trelloSidebar.style.display = '';
        if (filtersSection) filtersSection.style.display = '';
        if (headerSubtitle) headerSubtitle.style.display = '';
        if (headerProjectSelect) headerProjectSelect.style.display = 'none';
        if (newOrderBtn) newOrderBtn.style.display = '';
        return;
    }

    if (EMBED_VIEW_MODE === 'calendar') {
        setTabVisibility('table-tab', 'table-view', false);
        setTabVisibility('kanban-tab', 'kanban-view', false);
        setTabVisibility('calendar-tab', 'calendar-view', true);
        setTabVisibility('calendar-overview-tab', 'calendar-overview-view', true);
        activateTab('calendar-tab');

        if (headerTitle) {
            headerTitle.textContent = 'Calendar';
        }

        if (headerSubtitle) {
            headerSubtitle.style.display = 'none';
        }

        // Hide ACTIONS menu in calendar view
        if (actionsSection) {
            actionsSection.style.display = 'none';
        }

        // Show project selector in calendar view
        if (projectSelectWrap) {
            projectSelectWrap.style.display = 'block';
        }

        // Hide sidebar in calendar view
        if (trelloSidebar) {
            trelloSidebar.style.display = 'none';
        }

        // Hide filters in calendar view
        if (filtersSection) {
            filtersSection.style.display = 'none';
        }

        // Hide New Order button and show project selector in header
        if (newOrderBtn) {
            newOrderBtn.style.display = 'none';
        }
        if (headerProjectSelect) {
            headerProjectSelect.style.display = 'block';
        }
    }
}

function switchCalendarProject(projectId) {
    if (!projectId) return;
    // Get project name from either selector
    let selector = document.querySelector('#headerProjectSelector');
    if (!selector || !selector.offsetParent) {
        selector = document.querySelector('#projectSelector');
    }
    const projectName = selector.options[selector.selectedIndex].text;
    // Store selected project in localStorage
    localStorage.setItem('selectedCalendarProject', projectId);
    // Load calendar with new project
    selectBoard(projectId, projectName);
}

function populateProjectSelector() {
    const filterSelector = document.querySelector('#projectSelector');
    const headerSelector = document.querySelector('#headerProjectSelector');
    if (!filterSelector && !headerSelector) return;
    
    const projectsList = document.querySelector('#projectsList');
    if (!projectsList) return;
    
    // Get all projects from the sidebar
    const projects = projectsList.querySelectorAll('.project-item');
    projects.forEach(proj => {
        const projectId = proj.dataset.projectId;
        const projectName = proj.textContent.trim().replace(/×/g, '').trim();
        if (projectId && projectName) {
            // Add to filter selector
            if (filterSelector) {
                const option = document.createElement('option');
                option.value = projectId;
                option.textContent = projectName;
                filterSelector.appendChild(option);
            }
            // Add to header selector
            if (headerSelector) {
                const option = document.createElement('option');
                option.value = projectId;
                option.textContent = projectName;
                headerSelector.appendChild(option);
            }
        }
    });
    
    // Restore previously selected project
    const lastSelected = localStorage.getItem('selectedCalendarProject');
    if (lastSelected) {
        if (filterSelector) filterSelector.value = lastSelected;
        if (headerSelector) headerSelector.value = lastSelected;
    }
}

function getChecklistCheckedByMap() {
    try {
        return JSON.parse(localStorage.getItem(CHECKED_BY_STORAGE_KEY) || '{}');
    } catch (error) {
        console.warn('Failed to parse checklist checked-by map:', error);
        return {};
    }
}

function getChecklistCheckedBy(cardId, itemId) {
    const info = getChecklistCheckedByInfo(cardId, itemId);
    return info ? info.name : '';
}

function getChecklistCheckedByInfo(cardId, itemId) {
    const map = getChecklistCheckedByMap();
    const raw = map?.[cardId]?.[itemId];
    if (!raw) {
        return null;
    }

    if (typeof raw === 'string') {
        return { name: raw, checkedAt: '' };
    }

    if (typeof raw === 'object') {
        return {
            name: raw.name || '',
            checkedAt: raw.checkedAt || ''
        };
    }

    return null;
}

function setChecklistCheckedBy(cardId, itemId, checkedByName, checkedAt) {
    const map = getChecklistCheckedByMap();
    if (!map[cardId]) {
        map[cardId] = {};
    }
    map[cardId][itemId] = {
        name: checkedByName,
        checkedAt: checkedAt || new Date().toISOString()
    };
    localStorage.setItem(CHECKED_BY_STORAGE_KEY, JSON.stringify(map));
}

function formatCheckedByText(info) {
    if (!info || !info.name) {
        return '';
    }

    if (!info.checkedAt) {
        return `Checked by: ${info.name}`;
    }

    const checkedAt = new Date(info.checkedAt);
    if (Number.isNaN(checkedAt.getTime())) {
        return `Checked by: ${info.name}`;
    }

    const timestamp = checkedAt.toLocaleString('en-US', {
        month: 'short',
        day: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    return `Checked by: ${info.name} · ${timestamp}`;
}

function clearChecklistCheckedBy(cardId, itemId) {
    const map = getChecklistCheckedByMap();
    if (!map[cardId]) return;

    delete map[cardId][itemId];
    if (Object.keys(map[cardId]).length === 0) {
        delete map[cardId];
    }
    localStorage.setItem(CHECKED_BY_STORAGE_KEY, JSON.stringify(map));
}

function getCurrentOperatorName() {
    if (employeeAccessMode && currentEmployeeName) {
        return currentEmployeeName;
    }
    return 'Admin';
}

function getEmployeeNameVariants(name) {
    const normalized = (name || '').trim().toLowerCase();
    if (!normalized) return [];
    const firstName = normalized.split(' ')[0];
    return Array.from(new Set([normalized, firstName]));
}

function extractAssigneeNamesFromCard(card) {
    const assigneeNames = new Set();
    if (!card?.checklists || card.checklists.length === 0) {
        return assigneeNames;
    }

    card.checklists.forEach(checklist => {
        (checklist.checkItems || []).forEach(item => {
            const match = item.name.match(/→\s*(.+)$/);
            if (match && match[1] && match[1].trim() !== 'Unassigned') {
                match[1].split(',').forEach(name => {
                    const cleaned = name.trim();
                    if (cleaned && cleaned.toLowerCase() !== 'unassigned') {
                        assigneeNames.add(cleaned);
                    }
                });
            }
        });
    });

    return assigneeNames;
}

function isCardVisibleToCurrentEmployee(card) {
    if (!employeeAccessMode || !currentEmployeeName) {
        return true;
    }

    const assignees = Array.from(extractAssigneeNamesFromCard(card)).map(name => name.toLowerCase());
    if (assignees.length === 0) {
        return false;
    }

    const employeeNameVariants = getEmployeeNameVariants(currentEmployeeName);
    return assignees.some(assignee => {
        const assigneeVariants = getEmployeeNameVariants(assignee);
        return employeeNameVariants.some(name => assigneeVariants.includes(name));
    });
}

function canCurrentEmployeeEditChecklistItem(itemName) {
    if (!employeeAccessMode || !currentEmployeeName) {
        return true;
    }

    const match = (itemName || '').match(/→\s*(.+)$/);
    if (!match || !match[1]) {
        return false;
    }

    const assignees = match[1]
        .split(',')
        .map(name => name.trim())
        .filter(name => name && name.toLowerCase() !== 'unassigned');

    if (assignees.length === 0) {
        return false;
    }

    const employeeNameVariants = getEmployeeNameVariants(currentEmployeeName);
    return assignees.some(assignee => {
        const assigneeVariants = getEmployeeNameVariants(assignee);
        return employeeNameVariants.some(name => assigneeVariants.includes(name));
    });
}

function parseEnvContent(content) {
    const env = {};
    const lines = content.split(/\r?\n/);

    lines.forEach(line => {
        const trimmed = line.trim();
        if (!trimmed || trimmed.startsWith('#')) {
            return;
        }

        const separatorIndex = trimmed.indexOf('=');
        if (separatorIndex === -1) {
            return;
        }

        const key = trimmed.slice(0, separatorIndex).trim();
        const value = trimmed.slice(separatorIndex + 1).trim();

        if (key) {
            env[key] = value;
        }
    });

    return env;
}

async function loadEnvConfig() {
    if (window.APP_ENV && typeof window.APP_ENV === 'object') {
        TRELLO_API_KEY = window.APP_ENV.TRELLO_API_KEY || TRELLO_API_KEY;
        TRELLO_API_URL = window.APP_ENV.TRELLO_API_URL || TRELLO_API_URL;
        DEFAULT_TOKEN = window.APP_ENV.DEFAULT_TOKEN || DEFAULT_TOKEN;
        return;
    }

    try {
        const response = await fetch('./lib/.env', { cache: 'no-store' });
        if (!response.ok) {
            console.warn('Could not load lib/.env. Status:', response.status);
            return;
        }

        const envText = await response.text();
        const env = parseEnvContent(envText);

        TRELLO_API_KEY = env.TRELLO_API_KEY || TRELLO_API_KEY;
        TRELLO_API_URL = env.TRELLO_API_URL || TRELLO_API_URL;
        DEFAULT_TOKEN = env.DEFAULT_TOKEN || DEFAULT_TOKEN;
    } catch (error) {
        console.warn('Failed to load lib/.env:', error);
    }
}

// Initialize with default token
async function initializeTrello() {
    await loadEnvConfig();

    TRELLO_TOKEN = localStorage.getItem('trelloToken') || DEFAULT_TOKEN;
    if (TRELLO_TOKEN) {
        localStorage.setItem('trelloToken', TRELLO_TOKEN);
    }
    return true;
}

// Show token setup modal
function showTokenSetupModal() {
    const modalHTML = `
        <div class="modal fade" id="setupModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Setup Trello Integration</h5>
                    </div>
                    <div class="modal-body">
                        <p><strong>Paste your Trello API Token</strong></p>
                        <p class="small text-muted">You got this from the Trello Security page (it starts with "ATATT")</p>
                        
                        <div class="form-group mb-3">
                            <input type="text" class="form-control" id="tokenInput" placeholder="Paste your token here (ATATT...)">
                            <small class="form-text text-muted mt-2">Your token is stored locally and never sent to external servers.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="saveCredentials()">Connect</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    new bootstrap.Modal(document.querySelector('#setupModal')).show();
}

// Save token and initialize app
function saveCredentials() {
    const token = document.querySelector('#tokenInput').value.trim();
    
    if (!token) {
        alert('Please enter your token');
        return;
    }
    
    localStorage.setItem('trelloToken', token);
    TRELLO_TOKEN = token;
    
    const modal = bootstrap.Modal.getInstance(document.querySelector('#setupModal'));
    modal.hide();
    
    document.querySelector('#setupModal').remove();
    
    loadWorkspaces();
}

// Make API calls to Trello
async function trelloAPI(endpoint, method = 'GET', body = null) {
    if (!TRELLO_API_KEY) {
        console.error('API key not set');
        return null;
    }

    if (!TRELLO_TOKEN) {
        console.error('Token not set');
        return null;
    }
    
    const url = `${TRELLO_API_URL}${endpoint}?key=${TRELLO_API_KEY}&token=${TRELLO_TOKEN}`;
    
    console.log('===== API CALL =====');
    console.log('Endpoint:', endpoint);
    console.log('Method:', method);
    
    const options = {
        method,
        headers: {
            'Content-Type': 'application/json'
        }
    };
    
    if (body) {
        options.body = JSON.stringify(body);
    }
    
    try {
        // Add timeout of 10 seconds
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 10000);
        
        const response = await fetch(url, {
            ...options,
            signal: controller.signal
        });
        
        clearTimeout(timeoutId);
        
        console.log('Response status:', response.status, response.statusText);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('ERROR Response body:', errorText);
            console.error('Status:', response.status);
            return null;
        }
        
        const data = await response.json();
        console.log('SUCCESS! Data received:', data);
        return data;
    } catch (error) {
        console.error('Fetch error:', error.message);
        console.error('Full error:', error);
        return null;
    }
}

// Load user's workspaces and boards
async function loadWorkspaces() {
    try {
        console.log('Loading workspaces...');
        console.log('Using token:', TRELLO_TOKEN ? TRELLO_TOKEN.substring(0, 20) + '...' : 'NONE');
        
        const workspacesList = document.querySelector('#workspacesList');
        const projectsList = document.querySelector('#projectsList');
        
        if (!workspacesList || !projectsList) {
            console.error('Required DOM elements not found');
            return;
        }
        
        workspacesList.innerHTML = `<span style="color: #0066cc;">Fetching member info...</span>`;
        projectsList.innerHTML = `<span style="color: #0066cc;">Fetching boards...</span>`;
        
        const member = await trelloAPI('/members/me');
        
        if (!member) {
            if (DEFAULT_TOKEN && TRELLO_TOKEN !== DEFAULT_TOKEN) {
                console.warn('Current token failed. Falling back to DEFAULT_TOKEN from environment config.');
                TRELLO_TOKEN = DEFAULT_TOKEN;
                localStorage.setItem('trelloToken', TRELLO_TOKEN);
                return loadWorkspaces();
            }

            const errorMsg = `❌ API Error: Failed to load member data.

Token may be invalid or expired.

Please try:
1. Go to https://trello.com/app-keys
2. Generate a new token  
3. Click Settings and update it`;
            
            workspacesList.innerHTML = `
                <div style="color: red; word-wrap: break-word; font-size: 12px;">${errorMsg}</div>
            `;
            projectsList.innerHTML = `
                <div style="color: red; word-wrap: break-word; font-size: 12px;">Token issue detected. Check console for details.</div>
            `;
            return;
        }
        
        console.log('Member loaded:', member);
        workspacesList.innerHTML = `
            <i class="bi bi-kanban me-2"></i> ${member.fullName || member.username || 'User'}
        `;
        
        await loadBoards(member.id);
    } catch (error) {
        console.error('Error loading workspaces:', error);
        const workspacesList = document.querySelector('#workspacesList');
        if (workspacesList) {
            workspacesList.innerHTML = `
                <div style="color: red; word-wrap: break-word; font-size: 12px;">❌ Error: ${error.message}</div>
            `;
        }
    }
}

// Load user's boards
async function loadBoards(memberId) {
    try {
        console.log('=== Loading boards ===');
        console.log('API Key:', TRELLO_API_KEY);
        console.log('Token (first 30):', TRELLO_TOKEN ? TRELLO_TOKEN.substring(0, 30) + '...' : 'NONE');
        
        const projectsList = document.querySelector('#projectsList');
        if (!projectsList) {
            console.error('projectsList element not found in DOM');
            return;
        }
        
        projectsList.innerHTML = `<span style="color: #0066cc;">Fetching boards...</span>`;
        
        const boards = await trelloAPI(`/members/me/boards`);
        
        console.log('Boards response:', boards);
        console.log('Number of boards:', boards ? boards.length : 0);
        
        if (!boards) {
            console.error('Boards is null - API call failed');
            projectsList.innerHTML = `
                <p style="color: red; font-size: 12px;">❌ API Error: Failed to load boards. Check console for details.</p>
            `;
            return;
        }
        
        if (boards.length === 0) {
            projectsList.innerHTML = `
                <p class="text-muted small">No boards found. Create one at <a href="https://trello.com" target="_blank">Trello.com</a></p>
            `;
            return;
        }
        
        let boardsHTML = '';
        boards.forEach(board => {
            console.log('Processing board:', board.name, board.id);
            const color = getColorForBoard(board.name);
            boardsHTML += `
                <div class="project-item" data-project-id="${board.id}" onclick="selectBoard('${board.id}', '${escapeJsString(board.name)}')">
                    <div class="project-icon" style="background-color: ${color}">
                        ${board.name.charAt(0).toUpperCase()}
                    </div>
                    <span>${escapeHtml(board.name)}</span>
                    <button class="btn btn-sm btn-danger ms-auto" onclick="event.stopPropagation(); deleteBoard('${board.id}', '${escapeJsString(board.name)}')" style="padding: 2px 6px; font-size: 11px;">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
        });
        
        console.log('Setting boards HTML');
        projectsList.innerHTML = boardsHTML;
        
        // Populate calendar project selector
        if (EMBED_VIEW_MODE === 'calendar') {
            populateProjectSelector();
        }
        
        // Load first board by default
        if (boards.length > 0) {
            console.log('Loading first board:', boards[0].name);
            await selectBoard(boards[0].id, boards[0].name);
        }
    } catch (error) {
        console.error('Error loading boards:', error);
        const projectsList = document.querySelector('#projectsList');
        if (projectsList) {
            projectsList.innerHTML = `
                <p style="color: red; font-size: 12px;">❌ Error: ${error.message}</p>
            `;
        }
    }
}

// Delete a board
async function deleteBoard(boardId, boardName) {
    if (!confirm(`Are you sure you want to delete "${boardName}"?\n\nThis action cannot be undone!`)) {
        return;
    }
    
    try {
        const result = await trelloAPI(`/boards/${boardId}`, 'DELETE');
        
        if (result === null) {
            alert('Failed to delete board. Check console for errors.');
            return;
        }
        
        alert(`Board "${boardName}" deleted successfully!`);
        
        // Reload boards
        loadWorkspaces();
    } catch (error) {
        console.error('Error deleting board:', error);
        alert('Error deleting board: ' + error.message);
    }
}

// Select a board and load its cards
async function selectBoard(boardId, boardName) {
    currentBoardId = boardId;
    
    const pageTitle = document.querySelector('#pageTitle');
    if (pageTitle) {
        pageTitle.textContent = boardName;
        const subtitle = pageTitle.nextElementSibling;
        if (subtitle) {
            subtitle.textContent = 'View all cards from this board';
        }
    }
    
    try {
        console.log('Loading lists for board:', boardName);
        
        // Load lists and cards in parallel for better performance
        const lists = await trelloAPI(`/boards/${boardId}/lists`);
        
        if (!lists) {
            console.error('No lists returned for board:', boardId);
            return;
        }
        
        console.log('Lists loaded:', lists.length);
        
        // Load all cards in parallel instead of one by one
        const cardsPromises = lists.map(list => 
            trelloAPI(`/lists/${list.id}/cards`)
        );
        
        const cardsResults = await Promise.all(cardsPromises);
        
        // Attach cards to their respective lists
        lists.forEach((list, index) => {
            list.cards = cardsResults[index] || [];
        });

        // Render dashboard/calendar views immediately from lists+cards,
        // then enrich with checklists asynchronously to avoid perceived 5s delays.
        console.log('Cards loaded. Rendering initial board views immediately...');

        currentBoardLists = lists.map(list => ({ ...list, cards: [...(list.cards || [])] }));
        initializeFilterMenus(currentBoardLists);
        refreshBoardViewsWithFilters();

        const allCards = lists.flatMap(list => list.cards || []);
        Promise.all(
            allCards.map(async card => {
                try {
                    return await trelloAPI(`/cards/${card.id}/checklists`);
                } catch (error) {
                    console.error('Error fetching checklist for card:', card.id, error);
                    return [];
                }
            })
        ).then((checklistResults) => {
            allCards.forEach((card, index) => {
                card.checklists = checklistResults[index] || [];
            });

            currentBoardLists = lists.map(list => ({ ...list, cards: [...(list.cards || [])] }));
            refreshBoardViewsWithFilters();
            console.log('Checklist enrichment complete. Board views refreshed.');
        });
    } catch (error) {
        console.error('Error loading board:', error);
    }
}

// Load table view with all cards
async function loadTableView(lists) {
    let allCards = [];
    const statusColors = {
        'backlog': 'status-backlog',
        'todo': 'status-todo',
        'to do': 'status-todo',
        'in progress': 'status-in-progress',
        'processing': 'status-in-progress',
        'printing': 'status-in-progress',
        'quality check': 'status-in-review',
        'in review': 'status-in-review',
        'ready for pickup': 'status-in-review',
        'done': 'status-done',
        'completed': 'status-done',
        'complete': 'status-done'
    };
    
    lists.forEach(list => {
        if (list.cards) {
            list.cards.forEach(card => {
                const cardWithList = {
                    ...card,
                    listName: list.name
                };

                if (isCardVisibleToCurrentEmployee(cardWithList)) {
                    allCards.push(cardWithList);
                }
            });
        }
    });
    
    let tableHTML = '';
    
    if (allCards.length === 0) {
        tableHTML = `
            <tr>
                <td colspan="7" class="text-center text-muted py-4">
                    <i class="bi bi-inbox me-2"></i> No cards found
                </td>
            </tr>
        `;
    } else {
        allCards.forEach(card => {
            const dueDate = card.due ? new Date(card.due).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            }) : '-';
            
            const statusClass = statusColors[normalizeListName(card.listName)] || 'status-backlog';
            
            // Extract assignees from checklists
            const assigneeNames = extractAssigneeNamesFromCard(card);
            
            let assigneeHTML = '-';
            if (assigneeNames.size > 0) {
                const names = Array.from(assigneeNames);
                if (names.length <= 3) {
                    assigneeHTML = names.map(name => {
                        const initials = name.split(' ').map(n => n[0]).join('').toUpperCase();
                        return `<span class="assignee-avatar" title="${escapeHtml(name)}">${initials}</span>`;
                    }).join(' ');
                } else {
                    const firstTwo = names.slice(0, 2);
                    const remaining = names.length - 2;
                    assigneeHTML = firstTwo.map(name => {
                        const initials = name.split(' ').map(n => n[0]).join('').toUpperCase();
                        return `<span class="assignee-avatar" title="${escapeHtml(name)}">${initials}</span>`;
                    }).join(' ') + ` <span class="assignee-avatar" title="${escapeHtml(names.slice(2).join(', '))}">+${remaining}</span>`;
                }
            }
            
            tableHTML += `
                <tr class="clickable-row" onclick="showCardDetails('${card.id}')" title="Click to view task details">
                    <td>
                        <input type="checkbox" class="form-check-input" onclick="event.stopPropagation()">
                    </td>
                    <td><strong>${escapeHtml(card.name)}</strong></td>
                    <td>${escapeHtml(card.listName)}</td>
                    <td>${assigneeHTML}</td>
                    <td>${dueDate}</td>
                    <td>
                        <span class="task-status-badge ${statusClass}">
                            ${card.listName}
                        </span>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-ghost" onclick="event.stopPropagation()" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); event.stopPropagation(); updateCard('${card.id}', '${escapeJsString(card.name)}'); return false;"><i class="bi bi-pencil me-2"></i>Update</a></li>
                                <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); event.stopPropagation(); insertTask('${card.listId}'); return false;"><i class="bi bi-plus-circle me-2"></i>Insert</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); event.stopPropagation(); deleteCard('${card.id}', '${escapeJsString(card.name)}'); return false;"><i class="bi bi-trash me-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            `;
        });
    }
    
    const tasksTable = document.querySelector('#tasksTable');
    if (tasksTable) {
        tasksTable.innerHTML = tableHTML;
    }
}

// Delete a card
async function deleteCard(cardId, cardName) {
    if (!confirm(`Are you sure you want to delete this task?\n\n"${cardName}"\n\nThis action cannot be undone!`)) {
        return;
    }
    
    try {
        const result = await trelloAPI(`/cards/${cardId}`, 'DELETE');
        
        if (result === null) {
            alert('Failed to delete task. Check console for errors.');
            return;
        }
        
        alert(`Task "${cardName}" deleted successfully!`);
        
        // Reload current board
        if (currentBoardId) {
            const boardName = allBoards.find(b => b.id === currentBoardId)?.name || 'Board';
            selectBoard(currentBoardId, boardName);
        }
    } catch (error) {
        console.error('Error deleting card:', error);
        alert('Error deleting task: ' + error.message);
    }
}

// Update a card
async function updateCard(cardId, cardName) {
    const newName = prompt('Update task name:', cardName);
    const normalizedNewName = (newName || '').trim();
    const normalizedCurrentName = (cardName || '').trim();
    
    if (!normalizedNewName || normalizedNewName === normalizedCurrentName) {
        return;
    }
    
    try {
        const result = await trelloAPI(`/cards/${cardId}`, 'PUT', {
            name: normalizedNewName
        });
        
        if (result) {
            alert('✅ Task updated successfully!');
            
            // Reload current board
            if (currentBoardId) {
                const boardName = allBoards.find(b => b.id === currentBoardId)?.name || 'Board';
                selectBoard(currentBoardId, boardName);
            }
        } else {
            alert('Failed to update task.');
        }
    } catch (error) {
        console.error('Error updating card:', error);
        alert('Error updating task: ' + error.message);
    }
}

// Insert a new task
async function insertTask(listId) {
    if (!listId) {
        alert('Cannot insert task: target list is missing. Please reload the board and try again.');
        return;
    }

    const taskName = prompt('Enter new task name:');
    const normalizedTaskName = (taskName || '').trim();
    
    if (!normalizedTaskName) {
        return;
    }
    
    try {
        const newCard = await trelloAPI('/cards', 'POST', {
            name: normalizedTaskName,
            idList: listId,
            pos: 'top'
        });
        
        if (newCard) {
            alert('✅ Task created successfully!');
            
            // Reload current board
            if (currentBoardId) {
                const boardName = allBoards.find(b => b.id === currentBoardId)?.name || 'Board';
                selectBoard(currentBoardId, boardName);
            }
        } else {
            alert('Failed to create task.');
        }
    } catch (error) {
        console.error('Error creating card:', error);
        alert('Error creating task: ' + error.message);
    }
}

// Load kanban view
function loadKanbanView(lists, boardId) {
    let kanbanHTML = '';
    const stageConfig = [
        {
            key: 'todo',
            title: 'To Do',
            color: '#0079BF',
            listNames: ['to do', 'todo', 'backlog']
        },
        {
            key: 'process',
            title: 'Process',
            color: '#FFD93D',
            listNames: ['in progress', 'processing', 'printing', 'quality check', 'ready for pickup']
        },
        {
            key: 'completed',
            title: 'Completed',
            color: '#4D96FF',
            listNames: ['completed', 'done', 'complete']
        }
    ];

    const stages = stageConfig.map(stage => ({
        ...stage,
        cards: [],
        targetListId: ''
    }));

    const getStageKeyForListName = (name) => {
        const normalized = normalizeListName(name);
        if (stageConfig[0].listNames.includes(normalized)) return 'todo';
        if (stageConfig[2].listNames.includes(normalized)) return 'completed';
        return 'process';
    };

    stages.forEach(stage => {
        const directMatch = stage.listNames
            .map(stageListName => lists.find(list => normalizeListName(list.name) === stageListName))
            .find(Boolean);

        if (directMatch) {
            stage.targetListId = directMatch.id;
            return;
        }

        const fallbackMatch = lists.find(list => getStageKeyForListName(list.name) === stage.key);
        stage.targetListId = fallbackMatch ? fallbackMatch.id : '';
    });

    lists.forEach(list => {
        const stageKey = getStageKeyForListName(list.name);
        const stage = stages.find(item => item.key === stageKey);
        if (!stage) return;

        const visibleCards = (list.cards || []).filter(card => isCardVisibleToCurrentEmployee({ ...card, listName: list.name }));
        visibleCards.forEach(card => {
            stage.cards.push({ ...card, sourceListId: list.id, sourceListName: list.name });
        });
    });

    stages.forEach(stage => {
        let cardsHTML = '';

        if (stage.cards.length > 0) {
            stage.cards.forEach(card => {
                const dueDate = card.due ? new Date(card.due).toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric'
                }) : null;

                cardsHTML += `
                    <div class="kanban-card" 
                         draggable="true" 
                         data-card-id="${card.id}"
                         data-list-id="${card.sourceListId || card.idList}"
                         ondragstart="handleDragStart(event)" 
                         ondragend="handleDragEnd(event)"
                         onclick="showCardDetails('${card.id}')">
                        <div class="kanban-card-title">${escapeHtml(card.name)}</div>
                        <div class="kanban-card-footer">
                            <div class="kanban-card-meta">
                                ${card.idMembers && card.idMembers.length > 0 ? `
                                    <span class="assignee-avatar" style="width: 20px; height: 20px; font-size: 10px;">
                                        ${card.name.substring(0, 1).toUpperCase()}
                                    </span>
                                ` : ''}
                                ${dueDate ? `<span style="color: #ffa500;">📅 ${dueDate}</span>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });
        }

        kanbanHTML += `
            <div class="kanban-column" 
                 data-list-id="${stage.targetListId}"
                 ondragover="handleDragOver(event)" 
                 ondrop="handleDrop(event)"
                 ondragleave="handleDragLeave(event)">
                <div class="column-header">
                    <span style="color: ${stage.color};">● ${escapeHtml(stage.title)}</span>
                    <span class="badge bg-secondary">${stage.cards.length}</span>
                </div>
                <div class="kanban-cards-container">
                    ${cardsHTML || '<p class="text-muted small">No cards</p>'}
                </div>
            </div>
        `;
    });
    
    const kanbanBoard = document.querySelector('#kanbanBoard');
    if (kanbanBoard) {
        kanbanBoard.innerHTML = kanbanHTML || '<p>No lists found</p>';
    }
}

// Drag and drop handlers
let draggedCard = null;

function handleDragStart(event) {
    draggedCard = event.currentTarget;
    event.currentTarget.style.opacity = '0.5';
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/html', event.currentTarget.innerHTML);
}

function handleDragEnd(event) {
    event.currentTarget.style.opacity = '1';
}

function handleDragOver(event) {
    if (event.preventDefault) {
        event.preventDefault();
    }
    event.dataTransfer.dropEffect = 'move';
    
    const column = event.currentTarget;
    if (column.classList.contains('kanban-column')) {
        column.style.backgroundColor = '#e3f2fd';
    }
    
    return false;
}

function handleDragLeave(event) {
    const column = event.currentTarget;
    if (column.classList.contains('kanban-column')) {
        column.style.backgroundColor = '';
    }
}

async function handleDrop(event) {
    if (event.stopPropagation) {
        event.stopPropagation();
    }
    
    event.preventDefault();
    
    const column = event.currentTarget;
    column.style.backgroundColor = '';
    
    if (!draggedCard) return false;
    
    const cardId = draggedCard.getAttribute('data-card-id');
    const oldListId = draggedCard.getAttribute('data-list-id');
    const newListId = column.getAttribute('data-list-id');

    if (!newListId) {
        return false;
    }
    
    if (oldListId === newListId) {
        return false;
    }
    
    // Move card in DOM immediately for instant feedback
    const cardsContainer = column.querySelector('.kanban-cards-container');
    if (cardsContainer) {
        const cardClone = draggedCard.cloneNode(true);
        cardClone.setAttribute('data-list-id', newListId);
        cardClone.ondragstart = handleDragStart;
        cardClone.ondragend = handleDragEnd;
        
        cardsContainer.insertBefore(cardClone, cardsContainer.firstChild);
        draggedCard.remove();
        
        // Update counters immediately
        updateColumnCounters();
    }
    
    // Update Trello API in background
    trelloAPI(`/cards/${cardId}`, 'PUT', {
        idList: newListId
    }).catch(error => {
        console.error('Error moving card:', error);
        // Reload on error
        if (currentBoardId) {
            const boardName = allBoards.find(b => b.id === currentBoardId)?.name || 'Board';
            selectBoard(currentBoardId, boardName);
        }
    });
    
    return false;
}

// Update column card counters
function updateColumnCounters() {
    document.querySelectorAll('.kanban-column').forEach(column => {
        const cardsContainer = column.querySelector('.kanban-cards-container');
        const cards = cardsContainer ? cardsContainer.querySelectorAll('.kanban-card').length : 0;
        const badge = column.querySelector('.badge');
        if (badge) {
            badge.textContent = cards;
        }
    });
}

// Show card details modal
async function showCardDetails(cardId) {
    try {
        const card = await trelloAPI(`/cards/${cardId}?fields=all&members=true&checklists=open`);
        
        if (!card) return;
        
        const dueDate = card.due ? new Date(card.due).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }) : 'Not set';
        
        let checklistsHTML = '';
        if (card.checklists && card.checklists.length > 0) {
            card.checklists.forEach(checklist => {
                const completed = checklist.checkItems.filter(item => item.state === 'complete').length;
                const total = checklist.checkItems.length;
                checklistsHTML += `
                    <div class="mt-3">
                        <h6>${escapeHtml(checklist.name)}</h6>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" style="width: ${(completed/total)*100}%"></div>
                        </div>
                        <small class="text-muted">${completed}/${total} completed</small>
                    </div>
                `;
            });
        }
        
        const modalContent = `
            <div>
                <h5>${escapeHtml(card.name)}</h5>
                <hr>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Description:</strong>
                        <p>${card.desc ? escapeHtml(card.desc) : 'No description'}</p>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Due Date:</strong>
                            <p>${dueDate}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Labels:</strong>
                            <div>
                                ${card.labels && card.labels.length > 0 ? 
                                    card.labels.map(label => `
                                        <span class="badge" style="background-color: ${label.color}">${label.name || label.color}</span>
                                    `).join('') : 
                                    'No labels'}
                            </div>
                        </div>
                    </div>
                </div>
                ${checklistsHTML}
            </div>
        `;
        
        const taskModalContent = document.querySelector('#taskModalContent');
        const taskModal = document.querySelector('#taskModal');
        if (taskModalContent && taskModal) {
            taskModalContent.innerHTML = modalContent;
            new bootstrap.Modal(taskModal).show();
        }
    } catch (error) {
        console.error('Error loading card details:', error);
    }
}

// Utility functions
function getColorForBoard(name) {
    const colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8'];
    let hash = 0;
    for (let i = 0; i < name.length; i++) {
        hash = name.charCodeAt(i) + ((hash << 5) - hash);
    }
    return colors[Math.abs(hash) % colors.length];
}

function getColorForList(name) {
    const colors = {
        'Backlog': '#808080',
        'Todo': '#FF6B6B',
        'In Progress': '#FFD93D',
        'In Review': '#6BCB77',
        'Done': '#4D96FF'
    };
    return colors[name] || '#0079BF';
}

function getCardCreatedDate(cardId) {
    if (!cardId || cardId.length < 8) {
        return null;
    }

    const timestampHex = cardId.substring(0, 8);
    const timestampMs = parseInt(timestampHex, 16) * 1000;

    if (Number.isNaN(timestampMs)) {
        return null;
    }

    return new Date(timestampMs);
}

function isSameCalendarDay(date, day, month, year) {
    return date &&
        date.getDate() === day &&
        date.getMonth() === month &&
        date.getFullYear() === year;
}

// Calendar View Functions
let currentCalendarDate = new Date();
let calendarCards = [];
let calendarOverviewRows = [];
let calendarOverviewSearchQuery = '';
let calendarOverviewPage = 1;
let calendarOverviewPageSize = 8;
let calendarOverviewStatusFilter = 'all';
let calendarOverviewDueFilter = 'all';
let currentCalendarNoteDate = '';
let currentCalendarEditingNoteId = '';
const CALENDAR_NOTES_STORAGE_KEY = 'calendarPersonalNotes';

function getCalendarNotes() {
    try {
        const notes = JSON.parse(localStorage.getItem(CALENDAR_NOTES_STORAGE_KEY) || '[]');
        return Array.isArray(notes) ? notes : [];
    } catch (error) {
        console.warn('Failed to read calendar notes:', error);
        return [];
    }
}

function saveCalendarNotes(notes) {
    localStorage.setItem(CALENDAR_NOTES_STORAGE_KEY, JSON.stringify(notes));
}

function normalizeCalendarDateString(dateValue) {
    if (!dateValue) return '';
    const date = new Date(dateValue);
    if (Number.isNaN(date.getTime())) return '';
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function getCalendarNotesForDay(year, month, day) {
    return getCalendarNotes().filter((note) => {
        const noteDate = new Date(note.date);
        return !Number.isNaN(noteDate.getTime()) &&
            noteDate.getFullYear() === year &&
            noteDate.getMonth() === month &&
            noteDate.getDate() === day;
    });
}

function getCalendarNoteModalElements() {
    return {
        modal: document.querySelector('#calendarNoteModal'),
        dateLabel: document.querySelector('#calendarNoteModalDateLabel'),
        noteDateInput: document.querySelector('#calendarNoteDate'),
        noteTextInput: document.querySelector('#calendarNoteText'),
        addButton: document.querySelector('#addCalendarNoteBtn'),
        notesCount: document.querySelector('#calendarNotesCount'),
        notesList: document.querySelector('#calendarNotesList')
    };
}

function renderCalendarNoteModal() {
    const elements = getCalendarNoteModalElements();
    if (!elements.modal || !elements.notesList || !elements.notesCount) {
        return;
    }

    const selectedDate = currentCalendarNoteDate || normalizeCalendarDateString(currentCalendarDate.toISOString());
    const selectedDateObject = selectedDate ? new Date(`${selectedDate}T00:00:00`) : null;
    const notes = getCalendarNotes().filter((note) => note.date === selectedDate).sort((a, b) => String(a.createdAt || '').localeCompare(String(b.createdAt || '')));

    if (elements.dateLabel) {
        elements.dateLabel.textContent = selectedDateObject && !Number.isNaN(selectedDateObject.getTime())
            ? selectedDateObject.toLocaleDateString('en-US', { weekday: 'long', month: 'short', day: '2-digit', year: 'numeric' })
            : 'Select a date';
    }

    if (elements.noteDateInput) {
        elements.noteDateInput.value = selectedDate || '';
    }

    if (elements.noteTextInput) {
        const notes = getCalendarNotes();
        const editingNote = currentCalendarEditingNoteId
            ? notes.find((note) => note.id === currentCalendarEditingNoteId)
            : null;
        elements.noteTextInput.value = editingNote ? (editingNote.text || '') : '';
        if (elements.addButton) {
            elements.addButton.innerHTML = editingNote
                ? '<i class="bi bi-check-lg me-1"></i> Save'
                : '<i class="bi bi-plus-lg me-1"></i> Add';
        }
    }

    elements.notesCount.textContent = `${notes.length} note${notes.length === 1 ? '' : 's'}`;

    if (!notes.length) {
        elements.notesList.innerHTML = '<div class="text-muted small">No notes for this date yet.</div>';
        return;
    }

    elements.notesList.innerHTML = notes.map((note) => {
        const createdAt = note.createdAt ? new Date(note.createdAt) : null;
        const createdLabel = createdAt && !Number.isNaN(createdAt.getTime())
            ? createdAt.toLocaleString('en-US', {
                month: 'short',
                day: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            })
            : 'Saved';

        return `
            <div class="calendar-note-item" data-note-id="${escapeHtml(note.id)}">
                <div class="calendar-note-meta">
                    <strong>${escapeHtml(createdLabel)}</strong>
                    <div class="d-flex gap-2 align-items-center">
                        <button type="button" class="btn btn-sm btn-link text-primary p-0 calendar-note-edit" data-note-id="${escapeHtml(note.id)}">Edit</button>
                        <button type="button" class="btn btn-sm btn-link text-danger p-0 calendar-note-delete" data-note-id="${escapeHtml(note.id)}">Delete</button>
                    </div>
                </div>
                <div class="calendar-note-text">${escapeHtml(note.text)}</div>
            </div>
        `;
    }).join('');
}

function beginCalendarNoteEdit(noteId) {
    const note = getCalendarNotes().find((item) => item.id === noteId);
    if (!note) return;

    currentCalendarNoteDate = normalizeCalendarDateString(note.date || currentCalendarDate.toISOString());
    currentCalendarEditingNoteId = noteId;
    renderCalendarNoteModal();

    emitHciInteraction('calendar_note_edit_opened', {
        noteId,
        date: currentCalendarNoteDate
    });
}

function resetCalendarNoteEditorState() {
    currentCalendarEditingNoteId = '';
}

function addCalendarNote() {
    const noteDateInput = document.querySelector('#calendarNoteDate');
    const noteTextInput = document.querySelector('#calendarNoteText');
    if (!noteDateInput || !noteTextInput) return;

    const dateValue = normalizeCalendarDateString(noteDateInput.value || currentCalendarDate.toISOString());
    const noteText = String(noteTextInput.value || '').trim();
    if (!dateValue || !noteText) {
        return;
    }

    const notes = getCalendarNotes();
    const now = new Date().toISOString();

    if (currentCalendarEditingNoteId) {
        const index = notes.findIndex((note) => note.id === currentCalendarEditingNoteId);
        if (index !== -1) {
            notes[index] = {
                ...notes[index],
                date: dateValue,
                text: noteText,
                updatedAt: now
            };
        }
    } else {
        notes.unshift({
            id: `note_${Date.now()}_${Math.random().toString(36).slice(2, 8)}`,
            date: dateValue,
            text: noteText,
            createdAt: now,
            updatedAt: now
        });
    }
    saveCalendarNotes(notes);
    noteTextInput.value = '';
    currentCalendarNoteDate = dateValue;
    const wasEditing = Boolean(currentCalendarEditingNoteId);
    const savedNoteId = currentCalendarEditingNoteId;
    currentCalendarEditingNoteId = '';
    renderCalendar();
    renderCalendarNoteModal();
    emitHciInteraction(wasEditing ? 'calendar_note_updated' : 'calendar_note_added', {
        noteId: savedNoteId || undefined,
        date: dateValue,
        textPreview: noteText.slice(0, 80)
    });
}

function deleteCalendarNote(noteId) {
    const notes = getCalendarNotes().filter((note) => note.id !== noteId);
    saveCalendarNotes(notes);
    if (currentCalendarEditingNoteId === noteId) {
        currentCalendarEditingNoteId = '';
    }
    renderCalendar();
    renderCalendarNoteModal();
    emitHciInteraction('calendar_note_deleted', { noteId });
}

function openCalendarNoteEditor(dateValue) {
    currentCalendarNoteDate = normalizeCalendarDateString(dateValue || currentCalendarDate.toISOString());
    currentCalendarEditingNoteId = '';
    renderCalendarNoteModal();

    const elements = getCalendarNoteModalElements();
    if (elements.modal) {
        bootstrap.Modal.getOrCreateInstance(elements.modal).show();
    }

    emitHciInteraction('calendar_note_editor_opened', {
        date: currentCalendarNoteDate
    });
}

function getCalendarOverviewStatusClass(status) {
    const normalized = normalizeListName(status || '');
    if (normalized.includes('done') || normalized.includes('complete')) {
        return 'text-bg-success';
    }
    if (normalized.includes('review') || normalized.includes('quality')) {
        return 'text-bg-info';
    }
    if (normalized.includes('progress') || normalized.includes('processing') || normalized.includes('printing')) {
        return 'text-bg-warning';
    }
    if (normalized.includes('cancel')) {
        return 'text-bg-danger';
    }
    return 'text-bg-secondary';
}

function formatOverviewDate(dateValue) {
    if (!dateValue) return 'No date';
    const date = new Date(dateValue);
    if (Number.isNaN(date.getTime())) return 'No date';
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: '2-digit'
    });
}

function rowMatchesOverviewDueFilter(row) {
    if (calendarOverviewDueFilter === 'all') {
        return true;
    }

    const now = new Date();
    const todayStart = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    const nextWeek = new Date(todayStart);
    nextWeek.setDate(nextWeek.getDate() + 7);

    const dueDate = row.dueDate && !Number.isNaN(row.dueDate.getTime()) ? row.dueDate : null;

    if (calendarOverviewDueFilter === 'withDue') {
        return Boolean(dueDate);
    }
    if (calendarOverviewDueFilter === 'noDue') {
        return !dueDate;
    }
    if (calendarOverviewDueFilter === 'overdue') {
        return Boolean(dueDate) && dueDate < todayStart;
    }
    if (calendarOverviewDueFilter === 'next7') {
        return Boolean(dueDate) && dueDate >= todayStart && dueDate < nextWeek;
    }

    return true;
}

function refreshOverviewStatusFilterOptions() {
    const statusSelect = document.querySelector('#calendarOverviewStatusFilter');
    if (!statusSelect) {
        return;
    }

    const previousValue = calendarOverviewStatusFilter || statusSelect.value || 'all';
    const statuses = Array.from(new Set(calendarOverviewRows.map((row) => String(row.listName || '').trim()).filter(Boolean))).sort((a, b) => a.localeCompare(b));

    const optionsHtml = ['<option value="all">All</option>']
        .concat(statuses.map((status) => `<option value="${escapeHtml(status.toLowerCase())}">${escapeHtml(status)}</option>`))
        .join('');

    statusSelect.innerHTML = optionsHtml;

    const hasPrevious = previousValue === 'all' || statuses.some((status) => status.toLowerCase() === previousValue);
    calendarOverviewStatusFilter = hasPrevious ? previousValue : 'all';
    statusSelect.value = calendarOverviewStatusFilter;
}

function getFilteredCalendarOverviewRows() {
    const query = String(calendarOverviewSearchQuery || '').trim().toLowerCase();
    return calendarOverviewRows.filter((row) => {
        const statusValue = String(row.listName || '').toLowerCase();
        const statusMatch = calendarOverviewStatusFilter === 'all' || statusValue === calendarOverviewStatusFilter;
        const dueMatch = rowMatchesOverviewDueFilter(row);
        const taskName = String(row.name || '').toLowerCase();
        const status = statusValue;
        const dueDate = formatOverviewDate(row.dueDate).toLowerCase();
        const createdDate = formatOverviewDate(row.createdDate).toLowerCase();
        const queryMatch = !query || taskName.includes(query) || status.includes(query) || dueDate.includes(query) || createdDate.includes(query);
        return statusMatch && dueMatch && queryMatch;
    });
}

function renderCalendarOverviewList() {
    const listContainer = document.querySelector('#calendarOverviewList');
    const stats = document.querySelector('#calendarOverviewStats');
    const pageInfo = document.querySelector('#calendarOverviewPageInfo');
    const prevBtn = document.querySelector('#calendarOverviewPrev');
    const nextBtn = document.querySelector('#calendarOverviewNext');

    if (!listContainer) {
        return;
    }

    const filteredRows = getFilteredCalendarOverviewRows();
    const totalRows = filteredRows.length;
    const totalPages = Math.max(1, Math.ceil(totalRows / calendarOverviewPageSize));
    if (calendarOverviewPage > totalPages) {
        calendarOverviewPage = totalPages;
    }

    const startIndex = (calendarOverviewPage - 1) * calendarOverviewPageSize;
    const pagedRows = filteredRows.slice(startIndex, startIndex + calendarOverviewPageSize);

    if (stats) {
        stats.textContent = `${totalRows} task${totalRows === 1 ? '' : 's'}`;
    }

    if (pageInfo) {
        pageInfo.textContent = `Page ${calendarOverviewPage} of ${totalPages}`;
    }
    if (prevBtn) {
        prevBtn.disabled = calendarOverviewPage <= 1;
    }
    if (nextBtn) {
        nextBtn.disabled = calendarOverviewPage >= totalPages;
    }

    if (!totalRows) {
        const noResultsText = calendarOverviewSearchQuery
            ? 'No tasks match your search.'
            : 'No tasks found for this project.';
        listContainer.innerHTML = `<div class="text-muted py-4 text-center">${noResultsText}</div>`;
        return;
    }

    listContainer.innerHTML = pagedRows.map((row) => {
        const statusClass = getCalendarOverviewStatusClass(row.listName);
        return `
            <div class="calendar-overview-item" role="button" tabindex="0" onclick="showCardDetails('${row.id}')" onkeydown="if(event.key==='Enter'){showCardDetails('${row.id}')}">
                <div class="calendar-overview-task" title="${escapeHtml(row.name)}">${escapeHtml(row.name)}</div>
                <div class="calendar-overview-date">
                    <span class="label">Due Date</span>
                    ${escapeHtml(formatOverviewDate(row.dueDate))}
                </div>
                <div class="calendar-overview-date">
                    <span class="label">Created</span>
                    ${escapeHtml(formatOverviewDate(row.createdDate))}
                </div>
                <div class="calendar-overview-status">
                    <span class="badge ${statusClass}">${escapeHtml(row.listName || 'Unknown')}</span>
                </div>
            </div>
        `;
    }).join('');
}

function setupCalendarOverviewControls() {
    const searchInput = document.querySelector('#calendarOverviewSearch');
    const statusFilterSelect = document.querySelector('#calendarOverviewStatusFilter');
    const dueFilterSelect = document.querySelector('#calendarOverviewDueFilter');
    const pageSizeSelect = document.querySelector('#calendarOverviewPageSize');
    const prevBtn = document.querySelector('#calendarOverviewPrev');
    const nextBtn = document.querySelector('#calendarOverviewNext');

    if (searchInput && searchInput.dataset.bound !== 'true') {
        searchInput.dataset.bound = 'true';
        searchInput.addEventListener('input', () => {
            calendarOverviewSearchQuery = searchInput.value || '';
            calendarOverviewPage = 1;
            renderCalendarOverviewList();
            emitHciInteraction('calendar_overview_search_changed', {
                queryLength: calendarOverviewSearchQuery.trim().length,
                boardId: currentBoardId
            });
        });
    }

    if (statusFilterSelect && statusFilterSelect.dataset.bound !== 'true') {
        statusFilterSelect.dataset.bound = 'true';
        statusFilterSelect.addEventListener('change', () => {
            calendarOverviewStatusFilter = statusFilterSelect.value || 'all';
            calendarOverviewPage = 1;
            renderCalendarOverviewList();
            emitHciInteraction('calendar_overview_filter_changed', {
                type: 'status',
                value: calendarOverviewStatusFilter,
                boardId: currentBoardId
            });
        });
    }

    if (dueFilterSelect && dueFilterSelect.dataset.bound !== 'true') {
        dueFilterSelect.dataset.bound = 'true';
        dueFilterSelect.addEventListener('change', () => {
            calendarOverviewDueFilter = dueFilterSelect.value || 'all';
            calendarOverviewPage = 1;
            renderCalendarOverviewList();
            emitHciInteraction('calendar_overview_filter_changed', {
                type: 'due',
                value: calendarOverviewDueFilter,
                boardId: currentBoardId
            });
        });
    }

    if (pageSizeSelect && pageSizeSelect.dataset.bound !== 'true') {
        pageSizeSelect.dataset.bound = 'true';
        pageSizeSelect.addEventListener('change', () => {
            const size = parseInt(pageSizeSelect.value, 10);
            calendarOverviewPageSize = Number.isFinite(size) && size > 0 ? size : 8;
            calendarOverviewPage = 1;
            renderCalendarOverviewList();
            emitHciInteraction('calendar_overview_page_size_changed', {
                pageSize: calendarOverviewPageSize,
                boardId: currentBoardId
            });
        });
    }

    if (prevBtn && prevBtn.dataset.bound !== 'true') {
        prevBtn.dataset.bound = 'true';
        prevBtn.addEventListener('click', () => {
            if (calendarOverviewPage <= 1) return;
            calendarOverviewPage -= 1;
            renderCalendarOverviewList();
            emitHciInteraction('calendar_overview_page_changed', {
                page: calendarOverviewPage,
                boardId: currentBoardId
            });
        });
    }

    if (nextBtn && nextBtn.dataset.bound !== 'true') {
        nextBtn.dataset.bound = 'true';
        nextBtn.addEventListener('click', () => {
            const totalRows = getFilteredCalendarOverviewRows().length;
            const totalPages = Math.max(1, Math.ceil(totalRows / calendarOverviewPageSize));
            if (calendarOverviewPage >= totalPages) return;
            calendarOverviewPage += 1;
            renderCalendarOverviewList();
            emitHciInteraction('calendar_overview_page_changed', {
                page: calendarOverviewPage,
                boardId: currentBoardId
            });
        });
    }
}

function loadCalendarOverviewView(lists) {
    const rows = [];
    (lists || []).forEach((list) => {
        (list.cards || []).forEach((card) => {
            const cardWithList = {
                ...card,
                listName: list.name
            };
            if (!isCardVisibleToCurrentEmployee(cardWithList)) {
                return;
            }

            const createdDate = getCardCreatedDate(card.id);
            const dueDate = card.due ? new Date(card.due) : null;
            const rankingDate = dueDate && !Number.isNaN(dueDate.getTime()) ? dueDate : createdDate;

            rows.push({
                id: card.id,
                name: card.name,
                listName: list.name,
                createdDate,
                dueDate,
                rankingDate
            });
        });
    });

    rows.sort((a, b) => {
        const aTime = a.rankingDate && !Number.isNaN(a.rankingDate.getTime()) ? a.rankingDate.getTime() : Number.MAX_SAFE_INTEGER;
        const bTime = b.rankingDate && !Number.isNaN(b.rankingDate.getTime()) ? b.rankingDate.getTime() : Number.MAX_SAFE_INTEGER;
        return aTime - bTime;
    });

    calendarOverviewRows = rows;
    refreshOverviewStatusFilterOptions();
    renderCalendarOverviewList();

    emitHciInteraction('calendar_overview_rendered', {
        taskCount: rows.length,
        boardId: currentBoardId,
        filtered: Boolean(calendarOverviewSearchQuery)
    });
}

function setupCalendarTabTelemetry() {
    const calendarTab = document.querySelector('#calendar-tab');
    const overviewTab = document.querySelector('#calendar-overview-tab');

    if (calendarTab) {
        calendarTab.addEventListener('shown.bs.tab', () => {
            emitHciInteraction('calendar_tab_changed', { tab: 'calendar' });
        });
        calendarTab.addEventListener('click', () => {
            emitHciInteraction('calendar_tab_click', { tab: 'calendar' });
        });
    }

    if (overviewTab) {
        overviewTab.addEventListener('shown.bs.tab', () => {
            emitHciInteraction('calendar_tab_changed', { tab: 'overview' });
        });
        overviewTab.addEventListener('click', () => {
            emitHciInteraction('calendar_tab_click', { tab: 'overview' });
        });
    }
}

function loadCalendarView(lists) {
    calendarCards = [];
    lists.forEach(list => {
        if (list.cards) {
            list.cards.forEach(card => {
                const cardWithList = {
                    ...card,
                    listName: list.name
                };

                if (isCardVisibleToCurrentEmployee(cardWithList)) {
                    calendarCards.push(cardWithList);
                }
            });
        }
    });
    renderCalendar();
}

function renderCalendar() {
    const year = currentCalendarDate.getFullYear();
    const month = currentCalendarDate.getMonth();
    
    // Update header
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                       'July', 'August', 'September', 'October', 'November', 'December'];
    document.querySelector('#calendarMonthYear').textContent = `${monthNames[month]} ${year}`;
    
    // Get first day of month and number of days
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    
    let calendarHTML = '<div class="calendar-weekdays">';
    const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    weekdays.forEach(day => {
        calendarHTML += `<div class="calendar-weekday">${day}</div>`;
    });
    calendarHTML += '</div><div class="calendar-days">';
    
    // Add empty cells for days before month starts
    for (let i = 0; i < firstDay; i++) {
        calendarHTML += '<div class="calendar-day empty"></div>';
    }
    
    // Add days of month
    const today = new Date();
    const todayDateOnly = new Date(today.getFullYear(), today.getMonth(), today.getDate());
    for (let day = 1; day <= daysInMonth; day++) {
        const currentDate = new Date(year, month, day);
        const isToday = currentDate.toDateString() === today.toDateString();
        
        const tasksOnDay = [];
        calendarCards.forEach(card => {
            const createdDate = getCardCreatedDate(card.id);
            if (createdDate && isSameCalendarDay(createdDate, day, month, year)) {
                tasksOnDay.push({
                    card,
                    type: 'created',
                    date: createdDate
                });
            }

            if (card.due) {
                const dueDate = new Date(card.due);
                if (isSameCalendarDay(dueDate, day, month, year)) {
                    tasksOnDay.push({
                        card,
                        type: 'due',
                        date: dueDate
                    });
                }
            }
        });

        const notesOnDay = getCalendarNotesForDay(year, month, day);
        
        const dayKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        let dayClass = 'calendar-day';
        if (isToday) dayClass += ' today';
        if (tasksOnDay.length > 0) dayClass += ' has-tasks';
        if (notesOnDay.length > 0) dayClass += ' has-notes';
        
        calendarHTML += `<div class="${dayClass}" data-calendar-date="${dayKey}" onclick="openCalendarNoteEditor('${dayKey}')" title="Click to add or edit notes">`;
        calendarHTML += `<div class="day-number">${day}</div>`;
        
        if (tasksOnDay.length > 0) {
            calendarHTML += '<div class="day-tasks">';
            tasksOnDay.forEach(task => {
                const card = task.card;
                const isOverdue = task.type === 'due' &&
                    new Date(task.date.getFullYear(), task.date.getMonth(), task.date.getDate()) < todayDateOnly &&
                    card.listName !== 'Done';
                const taskPrefix = task.type === 'created' ? '🆕' : '📅';
                const taskTypeLabel = task.type === 'created' ? 'Created' : 'Deadline';
                calendarHTML += `
                    <div class="calendar-task ${isOverdue ? 'overdue' : ''}" 
                         onclick="event.stopPropagation(); showCardDetails('${card.id}')" 
                         title="${taskTypeLabel}: ${escapeHtml(card.name)}">
                        <span class="task-dot" style="background-color: ${getColorForList(card.listName)}"></span>
                        <span class="task-name">${taskPrefix} ${escapeHtml(card.name.substring(0, 26))}${card.name.length > 26 ? '...' : ''}</span>
                    </div>
                `;
            });
            calendarHTML += '</div>';
        }

        if (notesOnDay.length > 0) {
            calendarHTML += '<div class="day-notes">';
            notesOnDay.slice(0, 2).forEach((note) => {
                calendarHTML += `<div class="calendar-note-pill" title="${escapeHtml(note.text)}">📝 ${escapeHtml(note.text.substring(0, 18))}${note.text.length > 18 ? '...' : ''}</div>`;
            });
            if (notesOnDay.length > 2) {
                calendarHTML += `<div class="calendar-note-pill more">+${notesOnDay.length - 2} more</div>`;
            }
            calendarHTML += '</div>';
        }
        
        calendarHTML += '</div>';
    }
    
    calendarHTML += '</div>';
    const calendar = document.querySelector('#calendar');
    if (calendar) {
        calendar.innerHTML = calendarHTML;
    }
}

function previousMonth() {
    currentCalendarDate.setMonth(currentCalendarDate.getMonth() - 1);
    renderCalendar();
}

function nextMonth() {
    currentCalendarDate.setMonth(currentCalendarDate.getMonth() + 1);
    renderCalendar();
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text ?? '').replace(/[&<>"']/g, m => map[m]);
}

function escapeJsString(text) {
    return String(text ?? '')
        .replace(/\\/g, '\\\\')
        .replace(/'/g, "\\'")
        .replace(/\r/g, '\\r')
        .replace(/\n/g, '\\n');
}

// Initialize application
document.addEventListener('DOMContentLoaded', async () => {
    if (window.parent && window.parent !== window) {
        document.body.classList.add('embedded-mode');
        window.parent.postMessage({
            type: 'hci:interaction',
            detail: {
                action: 'trello_embed_ready',
                source: 'trello-task',
                timestamp: new Date().toISOString(),
                payload: { view: EMBED_VIEW_MODE }
            }
        }, '*');
    }

    applyEmbedViewMode();
    setupCalendarTabTelemetry();
    setupCalendarOverviewControls();
    const addCalendarNoteBtn = document.querySelector('#addCalendarNoteBtn');
    const calendarNotesList = document.querySelector('#calendarNotesList');
    if (addCalendarNoteBtn) {
        addCalendarNoteBtn.addEventListener('click', addCalendarNote);
    }
    const calendarNoteModal = document.querySelector('#calendarNoteModal');
    if (calendarNoteModal) {
        calendarNoteModal.addEventListener('hidden.bs.modal', () => {
            resetCalendarNoteEditorState();
            renderCalendarNoteModal();
        });
    }
    if (calendarNotesList) {
        calendarNotesList.addEventListener('click', (event) => {
            const editBtn = event.target.closest('.calendar-note-edit');
            if (editBtn) {
                beginCalendarNoteEdit(editBtn.dataset.noteId);
                return;
            }
            const deleteBtn = event.target.closest('.calendar-note-delete');
            if (!deleteBtn) return;
            deleteCalendarNote(deleteBtn.dataset.noteId);
        });
    }
    initializeEmployeeAccessMode();
    setupFilterControls();
    const initialized = await initializeTrello();
    if (initialized) {
        loadWorkspaces();
    }
});

// Store current boards data globally
let allBoards = [];
let currentBoardId = null;
let orderCreationMode = 'manual'; // 'manual', 'template', 'ai', or 'quick'

// Dummy employees for the print shop
const employees = [
    { id: 1, name: 'John Smith', role: 'Print Operator', avatar: '👨‍💼', color: '#FF6B6B' },
    { id: 2, name: 'Maria Garcia', role: 'Designer', avatar: '👩‍🎨', color: '#4ECDC4' },
    { id: 3, name: 'James Wilson', role: 'Quality Control', avatar: '👨‍🔧', color: '#95E1D3' },
    { id: 4, name: 'Emily Chen', role: 'Production Manager', avatar: '👩‍💼', color: '#F38181' },
    { id: 5, name: 'Michael Brown', role: 'Finishing Specialist', avatar: '👨‍🏭', color: '#AA96DA' },
    { id: 6, name: 'Sarah Davis', role: 'Customer Service', avatar: '👩‍💻', color: '#FCBAD3' }
];

function updateEmployeeAccessUI() {
    const info = document.querySelector('#employeeAccessInfo');
    const newOrderBtn = document.querySelector('#newOrderBtn');
    const newBoardBtn = document.querySelector('#newBoardBtn');
    const isCalendarEmbedView = EMBED_VIEW_MODE === 'calendar';

    if (employeeAccessMode && currentEmployeeName) {
        if (info) {
            info.style.display = 'block';
            info.textContent = `Employee Mode: ${currentEmployeeName}`;
        }
        if (newOrderBtn) newOrderBtn.style.display = 'none';
        if (newBoardBtn) newBoardBtn.style.display = 'none';
    } else {
        if (info) {
            info.style.display = 'none';
            info.textContent = '';
        }
        if (newOrderBtn) newOrderBtn.style.display = isCalendarEmbedView ? 'none' : '';
        if (newBoardBtn) newBoardBtn.style.display = '';
    }
}

function initializeEmployeeAccessMode() {
    employeeAccessMode = localStorage.getItem('employeeAccessMode') === 'true';
    currentEmployeeName = localStorage.getItem('currentEmployeeName') || '';

    if (!currentEmployeeName) {
        employeeAccessMode = false;
    }

    updateEmployeeAccessUI();
}

function showEmployeeAccessModal() {
    const currentSelection = currentEmployeeName || '';
    const employeeOptions = employees.map(emp => `
        <option value="${escapeHtml(emp.name)}" ${currentSelection === emp.name ? 'selected' : ''}>${escapeHtml(emp.name)} (${escapeHtml(emp.role)})</option>
    `).join('');

    const modalHTML = `
        <div class="modal fade" id="employeeAccessModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-person-badge me-2"></i>Employee Access</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">Select an employee to view only tasks assigned to them.</p>
                        <select class="form-select" id="employeeAccessSelect">
                            <option value="">Choose employee...</option>
                            ${employeeOptions}
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" onclick="disableEmployeeAccess()">Disable Mode</button>
                        <button type="button" class="btn btn-primary" onclick="activateEmployeeAccess()">Enable</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    const existing = document.querySelector('#employeeAccessModal');
    if (existing) {
        existing.remove();
    }

    document.body.insertAdjacentHTML('beforeend', modalHTML);
    const modalElement = document.querySelector('#employeeAccessModal');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();

    modalElement.addEventListener('hidden.bs.modal', () => {
        modal.dispose();
        modalElement.remove();
    });
}

function activateEmployeeAccess() {
    const select = document.querySelector('#employeeAccessSelect');
    const selectedName = select?.value || '';

    if (!selectedName) {
        alert('Please select an employee.');
        return;
    }

    employeeAccessMode = true;
    currentEmployeeName = selectedName;
    localStorage.setItem('employeeAccessMode', 'true');
    localStorage.setItem('currentEmployeeName', selectedName);

    updateEmployeeAccessUI();

    const modalElement = document.querySelector('#employeeAccessModal');
    const modal = modalElement ? bootstrap.Modal.getInstance(modalElement) : null;
    if (modal) modal.hide();

    if (currentBoardId) {
        const boardName = allBoards.find(b => b.id === currentBoardId)?.name || 'Board';
        selectBoard(currentBoardId, boardName);
    }
}

function disableEmployeeAccess() {
    employeeAccessMode = false;
    currentEmployeeName = '';
    localStorage.setItem('employeeAccessMode', 'false');
    localStorage.removeItem('currentEmployeeName');

    updateEmployeeAccessUI();

    const modalElement = document.querySelector('#employeeAccessModal');
    const modal = modalElement ? bootstrap.Modal.getInstance(modalElement) : null;
    if (modal) modal.hide();

    if (currentBoardId) {
        const boardName = allBoards.find(b => b.id === currentBoardId)?.name || 'Board';
        selectBoard(currentBoardId, boardName);
    }
}

// Show assign employee modal with checkboxes
function showAssignEmployeeModal() {
    const currentAssigned = document.querySelector('#assignedEmployees').value.split(',').filter(id => id);
    
    let employeesHTML = employees.map(emp => `
        <div class="form-check mb-3 p-3 border rounded" style="border-color: ${emp.color} !important; border-width: 2px !important;">
            <input class="form-check-input" type="checkbox" id="emp_${emp.id}" value="${emp.id}" ${currentAssigned.includes(String(emp.id)) ? 'checked' : ''}>
            <label class="form-check-label w-100" for="emp_${emp.id}">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2" 
                         style="width: 40px; height: 40px; background-color: ${emp.color}; font-size: 20px; flex-shrink: 0;">
                        ${emp.avatar}
                    </div>
                    <div>
                        <strong>${emp.name}</strong><br>
                        <small class="text-muted">${emp.role}</small>
                    </div>
                </div>
            </label>
        </div>
    `).join('');
    
    const employeeChecklistContainer = document.querySelector('#employeeChecklistContainer');
    const assignEmployeeModal = document.querySelector('#assignEmployeeModal');
    if (employeeChecklistContainer && assignEmployeeModal) {
        employeeChecklistContainer.innerHTML = employeesHTML;
        new bootstrap.Modal(assignEmployeeModal).show();
    }
}

// Save assigned employees
function saveAssignedEmployees() {
    const checkboxes = document.querySelectorAll('#employeeChecklistContainer input[type="checkbox"]:checked');
    const selectedIds = Array.from(checkboxes).map(cb => cb.value).join(',');
    
    document.querySelector('#assignedEmployees').value = selectedIds;
    
    // Update button text
    const selectedEmployees = employees.filter(emp => selectedIds.split(',').includes(String(emp.id)));
    const countText = selectedIds ? `${selectedEmployees.length} Assigned` : '0 Assigned';
    document.querySelector('#assignedEmployeeCount').textContent = countText;
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.querySelector('#assignEmployeeModal'));
    modal.hide();
}

// Open assign step employees modal
function assignStepEmployees(button, stepId) {
    const stepElement = button.closest('.checklist-item');
    const stepTitle = stepElement.querySelector('.step-title').textContent.trim();
    
    // Store current step ID
    window.currentStepId = stepId;
    
    // Update modal title
    document.querySelector('#stepNameDisplay').textContent = stepTitle;
    
    // Get current assignments for this step
    const storedAssignments = JSON.parse(localStorage.getItem('stepAssignments') || '{}');
    const currentAssignments = storedAssignments[stepId] || [];
    
    // Create checklist
    const container = document.querySelector('#stepEmployeeChecklistContainer');
    container.innerHTML = '';
    
    employees.forEach(emp => {
        const isAssigned = currentAssignments.includes(String(emp.id));
        const checkbox = document.createElement('div');
        checkbox.className = 'form-check mb-2';
        checkbox.innerHTML = `
            <input class="form-check-input" type="checkbox" value="${emp.id}" id="step-emp-${emp.id}" ${isAssigned ? 'checked' : ''}>
            <label class="form-check-label" for="step-emp-${emp.id}">
                <strong>${emp.name}</strong>
                <small class="text-muted d-block">${emp.role}</small>
            </label>
        `;
        container.appendChild(checkbox);
    });
    
    // Show modal
    const modal = new bootstrap.Modal(document.querySelector('#assignStepEmployeeModal'));
    modal.show();
}

// Save step assignments
function saveStepAssignedEmployees() {
    const stepId = window.currentStepId;
    const checkboxes = document.querySelectorAll('#stepEmployeeChecklistContainer input[type="checkbox"]:checked');
    const selectedIds = Array.from(checkboxes).map(cb => String(cb.value));
    
    // Store in localStorage
    const storedAssignments = JSON.parse(localStorage.getItem('stepAssignments') || '{}');
    storedAssignments[stepId] = selectedIds;
    localStorage.setItem('stepAssignments', JSON.stringify(storedAssignments));
    
    // Update the badge display
    const assigneesBadge = document.querySelector(`#${stepId}-assignees`);
    if (selectedIds.length > 0) {
        const assignedEmployees = employees.filter(emp => selectedIds.includes(String(emp.id)));
        const names = assignedEmployees.map(e => e.name.split(' ')[0]).join(', ');
        assigneesBadge.textContent = names;
        assigneesBadge.classList.remove('bg-info');
        assigneesBadge.classList.add('bg-success');
    } else {
        assigneesBadge.textContent = 'Unassigned';
        assigneesBadge.classList.add('bg-info');
        assigneesBadge.classList.remove('bg-success');
    }
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.querySelector('#assignStepEmployeeModal'));
    modal.hide();
}

// Load step assignments from localStorage and display them
function loadStepAssignments() {
    const storedAssignments = JSON.parse(localStorage.getItem('stepAssignments') || '{}');
    const steps = document.querySelectorAll('.checklist-item');
    
    steps.forEach(step => {
        // Find step ID
        const checkbox = step.querySelector('input[type="checkbox"]');
        if (!checkbox) return;
        
        const stepId = checkbox.id;
        const assigneesBadge = step.querySelector(`#${stepId}-assignees`);
        if (!assigneesBadge) return;
        
        const assignments = storedAssignments[stepId] || [];
        
        if (assignments.length > 0) {
            const assignedEmployees = employees.filter(emp => assignments.includes(String(emp.id)));
            const names = assignedEmployees.map(e => e.name.split(' ')[0]).join(', ');
            assigneesBadge.textContent = names;
            assigneesBadge.classList.remove('bg-info');
            assigneesBadge.classList.add('bg-success');
        } else {
            assigneesBadge.textContent = 'Unassigned';
            assigneesBadge.classList.add('bg-info');
            assigneesBadge.classList.remove('bg-success');
        }
    });
}

// Generate description with AI Chatbot
async function generateDescriptionWithAI() {
    const specField = document.querySelector('#specifications');
    
    // Store order context for the chatbot
    window.specChatContext = {
        customerName: document.querySelector('#customerName').value || 'Customer',
        printType: document.querySelector('#printType').value || 'print job',
        quantity: document.querySelector('#quantity').value || '1',
        priority: document.querySelector('#priority').value || 'Normal',
        specField: specField
    };
    
    // Open chatbot modal and focus input
    const modal = new bootstrap.Modal(document.querySelector('#specChatbotModal'));
    modal.show();
    
    // Focus on input field for user to type
    setTimeout(() => {
        document.querySelector('#chatbotInput').focus();
    }, 300);
}


// Send chat message
async function sendChatMessage() {
    const input = document.querySelector('#chatbotInput');
    const userMessage = input.value.trim();
    
    if (!userMessage) return;
    
    input.value = '';
    
    // Check if this looks like an order/task request
    const orderKeywords = /^(create|print|order|make|produce|need|generate|design)|\s(units?|pieces?|qty|tshirt|business card|flyer|banner|poster)/i;
    const isOrderRequest = orderKeywords.test(userMessage) || /\d+\s*(units?|pieces?|qty)?/.test(userMessage);
    
    if (isOrderRequest) {
        // Disable send button during processing
        const sendBtn = document.querySelector('#chatbotSendBtn');
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="bi bi-hourglass-split"></i>';
        
        try {
            if (typeof puter === 'undefined') {
                throw new Error('Puter.js not loaded');
            }
            
            const prompt = `Parse this print order request and generate ONLY a structured task breakdown in this exact format:

User request: "${userMessage}"

TITLE: [clear task title]
DESCRIPTION: [2-3 sentences]
CHECKLIST:
- [action 1]
- [action 2]
- [action 3]
- [action 4]
- [action 5]`;
            
            const response = await puter.ai.chat(prompt, {
                model: 'claude-sonnet-4-5'
            });
            
            const aiText = response.message.content[0].text;
            
            // Parse the response
            const titleMatch = aiText.match(/TITLE:\s*(.+)/i);
            const descMatch = aiText.match(/DESCRIPTION:\s*(.+?)(?=CHECKLIST:|$)/is);
            const checklistMatch = aiText.match(/CHECKLIST:\s*([\s\S]+)/i);
            const qtyMatch = userMessage.match(/(\d+)\s*/);
            
            const title = titleMatch ? titleMatch[1].trim() : userMessage;
            const description = descMatch ? descMatch[1].trim() : '';
            let checklist = [];
            
            if (checklistMatch) {
                const items = checklistMatch[1].match(/^[\s-]*(.+)$/gm);
                if (items) {
                    checklist = items.map(item => item.replace(/^[\s-]*/, '').trim()).filter(item => item.length > 0);
                }
            }
            
            // Auto-detect print type
            let printType = 'Custom';
            if (userMessage.toLowerCase().includes('t-shirt') || userMessage.toLowerCase().includes('tshirt')) {
                printType = 'T-Shirts';
            } else if (userMessage.toLowerCase().includes('business card')) {
                printType = 'Business Cards';
            } else if (userMessage.toLowerCase().includes('flyer')) {
                printType = 'Flyers';
            } else if (userMessage.toLowerCase().includes('banner')) {
                printType = 'Banners';
            } else if (userMessage.toLowerCase().includes('poster')) {
                printType = 'Posters';
            }
            
            // Update form fields immediately
            const printTypeSelect = document.querySelector('#printType');
            if (printTypeSelect) {
                printTypeSelect.value = printType;
            }
            
            if (qtyMatch) {
                const qtyField = document.querySelector('#quantity');
                if (qtyField) {
                    qtyField.value = qtyMatch[1];
                }
            }
            
            // Build specs with checklist
            let specs = title + '\n\n' + description + '\n\n**Action Items:**\n';
            checklist.forEach((item, index) => {
                specs += `${index + 1}. ${item}\n`;
            });
            
            // Update specs field
            const specField = document.querySelector('#specifications');
            if (specField) {
                specField.value = specs;
            }
            
        } catch (error) {
            console.error('Chatbot error:', error);
        } finally {
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="bi bi-send"></i>';
            input.focus();
        }
    } else {
        // Not an order - just add to specs
        const context = window.specChatContext;
        const specField = context.specField;
        if (specField.value) {
            specField.value += '\n' + userMessage;
        } else {
            specField.value = userMessage;
        }
    }
}

// Show employees modal
function showEmployeesModal() {
    let employeesHTML = employees.map(emp => `
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 50px; height: 50px; background-color: ${emp.color}; font-size: 24px;">
                        ${emp.avatar}
                    </div>
                    <div>
                        <h6 class="mb-0">${emp.name}</h6>
                        <small class="text-muted">${emp.role}</small>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
    
    const modalHTML = `
        <div class="modal fade" id="employeesModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title"><i class="bi bi-people me-2"></i>Print Shop Employees</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Team members available for task assignment</p>
                        ${employeesHTML}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    new bootstrap.Modal(document.querySelector('#employeesModal')).show();
    
    document.querySelector('#employeesModal').addEventListener('hidden.bs.modal', () => {
        document.querySelector('#employeesModal').remove();
    });
}

// Show order creation options modal
function showOrderOptionsModal() {
    const modalHTML = `
        <div class="modal fade" id="orderOptionsModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Select an Option to Create Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-grid gap-3">
                            <button class="btn btn-outline-primary btn-lg text-start" onclick="selectOrderMode('manual')">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-pencil-square fs-3 me-3"></i>
                                    <div>
                                        <div class="fw-bold">Create Order Manually</div>
                                        <small class="text-muted">Fill out the order form yourself</small>
                                    </div>
                                </div>
                            </button>

                            <button class="btn btn-outline-dark btn-lg text-start" onclick="selectOrderMode('quick')">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-terminal fs-3 me-3"></i>
                                    <div>
                                        <div class="fw-bold">Quick Command</div>
                                        <small class="text-muted">Type commands like: printing 50 mug</small>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    const modal = new bootstrap.Modal(document.querySelector('#orderOptionsModal'));
    modal.show();
    
    document.querySelector('#orderOptionsModal').addEventListener('hidden.bs.modal', () => {
        document.querySelector('#orderOptionsModal').remove();
    });
}

// Select order creation mode
function selectOrderMode(mode) {
    orderCreationMode = mode;
    const modal = bootstrap.Modal.getInstance(document.querySelector('#orderOptionsModal'));
    modal.hide();
    
    if (mode === 'manual') {
        showNewOrderModal();
    } else if (mode === 'template') {
        showTemplateSelectionModal();
    } else if (mode === 'ai') {
        showAIPromptModal();
    } else if (mode === 'quick') {
        showQuickCommandModal();
    }
}

function showQuickCommandModal() {
    const modalHTML = `
        <div class="modal fade" id="quickCommandModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title"><i class="bi bi-terminal me-2"></i>Quick Command</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Enter a short print command to auto-create a Trello task.</p>
                        <div class="mb-3">
                            <label for="quickCommandInput" class="form-label">Command</label>
                            <input class="form-control" id="quickCommandInput" placeholder="printing 50 mug" />
                        </div>
                        <div class="alert alert-secondary py-2 mb-0">
                            <small>
                                Supported format examples:<br>
                                • printing 50 mug<br>
                                • print 200 flyers rush<br>
                                • printing 100 business cards for Acme
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-dark" onclick="generateOrderFromQuickCommand()">
                            <i class="bi bi-lightning-charge me-2"></i>Create Task
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);
    const modal = new bootstrap.Modal(document.querySelector('#quickCommandModal'));
    modal.show();

    const input = document.querySelector('#quickCommandInput');
    if (input) {
        input.focus();
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                generateOrderFromQuickCommand();
            }
        });
    }

    document.querySelector('#quickCommandModal').addEventListener('hidden.bs.modal', () => {
        document.querySelector('#quickCommandModal').remove();
    });
}

function parseQuickPrintCommand(commandText) {
    if (!commandText || !commandText.trim()) {
        return null;
    }

    const original = commandText.trim();
    const lower = original.toLowerCase();

    const qtyMatch = lower.match(/\b(\d{1,6})\b/);
    const quantity = qtyMatch ? parseInt(qtyMatch[1], 10) : 1;

    let item = 'custom order';
    const itemMatch = lower.match(/(?:print(?:ing)?\s+\d+\s+)([a-z0-9\-\s]+?)(?:\s+for\s+|\s+rush\b|\s+urgent\b|\s+asap\b|$)/i);
    if (itemMatch && itemMatch[1]) {
        item = itemMatch[1].trim();
    } else {
        const fallback = lower.match(/\d+\s+([a-z0-9\-\s]+?)(?:\s+for\s+|\s+rush\b|\s+urgent\b|\s+asap\b|$)/i);
        if (fallback && fallback[1]) {
            item = fallback[1].trim();
        }
    }

    let customerName = 'Walk-in Customer';
    const customerMatch = original.match(/\bfor\s+(.+)$/i);
    if (customerMatch && customerMatch[1]) {
        customerName = customerMatch[1].trim();
    }

    let printType = 'Custom';
    if (/business\s*cards?/.test(lower)) printType = 'Business Cards';
    else if (/flyers?/.test(lower)) printType = 'Flyers';
    else if (/brochures?/.test(lower)) printType = 'Brochures';
    else if (/banners?/.test(lower)) printType = 'Banners';
    else if (/posters?/.test(lower)) printType = 'Posters';
    else if (/booklets?/.test(lower)) printType = 'Booklets';
    else if (/stickers?/.test(lower)) printType = 'Stickers';
    else if (/large\s*format/.test(lower)) printType = 'Large Format';

    let priority = 'Normal';
    if (/\b(express|same\s*day|today)\b/.test(lower)) priority = 'Express';
    else if (/\b(rush|urgent|asap)\b/.test(lower)) priority = 'Rush';

    const normalizedItem = item
        .split(/\s+/)
        .filter(Boolean)
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');

    const orderNumber = 'PO-' + Math.floor(1000 + Math.random() * 9000);
    const butlerTag = '#butler-print';

    return {
        orderNumber,
        customerName,
        quantity,
        item: normalizedItem || 'Custom Order',
        printType,
        priority,
        originalCommand: original,
        butlerTag
    };
}

async function generateOrderFromQuickCommand() {
    const input = document.querySelector('#quickCommandInput');
    const commandText = input ? input.value.trim() : '';
    const parsed = parseQuickPrintCommand(commandText);

    if (!parsed) {
        alert('Please enter a command, e.g. "printing 50 mug"');
        return;
    }

    const quickModal = bootstrap.Modal.getInstance(document.querySelector('#quickCommandModal'));
    if (quickModal) {
        quickModal.hide();
    }

    await showNewOrderModal();

    const orderNumberEl = document.querySelector('#orderNumber');
    const customerNameEl = document.querySelector('#customerName');
    const printTypeEl = document.querySelector('#printType');
    const quantityEl = document.querySelector('#quantity');
    const priorityEl = document.querySelector('#priority');
    const specsEl = document.querySelector('#specifications');
    const boardSelect = document.querySelector('#boardSelect');

    if (orderNumberEl) orderNumberEl.value = parsed.orderNumber;
    if (customerNameEl) customerNameEl.value = parsed.customerName;
    if (printTypeEl) printTypeEl.value = parsed.printType;
    if (quantityEl) quantityEl.value = parsed.quantity;
    if (priorityEl) priorityEl.value = parsed.priority;

    if (specsEl) {
        specsEl.value = `Quick command generated order\n\nCommand: ${parsed.originalCommand}\nItem: ${parsed.item}\nQuantity: ${parsed.quantity}\nAutomation Tag: ${parsed.butlerTag}\n\nUse Butler rule trigger on card name or description containing ${parsed.butlerTag}.`;
    }

    if (boardSelect && !boardSelect.value && boardSelect.options.length > 1) {
        boardSelect.value = boardSelect.options[1].value;
        await loadListsForBoard(boardSelect.value);
    }

    setDefaultListSelection();

    const stepsTab = document.querySelector('#stepsTab');
    if (stepsTab && typeof bootstrap !== 'undefined') {
        const tabInstance = bootstrap.Tab.getOrCreateInstance(stepsTab);
        tabInstance.show();
    }

    alert('Quick order draft generated. Please review and edit details, then click Create Order when ready.');
}

function setDefaultListSelection(force = false) {
    const listSelect = document.querySelector('#listSelect');
    if (!listSelect) {
        return;
    }

    if (!force && listSelect.value) {
        return;
    }

    const realOptions = Array.from(listSelect.options).filter(option => option.value && option.value.trim() !== '');
    if (realOptions.length === 0) {
        return;
    }

    // Prefer an early-stage list so newly created tasks do not start as completed.
    const priorityNames = ['to do', 'todo', 'backlog', 'pending', 'queue'];
    const preferred = realOptions.find(option => {
        const normalized = normalizeListName(option.textContent || option.label || '');
        return priorityNames.includes(normalized);
    });

    if (preferred) {
        listSelect.value = preferred.value;
        return;
    }

    const firstRealOption = realOptions[0];
    if (firstRealOption) {
        listSelect.value = firstRealOption.value;
    }
}

function resolveSafeCreationListId(selectedListId) {
    const listSelect = document.querySelector('#listSelect');
    if (!listSelect) {
        return selectedListId;
    }

    const realOptions = Array.from(listSelect.options).filter(option => option.value && option.value.trim() !== '');
    if (realOptions.length === 0) {
        return selectedListId;
    }

    const completedKeywords = ['completed', 'done', 'complete'];
    const todoKeywords = ['to do', 'todo', 'backlog', 'pending', 'queue'];

    const selectedOption = realOptions.find(option => option.value === selectedListId) || null;
    const selectedNormalized = normalizeListName(selectedOption ? (selectedOption.textContent || selectedOption.label || '') : '');
    const selectedIsCompleted = completedKeywords.some(keyword => {
        const normalizedKeyword = normalizeListName(keyword);
        return selectedNormalized === normalizedKeyword || selectedNormalized.includes(normalizedKeyword);
    });

    if (!selectedIsCompleted) {
        return selectedListId;
    }

    const todoOption = realOptions.find(option => {
        const normalized = normalizeListName(option.textContent || option.label || '');
        return todoKeywords.some(keyword => {
            const normalizedKeyword = normalizeListName(keyword);
            return normalized === normalizedKeyword || normalized.includes(normalizedKeyword);
        });
    });

    if (todoOption) {
        listSelect.value = todoOption.value;
        return todoOption.value;
    }

    return selectedListId;
}

// Show template selection modal
function showTemplateSelectionModal() {
    const templates = [
        { name: 'Business Cards', type: 'Business Cards', quantity: 500, priority: 'Normal' },
        { name: 'Flyers (Letter Size)', type: 'Flyers', quantity: 1000, priority: 'Normal' },
        { name: 'Banners (Large)', type: 'Banners', quantity: 5, priority: 'High' },
        { name: 'T-Shirts (Bulk)', type: 'T-Shirts', quantity: 100, priority: 'Normal' },
        { name: 'Posters (A3)', type: 'Posters', quantity: 50, priority: 'Normal' }
    ];
    
    let templatesHTML = templates.map(template => `
        <button class="btn btn-outline-secondary btn-lg w-100 text-start mb-2" 
                onclick='useTemplate(${JSON.stringify(template)})'>
            <i class="bi bi-file-earmark me-2"></i>${template.name}
        </button>
    `).join('');
    
    const modalHTML = `
        <div class="modal fade" id="templateModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-file-earmark-text me-2"></i>Select Order Template</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        ${templatesHTML}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    const modal = new bootstrap.Modal(document.querySelector('#templateModal'));
    modal.show();
    
    document.querySelector('#templateModal').addEventListener('hidden.bs.modal', () => {
        document.querySelector('#templateModal').remove();
    });
}

// Use template
function useTemplate(template) {
    const modal = bootstrap.Modal.getInstance(document.querySelector('#templateModal'));
    modal.hide();
    
    setTimeout(() => {
        showNewOrderModal();
        document.querySelector('#printType').value = template.type;
        document.querySelector('#quantity').value = template.quantity;
        document.querySelector('#priority').value = template.priority;
    }, 300);
}

// Show AI prompt modal
function showAIPromptModal() {
    const modalHTML = `
        <div class="modal fade" id="aiPromptModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title"><i class="bi bi-stars me-2"></i>Generate Order with AI</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Describe the print order in natural language and AI will create it for you.</p>
                        <div class="mb-3">
                            <label for="aiPromptInput" class="form-label">Describe the order:</label>
                            <textarea class="form-control" id="aiPromptInput" rows="4" 
                                      placeholder="Example: I need 500 business cards for John Smith, urgent priority, due next week"></textarea>
                        </div>
                        <button class="btn btn-info w-100" onclick="generateOrderWithAI()">
                            <i class="bi bi-magic me-2"></i>Generate Order
                        </button>
                        <div id="aiLoadingStatus" class="mt-3 text-center" style="display: none;">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                            <span>AI is analyzing your request...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    const modal = new bootstrap.Modal(document.querySelector('#aiPromptModal'));
    modal.show();
    
    document.querySelector('#aiPromptModal').addEventListener('hidden.bs.modal', () => {
        document.querySelector('#aiPromptModal').remove();
    });
}

// Generate order with AI
async function generateOrderWithAI() {
    const prompt = document.querySelector('#aiPromptInput').value.trim();
    
    if (!prompt) {
        alert('Please describe the order');
        return;
    }
    
    document.querySelector('#aiLoadingStatus').style.display = 'block';
    
    try {
        if (typeof puter === 'undefined') {
            throw new Error('Puter.js not loaded');
        }
        
        const aiPrompt = `Create a detailed print order task breakdown for: "${prompt}"

Generate:
1. A clear task title
2. A description with key details
3. A checklist of action items (4-6 steps) to complete this order

Format as:
TITLE: [task title]
DESCRIPTION: [description]
CHECKLIST:
- [step 1]
- [step 2]
- [step 3]
- [step 4]`;
        
        // Use Claude Sonnet 4.5 via Puter.js for better AI capabilities
        const response = await puter.ai.chat(aiPrompt, {
            model: 'claude-sonnet-4-5'
        });
        
        const responseText = response.message.content[0].text;
        
        // Parse AI response
        const titleMatch = responseText.match(/TITLE:\s*(.+)/i);
        const descMatch = responseText.match(/DESCRIPTION:\s*(.+?)(?=CHECKLIST:|$)/is);
        const checklistMatch = responseText.match(/CHECKLIST:\s*([\s\S]+)/i);
        
        const orderData = {
            orderNumber: 'PO-' + Math.floor(1000 + Math.random() + 9000),
            title: titleMatch ? titleMatch[1].trim() : `Print Order - ${prompt}`,
            description: descMatch ? descMatch[1].trim() : `Task: ${prompt}`,
            checklist: []
        };
        
        // Extract checklist items
        if (checklistMatch) {
            const items = checklistMatch[1].match(/^[\s-]*(.+)$/gm);
            if (items) {
                orderData.checklist = items.map(item => item.replace(/^[\s-]*/, '').trim()).filter(item => item.length > 0);
            }
        }
        
        // Extract basic info from prompt
        const qtyMatch = prompt.match(/(\d+)\s*(t-?shirts?|pieces?|units?|quantity|people)/i);
        if (qtyMatch) {
            orderData.quantity = parseInt(qtyMatch[1]);
            orderData.printType = 'T-Shirts';
        }
        
        const modal = bootstrap.Modal.getInstance(document.querySelector('#aiPromptModal'));
        modal.hide();
        
        setTimeout(() => {
            showNewOrderModal();
            
            document.querySelector('#orderNumber').value = orderData.orderNumber;
            document.querySelector('#customerName').value = '';
            
            // Set Print Type dropdown
            const printTypeSelect = document.querySelector('#printType');
            printTypeSelect.value = 'T-Shirts';
            printTypeSelect.textContent = 'T-Shirts';
            
            document.querySelector('#quantity').value = orderData.quantity || 1;
            document.querySelector('#priority').value = 'Normal';
            
            // Create detailed specifications with full checklist
            let specs = `Order and Manage Printing of ${orderData.quantity || 1} T-Shirts\n\n`;
            specs += `Plan the order to print ${orderData.quantity || 1} t-shirts with specific designs or logos.\nContact the printing vendor for quotes and available options.\n\n`;
            specs += 'Action Items:\n';
            specs += '• Request quotes from printing vendors\n';
            specs += '• Select t-shirt designs and specifications\n';
            specs += '• Confirm order quantities and sizes\n';
            specs += '• Choose fabric type and colors\n';
            specs += '• Approve design mockups\n';
            specs += '• Place the order with the vendor\n';
            specs += '• Schedule delivery or pickup';
            
            document.querySelector('#specifications').value = specs;
        }, 300);
    } catch (error) {
        console.error('AI generation error:', error);
        
        // Fallback: Create structured task manually
        const qtyMatch = prompt.match(/(\d+)\s*(t-?shirts?|pieces?|units?|quantity|people)/i);
        const quantity = qtyMatch ? parseInt(qtyMatch[1]) : 1;
        
        let printType = 'Other';
        if (prompt.toLowerCase().includes('t-shirt') || prompt.toLowerCase().includes('tshirt')) {
            printType = 'T-Shirts';
        } else if (prompt.toLowerCase().includes('business card')) {
            printType = 'Business Cards';
        } else if (prompt.toLowerCase().includes('flyer')) {
            printType = 'Flyers';
        } else if (prompt.toLowerCase().includes('banner')) {
            printType = 'Banners';
        }
        
        const modal = bootstrap.Modal.getInstance(document.querySelector('#aiPromptModal'));
        modal.hide();
        
        document.querySelector('#aiLoadingStatus').style.display = 'none';
        
        // Generate checklist based on print type
        let checklist = [];
        if (printType === 'T-Shirts') {
            checklist = [
                'Request quotes from printing vendors',
                'Select t-shirt designs and specifications',
                'Confirm order quantities and sizes',
                'Choose fabric type and colors',
                'Approve design mockups',
                'Place the order with the vendor',
                'Schedule delivery or pickup'
            ];
        } else if (printType === 'Business Cards') {
            checklist = [
                'Design business card layout',
                'Get client approval on design',
                'Select paper stock and finish',
                'Confirm quantity and dimensions',
                'Submit print order',
                'Quality check samples'
            ];
        } else {
            checklist = [
                'Define project requirements',
                'Get quotes from vendors',
                'Approve specifications',
                'Place the order',
                'Track production status',
                'Schedule delivery'
            ];
        }
        
        setTimeout(() => {
            showNewOrderModal();
            document.querySelector('#orderNumber').value = 'PO-' + Math.floor(1000 + Math.random() * 9000);
            
            // Set Print Type dropdown
            const printTypeSelect = document.querySelector('#printType');
            printTypeSelect.value = printType;
            printTypeSelect.textContent = printType;
            
            document.querySelector('#quantity').value = quantity;
            document.querySelector('#priority').value = 'Normal';
            
            let specs = `Order and Manage Printing of ${quantity} ${printType}\n\n`;
            specs += `Plan the order to print ${quantity} ${printType.toLowerCase()} with specific designs or requirements.\n\n`;
            specs += 'Action Items:';
            checklist.forEach((item, index) => {
                specs += `\n${index + 1}. ${item}`;
            });
            
            document.querySelector('#specifications').value = specs;
        }, 300);
    }
}

// Show new order modal
async function showNewOrderModal() {
    // Load boards into dropdown
    try {
        const boards = await trelloAPI('/members/me/boards');
        allBoards = boards || [];
        
        const boardSelect = document.querySelector('#boardSelect');
        if (!boardSelect) {
            console.error('boardSelect not found');
            return;
        }
        
        boardSelect.innerHTML = '<option value="">Select a board...</option>';
        
        if (allBoards.length > 0) {
            allBoards.forEach(board => {
                boardSelect.innerHTML += `<option value="${board.id}">${board.name}</option>`;
            });
            
            // Select current board if available
            if (currentBoardId) {
                boardSelect.value = currentBoardId;
                await loadListsForBoard(currentBoardId);
                setDefaultListSelection(true);
            } else if (allBoards.length > 0) {
                boardSelect.value = allBoards[0].id;
                await loadListsForBoard(allBoards[0].id);
                setDefaultListSelection(true);
            }
        }
        
        // Set up board change event listener
        boardSelect.onchange = async (e) => {
            const boardId = e.target.value;
            if (boardId) {
                await loadListsForBoard(boardId);
            } else {
                const listSelect = document.querySelector('#listSelect');
                if (listSelect) {
                    listSelect.innerHTML = '<option value="">Select a board first</option>';
                }
            }
        };
        
        // Set default due date to today
        const today = new Date().toISOString().split('T')[0];
        const dueDate = document.querySelector('#dueDate');
        if (dueDate) {
            dueDate.value = today;
        }
        
        // Load step assignments from localStorage
        loadStepAssignments();
        
        const newOrderModal = document.querySelector('#newOrderModal');
        if (newOrderModal) {
            new bootstrap.Modal(newOrderModal).show();
        }
    } catch (error) {
        console.error('Error in showNewOrderModal:', error);
        alert('Error loading boards: ' + error.message);
    }
}

// Load lists when board is selected - REMOVED OLD EVENT LISTENER

async function loadListsForBoard(boardId) {
    try {
        const lists = await trelloAPI(`/boards/${boardId}/lists`);
        const listSelect = document.querySelector('#listSelect');
        
        if (!listSelect) {
            console.error('listSelect not found');
            return;
        }
        
        listSelect.innerHTML = '<option value="">Select status...</option>';
        
        if (lists && lists.length > 0) {
            lists.forEach(list => {
                listSelect.innerHTML += `<option value="${list.id}">${list.name}</option>`;
            });
        } else {
            // Create default lists if none exist
            const defaultLists = ['To Do', 'In Progress', 'Printing', 'Quality Check', 'Ready for Pickup', 'Completed'];
            for (const listName of defaultLists) {
                const newList = await trelloAPI(`/lists`, 'POST', {
                    name: listName,
                    idBoard: boardId
                });
                if (newList) {
                    listSelect.innerHTML += `<option value="${newList.id}">${newList.name}</option>`;
                }
            }
        }

        setDefaultListSelection(true);
    } catch (error) {
        console.error('Error loading lists:', error);
    }
}

// Create new print order
async function createNewOrder() {
    const button = event?.target?.closest('button') || document.querySelector('#newOrderModal .modal-footer .btn-success');
    if (!button) {
        console.error('Create button not found');
        return;
    }
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Creating...';
    
    try {
        const form = document.querySelector('#newOrderForm');
        const orderNumber = document.querySelector('#orderNumber').value;
        const customerName = document.querySelector('#customerName').value;
        const printType = document.querySelector('#printType').value;
        const quantity = document.querySelector('#quantity').value;
        const dueDate = document.querySelector('#dueDate').value;
        const priority = document.querySelector('#priority')?.value || 'Normal';
        const listId = document.querySelector('#listSelect').value;
        const safeListId = resolveSafeCreationListId(listId);
        const specifications = document.querySelector('#specifications')?.value || '';
        const assignedEmployeeIds = document.querySelector('#assignedEmployees')?.value || '';
        
        // Validate required fields
        if (!orderNumber) {
            alert('Order Number is required');
            button.disabled = false;
            button.innerHTML = originalText;
            return;
        }
        if (!customerName) {
            alert('Customer Name is required');
            button.disabled = false;
            button.innerHTML = originalText;
            return;
        }
        if (!printType) {
            alert('Print Type is required');
            button.disabled = false;
            button.innerHTML = originalText;
            return;
        }
        if (!quantity) {
            alert('Quantity is required');
            button.disabled = false;
            button.innerHTML = originalText;
            return;
        }
        if (!dueDate) {
            alert('Due Date is required');
            button.disabled = false;
            button.innerHTML = originalText;
            return;
        }
        if (!listId) {
            alert('Status is required');
            button.disabled = false;
            button.innerHTML = originalText;
            return;
        }
        
        // Get assigned employees info
        let employeeInfo = '';
    if (assignedEmployeeIds) {
        const assignedIds = assignedEmployeeIds.split(',').filter(id => id);
        const assignedEmps = employees.filter(e => assignedIds.includes(String(e.id)));
        
        if (assignedEmps.length > 0) {
            employeeInfo = '\n👥 **Team Assigned:**';
            assignedEmps.forEach(emp => {
                employeeInfo += `\n  ${emp.avatar} ${emp.name} (${emp.role})`;
            });
        }
    }
    
    // Get progression steps
    const steps = document.querySelectorAll('.checklist-item');
    let stepsInfo = '';
    const stepAssignments = JSON.parse(localStorage.getItem('stepAssignments') || '{}');
    
    console.log('Found steps:', steps.length); // DEBUG
    
    if (steps.length > 0) {
        stepsInfo = '\n\n**Progression Steps:**\n';
        steps.forEach((step, index) => {
            const checkbox = step.querySelector('input[type="checkbox"]');
            const titleElement = step.querySelector('.step-title');
            const assigneeBadge = step.querySelector('[id$="-assignees"]');
            
            if (checkbox && titleElement) {
                const stepId = checkbox.id;
                const isChecked = checkbox.checked ? '✅' : '⭕';
                const title = titleElement.textContent.trim() || `Step ${index + 1}`;
                const assignees = assigneeBadge ? assigneeBadge.textContent.trim() : 'Unassigned';
                
                stepsInfo += `${isChecked} ${title} → ${assignees}\n`;
                console.log(`Step ${index + 1}: ${title} → ${assignees}`); // DEBUG
            }
        });
    }
    
    console.log('Steps Info:', stepsInfo); // DEBUG
    
    // Create card name and description
    const hasButlerPrintTag = specifications.includes('#butler-print');
    const automationSuffix = hasButlerPrintTag ? ' #butler-print' : '';
    const cardName = `${orderNumber} - ${customerName} (${printType})${automationSuffix}`;
    const cardDescription = `**Print Order Details**

📋 **Order Number:** ${orderNumber}
👤 **Customer:** ${customerName}
🖨️ **Print Type:** ${printType}
📦 **Quantity:** ${quantity}
⚡ **Priority:** ${priority}
📅 **Due Date:** ${dueDate}${employeeInfo}

**Specifications:**
${specifications || 'No additional specifications provided.'}

---
*Created: ${new Date().toLocaleString()}*`;
    
    // Create the card
    const newCard = await trelloAPI('/cards', 'POST', {
            name: cardName,
            desc: cardDescription,
            idList: safeListId,
            due: new Date(dueDate).toISOString(),
            pos: 'top'
        });
        
        if (newCard) {
            // Add checklist to the card
            if (steps.length > 0) {
                console.log('Creating checklist with', steps.length, 'steps');
                
                const checklist = await trelloAPI(`/cards/${newCard.id}/checklists`, 'POST', {
                    name: 'Progression Steps'
                });
                
                console.log('Checklist created:', checklist);
                
                if (checklist) {
                    // Add each step as a checklist item sequentially
                    for (let i = 0; i < steps.length; i++) {
                        const step = steps[i];
                        const checkbox = step.querySelector('input[type="checkbox"]');
                        const titleElement = step.querySelector('.step-title, h6.step-title');
                        const assigneeBadge = step.querySelector('[id$="-assignees"]');
                        
                        // Get title from various sources
                        let title = '';
                        if (titleElement) {
                            if (titleElement.tagName === 'INPUT') {
                                title = titleElement.value || titleElement.placeholder;
                            } else {
                                // Remove icon and get just the text
                                const clone = titleElement.cloneNode(true);
                                const icons = clone.querySelectorAll('i');
                                icons.forEach(icon => icon.remove());
                                title = clone.textContent.trim();
                            }
                        }
                        
                        title = title || `Step ${i + 1}`;
                        const assignees = assigneeBadge ? assigneeBadge.textContent.trim() : 'Unassigned';
                        const itemName = `${title} → ${assignees}`;
                        
                        console.log(`Adding checklist item ${i + 1}:`, itemName);
                        
                        await trelloAPI(`/checklists/${checklist.id}/checkItems`, 'POST', {
                            name: itemName,
                            pos: 'bottom'
                        });
                    }
                    console.log('All checklist items added');
                }
            } else {
                console.log('No steps found to add to checklist');
            }
            
            alert('✅ Order created successfully!\n\nOrder: ' + orderNumber);
            
            // Close modal properly
            const modal = bootstrap.Modal.getInstance(document.querySelector('#newOrderModal'));
            if (modal) {
                modal.hide();
            }
            
            // Remove backdrop manually to fix grey screen issue
            setTimeout(() => {
                document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }, 300);
            
            // Clear form and reset employee assignment
            const form = document.querySelector('#newOrderForm');
            if (form) form.reset();
            const assignedEmpsElement = document.querySelector('#assignedEmployees');
            if (assignedEmpsElement) assignedEmpsElement.value = '';
            const assignedCountElement = document.querySelector('#assignedEmployeeCount');
            if (assignedCountElement) assignedCountElement.textContent = '0 Assigned';
            
            // Reload current board
            if (currentBoardId) {
                const boardName = allBoards.find(b => b.id === currentBoardId)?.name || 'Board';
                selectBoard(currentBoardId, boardName);
            }
        } else {
            alert('❌ Failed to create order. Please try again.');
        }
    } catch (error) {
        alert('❌ Error creating order: ' + error.message);
    } finally {
        button.disabled = false;
        button.innerHTML = originalText;
    }
}

// Show card details modal
async function showCardDetails(cardId) {
    try {
        const existingModal = document.querySelector('#cardDetailsModal');
        if (existingModal) {
            const existingInstance = bootstrap.Modal.getInstance(existingModal);
            if (existingInstance) {
                existingInstance.dispose();
            }
            existingModal.remove();
        }
        cleanupModalArtifacts();

        const card = await trelloAPI(`/cards/${cardId}`);
        
        if (!card) {
            alert('Failed to load card details');
            return;
        }
        
        // Parse description to extract order details if it's a print order
        const desc = card.desc || '';
        
        // Format due date
        const dueDate = card.due ? new Date(card.due).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }) : 'Not set';
        
        const dueDateClass = card.due && new Date(card.due) < new Date() ? 'text-danger' : '';
        
        // Get list name
        const list = await trelloAPI(`/lists/${card.idList}`);
        const listName = list ? list.name : 'Unknown';
        
        // Fetch checklists
        console.log('Fetching checklists for card:', cardId);
        const checklists = await trelloAPI(`/cards/${cardId}/checklists`);
        console.log('Checklists fetched:', checklists);
        
        // Build checklist HTML
        let checklistHTML = '';
        if (checklists && checklists.length > 0) {
            checklistHTML = '<div class="mb-3" id="checklistsContainer"><h6><strong>📋 Checklists:</strong></h6>';
            for (const checklist of checklists) {
                const totalItems = checklist.checkItems.length;
                const completedItems = checklist.checkItems.filter(item => item.state === 'complete').length;
                const progress = totalItems > 0 ? Math.round((completedItems / totalItems) * 100) : 0;
                
                checklistHTML += `
                    <div class="card mb-2 checklist-card" data-checklist-id="${checklist.id}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">${escapeHtml(checklist.name)}</h6>
                                <span class="badge bg-info checklist-progress-badge">${completedItems}/${totalItems}</span>
                            </div>
                            <div class="progress mb-2" style="height: 5px;">
                                <div class="progress-bar checklist-progress-bar" role="progressbar" style="width: ${progress}%"></div>
                            </div>
                            <div class="checklist-items">`;
                
                for (const item of checklist.checkItems) {
                    const checked = item.state === 'complete' ? 'checked' : '';
                    const textDecoration = item.state === 'complete' ? 'text-decoration-line-through text-muted' : '';
                    const canEditChecklistItem = canCurrentEmployeeEditChecklistItem(item.name);
                    const disabled = canEditChecklistItem ? '' : 'disabled';
                    const permissionHint = canEditChecklistItem ? '' : 'title="You can only update steps assigned to you"';
                    const checkedByInfo = getChecklistCheckedByInfo(card.id, item.id);
                    const checkedByText = formatCheckedByText(checkedByInfo);
                    const checkedByHTML = (item.state === 'complete' && checkedByText)
                        ? escapeHtml(checkedByText)
                        : '';
                    checklistHTML += `
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" ${checked} ${disabled}
                                   data-card-id="${card.id}" data-item-id="${item.id}" data-can-edit="${canEditChecklistItem}" ${permissionHint}>
                            <label class="form-check-label ${textDecoration}">
                                ${escapeHtml(item.name)}
                            </label>
                            <small class="text-muted ms-2 checklist-checked-by">${checkedByHTML}</small>
                        </div>`;
                }
                
                checklistHTML += `
                            </div>
                        </div>
                    </div>`;
            }
            checklistHTML += '</div>';
        }
        
        const actionButtonsHTML = employeeAccessMode
            ? ''
            : `
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary" onclick="editCardDetails('${card.id}')">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </button>
                                <button class="btn btn-outline-danger ms-auto" onclick="deleteCardFromModal('${card.id}', '${escapeHtml(card.name)}')">
                                    <i class="bi bi-trash me-1"></i>Delete
                                </button>
                            </div>
                        `;

        const modalHTML = `
            <div class="modal fade" id="cardDetailsModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-dark text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-card-text me-2"></i>${escapeHtml(card.name)}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>📋 List:</strong> <span class="badge bg-secondary">${escapeHtml(listName)}</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>📅 Due Date:</strong> <span class="${dueDateClass}">${dueDate}</span></p>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <h6><strong>Description:</strong></h6>
                                <div class="card bg-light">
                                    <div class="card-body" style="white-space: pre-wrap;">${escapeHtml(desc) || '<em class="text-muted">No description</em>'}</div>
                                </div>
                            </div>
                            
                            ${checklistHTML}
                            
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>Created: ${new Date(card.dateLastActivity).toLocaleString()}
                                </small>
                            </div>

                            ${actionButtonsHTML}
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        const modalElement = document.querySelector('#cardDetailsModal');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        
        // Add event listener for checkbox changes
        modalElement.addEventListener('change', async (e) => {
            if (e.target.type === 'checkbox' && e.target.classList.contains('form-check-input')) {
                if (e.target.dataset.canEdit !== 'true') {
                    e.target.checked = !e.target.checked;
                    alert('You can only check steps assigned to you.');
                    return;
                }

                const cardId = e.target.dataset.cardId;
                const itemId = e.target.dataset.itemId;
                const isComplete = e.target.checked;
                await updateChecklistItem(cardId, itemId, isComplete);
            }
        });
        
        modalElement.addEventListener('hidden.bs.modal', () => {
            modal.dispose();
            modalElement.remove();
            cleanupModalArtifacts();
        });
    } catch (error) {
        console.error('Error loading card details:', error);
        alert('Error loading card details: ' + error.message);
    }
}

function cleanupModalArtifacts() {
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('padding-right');
    document.body.style.removeProperty('overflow');
}

// Edit card details
async function editCardDetails(cardId) {
    const detailsModalElement = document.querySelector('#cardDetailsModal');
    const detailsModal = detailsModalElement ? bootstrap.Modal.getInstance(detailsModalElement) : null;

    if (detailsModal) {
        detailsModal.hide();
    } else {
        cleanupModalArtifacts();
    }

    try {
        const card = await trelloAPI(`/cards/${cardId}`);
        if (!card) {
            alert('Failed to load card details for editing.');
            return;
        }

        const checklists = await trelloAPI(`/cards/${cardId}/checklists`) || [];

        let checklistEditorHtml = '';
        if (checklists.length === 0) {
            checklistEditorHtml = '<p class="text-muted small mb-0">No checklist tasks found for this card.</p>';
        } else {
            checklistEditorHtml = checklists.map(checklist => {
                const itemsHtml = (checklist.checkItems || []).map(item => {
                    const checked = item.state === 'complete' ? 'checked' : '';
                    return `
                        <div class="row g-2 align-items-center mb-2">
                            <div class="col-auto">
                                <input
                                    type="checkbox"
                                    class="form-check-input edit-checkitem-state"
                                    data-item-id="${item.id}"
                                    data-original-state="${item.state || 'incomplete'}"
                                    ${checked}
                                >
                            </div>
                            <div class="col">
                                <input
                                    type="text"
                                    class="form-control form-control-sm edit-checkitem-name"
                                    data-item-id="${item.id}"
                                    data-original-name="${escapeHtml(item.name || '')}"
                                    value="${escapeHtml(item.name || '')}"
                                    placeholder="Checklist task"
                                >
                            </div>
                        </div>
                    `;
                }).join('');

                return `
                    <div class="border rounded p-2 mb-3">
                        <div class="fw-semibold mb-2">${escapeHtml(checklist.name || 'Checklist')}</div>
                        ${itemsHtml || '<p class="text-muted small mb-0">No items in this checklist.</p>'}
                    </div>
                `;
            }).join('');
        }

        const editModalHtml = `
            <div class="modal fade" id="editCardModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Card</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                            <div class="mb-3">
                                <label for="editCardName" class="form-label">Task Name</label>
                                <input type="text" class="form-control" id="editCardName" value="${escapeHtml(card.name || '')}">
                            </div>

                            <div class="mb-3">
                                <label for="editCardDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="editCardDescription" rows="5">${escapeHtml(card.desc || '')}</textarea>
                            </div>

                            <div>
                                <label class="form-label">Checklist Tasks</label>
                                ${checklistEditorHtml}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="saveCardEditBtn" onclick="saveEditedCardDetails('${cardId}')">
                                <i class="bi bi-save me-1"></i>Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const existingEditModal = document.querySelector('#editCardModal');
        if (existingEditModal) {
            const existingInstance = bootstrap.Modal.getInstance(existingEditModal);
            if (existingInstance) {
                existingInstance.dispose();
            }
            existingEditModal.remove();
        }

        document.body.insertAdjacentHTML('beforeend', editModalHtml);
        const editModalElement = document.querySelector('#editCardModal');
        const editModal = new bootstrap.Modal(editModalElement);
        editModal.show();

        editModalElement.addEventListener('hidden.bs.modal', () => {
            editModal.dispose();
            editModalElement.remove();
            cleanupModalArtifacts();
        });
    } catch (error) {
        console.error('Error opening edit modal:', error);
        alert('Error opening editor: ' + error.message);
    }
}

async function saveEditedCardDetails(cardId) {
    const saveBtn = document.querySelector('#saveCardEditBtn');
    const originalBtnHtml = saveBtn ? saveBtn.innerHTML : '';

    const nameInput = document.querySelector('#editCardName');
    const descriptionInput = document.querySelector('#editCardDescription');

    const updatedName = (nameInput?.value || '').trim();
    const updatedDescription = descriptionInput?.value || '';

    if (!updatedName) {
        alert('Task name is required.');
        return;
    }

    if (saveBtn) {
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Saving...';
    }

    try {
        const cardUpdated = await trelloAPI(`/cards/${cardId}`, 'PUT', {
            name: updatedName,
            desc: updatedDescription
        });

        if (!cardUpdated) {
            throw new Error('Failed to update card details.');
        }

        const taskInputs = document.querySelectorAll('.edit-checkitem-name');

        for (const taskInput of taskInputs) {
            const itemId = taskInput.dataset.itemId;
            const originalName = taskInput.dataset.originalName || '';
            const newName = (taskInput.value || '').trim();
            if (!itemId || !newName) {
                continue;
            }

            const stateCheckbox = document.querySelector(`.edit-checkitem-state[data-item-id="${itemId}"]`);
            const originalState = stateCheckbox?.dataset.originalState || 'incomplete';
            const nextState = stateCheckbox?.checked ? 'complete' : 'incomplete';

            const nameChanged = newName !== originalName;
            const stateChanged = nextState !== originalState;

            if (!nameChanged && !stateChanged) {
                continue;
            }

            const updatedItem = await trelloAPI(`/cards/${cardId}/checkItem/${itemId}`, 'PUT', {
                name: newName,
                state: nextState
            });

            if (!updatedItem) {
                throw new Error('Failed to update one or more checklist tasks.');
            }

            if (stateChanged) {
                if (nextState === 'complete') {
                    setChecklistCheckedBy(cardId, itemId, getCurrentOperatorName());
                } else {
                    clearChecklistCheckedBy(cardId, itemId);
                }
            }
        }

        const editModalElement = document.querySelector('#editCardModal');
        const editModal = editModalElement ? bootstrap.Modal.getInstance(editModalElement) : null;
        if (editModal) {
            editModal.hide();
        }

        alert('✅ Card updated successfully!');

        if (currentBoardId) {
            const boardName = allBoards.find(b => b.id === currentBoardId)?.name || 'Board';
            await selectBoard(currentBoardId, boardName);
        }
    } catch (error) {
        console.error('Error saving card edit:', error);
        alert('Failed to save changes: ' + error.message);
    } finally {
        if (saveBtn) {
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalBtnHtml;
        }
    }
}

// Delete card from modal
function deleteCardFromModal(cardId, cardName) {
    const modalElement = document.querySelector('#cardDetailsModal');
    const modal = modalElement ? bootstrap.Modal.getInstance(modalElement) : null;
    if (modal) {
        modal.hide();
    } else {
        cleanupModalArtifacts();
    }
    deleteCard(cardId, cardName);
}

function normalizeListName(name) {
    return (name || '').trim().toLowerCase();
}

function findFirstListByPriority(boardLists, namesInPriority) {
    for (const name of namesInPriority) {
        const matched = boardLists.find(list => normalizeListName(list.name) === name);
        if (matched) {
            return matched;
        }
    }
    return null;
}

async function autoMoveCardByChecklistProgress(cardId) {
    try {
        const card = await trelloAPI(`/cards/${cardId}`);
        if (!card) return;

        const boardLists = await trelloAPI(`/boards/${card.idBoard}/lists`);
        if (!boardLists || boardLists.length === 0) return;

        const checklists = await trelloAPI(`/cards/${cardId}/checklists`);
        if (!checklists) return;

        const allItems = checklists.flatMap(checklist => checklist.checkItems || []);
        const totalItems = allItems.length;
        const completedItems = allItems.filter(item => item.state === 'complete').length;

        if (totalItems === 0) return;

        let targetList = null;
        if (completedItems === 0) {
            targetList = findFirstListByPriority(boardLists, ['to do', 'todo', 'backlog']);
        } else if (completedItems === totalItems) {
            targetList = findFirstListByPriority(boardLists, ['completed', 'done', 'complete']);
        } else {
            targetList = findFirstListByPriority(boardLists, ['process', 'in progress', 'processing', 'printing', 'quality check', 'ready for pickup']);
        }

        if (!targetList) return;
        if (card.idList === targetList.id) return;

        await trelloAPI(`/cards/${cardId}`, 'PUT', {
            idList: targetList.id
        });

        if (currentBoardId) {
            const boardName = allBoards.find(b => b.id === currentBoardId)?.name || 'Board';
            selectBoard(currentBoardId, boardName);
        }
    } catch (error) {
        console.error('Auto-move by checklist progress failed:', error);
    }
}

// Update checklist item state
async function updateChecklistItem(cardId, checkItemId, isComplete) {
    const checkbox = document.querySelector(`input[data-item-id="${checkItemId}"]`);
    const label = checkbox?.nextElementSibling;
    const checklistCard = checkbox?.closest('.checklist-card');
    const checkedByElement = checkbox?.closest('.form-check')?.querySelector('.checklist-checked-by');
    const previousCheckedByText = checkedByElement ? checkedByElement.textContent : '';

    const refreshChecklistProgress = () => {
        if (!checklistCard) return;
        const checklistCheckboxes = checklistCard.querySelectorAll('input.form-check-input[type="checkbox"]');
        const total = checklistCheckboxes.length;
        const current = Array.from(checklistCheckboxes).filter(input => input.checked).length;
        const badge = checklistCard.querySelector('.checklist-progress-badge');
        const progressBar = checklistCard.querySelector('.checklist-progress-bar');

        if (badge) {
            badge.textContent = `${current}/${total}`;
        }
        if (progressBar) {
            const newProgress = total > 0 ? Math.round((current / total) * 100) : 0;
            progressBar.style.width = `${newProgress}%`;
        }
    };

    if (employeeAccessMode && checkbox && checkbox.dataset.canEdit !== 'true') {
        checkbox.checked = !isComplete;
        alert('You can only check steps assigned to you.');
        return;
    }
    
    try {
        // Optimistic update - update UI immediately
        if (label) {
            if (isComplete) {
                label.classList.add('text-decoration-line-through', 'text-muted');
            } else {
                label.classList.remove('text-decoration-line-through', 'text-muted');
            }
        }

        if (checkedByElement) {
            checkedByElement.textContent = isComplete
                ? formatCheckedByText({ name: getCurrentOperatorName(), checkedAt: new Date().toISOString() })
                : '';
        }
        
        // Update progress immediately from actual checkbox states
        refreshChecklistProgress();
        
        // Update Trello in the background
        const state = isComplete ? 'complete' : 'incomplete';
        await trelloAPI(`/cards/${cardId}/checkItem/${checkItemId}`, 'PUT', { state });
        console.log('Checklist item updated:', checkItemId, state);

        if (isComplete) {
            setChecklistCheckedBy(cardId, checkItemId, getCurrentOperatorName());
        } else {
            clearChecklistCheckedBy(cardId, checkItemId);
        }

        await autoMoveCardByChecklistProgress(cardId);
        
    } catch (error) {
        console.error('Error updating checklist item:', error);
        
        // Revert optimistic update on error
        if (checkbox) checkbox.checked = !isComplete;
        if (label) {
            if (isComplete) {
                label.classList.remove('text-decoration-line-through', 'text-muted');
            } else {
                label.classList.add('text-decoration-line-through', 'text-muted');
            }
        }

        if (checkedByElement) {
            checkedByElement.textContent = previousCheckedByText;
        }
        
        // Revert progress from actual checkbox states
        refreshChecklistProgress();
        
        alert('Failed to update checklist item: ' + error.message);
    }
}

// Show settings modal
function showSettings() {
    const hasToken = localStorage.getItem('trelloToken');
    const modalHTML = `
        <div class="modal fade" id="settingsModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Settings</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Trello Token</h6>
                        <p class="text-muted small">Your token is stored securely in your browser.</p>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" id="settingsTokenInput" 
                                   placeholder="Paste your Trello token here">
                            <button class="btn btn-outline-secondary" type="button" onclick="toggleTokenVisibility()">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <small class="d-block mb-3">
                            Get your token from: <a href="https://trello.com/app-keys" target="_blank">https://trello.com/app-keys</a>
                        </small>
                        <button class="btn btn-sm btn-success w-100 mb-2" onclick="testAndUpdateToken()">
                            <i class="bi bi-check-circle me-2"></i> Test & Save Token
                        </button>
                        ${hasToken ? '<button class="btn btn-sm btn-danger w-100" onclick="clearToken()">Clear Token</button>' : ''}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    new bootstrap.Modal(document.querySelector('#settingsModal')).show();
    
    document.querySelector('#settingsModal').addEventListener('hidden.bs.modal', () => {
        document.querySelector('#settingsModal').remove();
    });
}

function toggleTokenVisibility() {
    const input = document.querySelector('#settingsTokenInput');
    input.type = input.type === 'password' ? 'text' : 'password';
}

async function testAndUpdateToken() {
    const token = document.querySelector('#settingsTokenInput').value.trim();
    
    if (!token) {
        alert('Please enter a token');
        return;
    }
    
    console.log('Testing token: ' + token.substring(0, 10) + '...');
    
    try {
        const url = `${TRELLO_API_URL}/members/me?key=${TRELLO_API_KEY}&token=${token}`;
        console.log('Test URL:', url);
        
        const response = await fetch(url);
        console.log('Test response status:', response.status);
        
        if (response.ok) {
            const data = await response.json();
            localStorage.setItem('trelloToken', token);
            TRELLO_TOKEN = token;
            
            alert(`✅ Token is valid!\n\nLogged in as: ${data.fullName || data.username}`);
            const modal = bootstrap.Modal.getInstance(document.querySelector('#settingsModal'));
            modal.hide();
            document.querySelector('#settingsModal').remove();
            
            // Reload data
            loadWorkspaces();
        } else {
            const errorText = await response.text();
            console.error('Token test failed:', errorText);
            alert(`❌ Token test failed (Status: ${response.status})\n\nPlease make sure:\n1. You have a valid Trello token\n2. You copied it correctly from https://trello.com/app-keys`);
        }
    } catch (error) {
        console.error('Error testing token:', error);
        alert(`❌ Error: ${error.message}\n\nCheck browser console (F12) for more details`);
    }
}

function clearToken() {
    if (confirm('Are you sure you want to clear your token? You will need to enter it again.')) {
        localStorage.removeItem('trelloToken');
        TRELLO_TOKEN = null;
        const modal = bootstrap.Modal.getInstance(document.querySelector('#settingsModal'));
        modal.hide();
        location.reload();
    }
}

// Show create board modal
function showCreateBoardModal() {
    new bootstrap.Modal(document.querySelector('#createBoardModal')).show();
}

// Create new board
async function createNewBoard() {
    const form = document.querySelector('#createBoardForm');
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const boardName = document.querySelector('#boardName').value.trim();
    const boardDesc = document.querySelector('#boardDesc').value.trim();
    const createDefaultLists = document.querySelector('#createDefaultLists').checked;
    
    try {
        console.log('Creating board:', boardName);
        
        // Create the board
        const newBoard = await trelloAPI('/boards', 'POST', {
            name: boardName,
            desc: boardDesc,
            defaultLists: false
        });
        
        if (!newBoard) {
            alert('❌ Failed to create board. Check console for errors.');
            return;
        }
        
        console.log('Board created:', newBoard);
        
        // Create default lists if requested
        if (createDefaultLists) {
            console.log('Creating default lists...');
            
            await trelloAPI('/lists', 'POST', {
                name: 'To Do',
                idBoard: newBoard.id,
                pos: 'top'
            });
            
            await trelloAPI('/lists', 'POST', {
                name: 'In Progress',
                idBoard: newBoard.id,
                pos: 'bottom'
            });
            
            await trelloAPI('/lists', 'POST', {
                name: 'Done',
                idBoard: newBoard.id,
                pos: 'bottom'
            });
        }
        
        alert(`✅ Board "${boardName}" created successfully!`);
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.querySelector('#createBoardModal'));
        modal.hide();
        
        // Clear form
        form.reset();
        
        // Wait a moment for Trello to process, then reload
        setTimeout(() => {
            console.log('Reloading boards after creation...');
            location.reload(); // Force full page reload
        }, 500);
        
    } catch (error) {
        console.error('Error creating board:', error);
        alert('❌ Error creating board: ' + error.message);
    }
}
// Show order progression section (always visible in form)
function showOrderProgressSection() {
    // Progression section is always visible now, no need to toggle
}

// Toggle progression tab
function toggleProgressionTab() {
    const tabContent = document.querySelector('#progressionTabContent');
    const toggleIcon = document.querySelector('#progressToggleIcon');
    
    if (tabContent && toggleIcon) {
        const isVisible = tabContent.style.display !== 'none';
        tabContent.style.display = isVisible ? 'none' : 'block';
        toggleIcon.style.transform = isVisible ? 'rotate(0deg)' : 'rotate(180deg)';
        toggleIcon.style.transition = 'transform 0.3s ease';
    }
}

function getNextChecklistStepId() {
    const stepNumbers = Array.from(document.querySelectorAll('#checklistContainer .checklist-item input[type="checkbox"]'))
        .map((checkbox) => {
            const match = String(checkbox.id || '').match(/step(\d+)/i);
            return match ? Number(match[1]) : 0;
        })
        .filter((value) => Number.isFinite(value) && value > 0);

    const nextStepNumber = stepNumbers.length > 0 ? Math.max(...stepNumbers) + 1 : 1;
    return `step${nextStepNumber}`;
}

function getChecklistStepOrder() {
    const container = document.querySelector('#checklistContainer');
    if (!container) return [];

    return Array.from(container.querySelectorAll('.checklist-item'))
        .map((item) => item.querySelector('input[type="checkbox"]')?.id)
        .filter(Boolean);
}

function refreshChecklistDragBindings() {
    document.querySelectorAll('#checklistContainer .checklist-item').forEach((item) => {
        item.draggable = true;
        item.ondragstart = handleChecklistDragStart;
        item.ondragover = handleChecklistDragOver;
        item.ondrop = handleChecklistDrop;
        item.ondragend = handleChecklistDragEnd;

        const dragHandle = item.querySelector('.checklist-drag-handle');
        if (dragHandle) {
            dragHandle.draggable = true;
            dragHandle.ondragstart = handleChecklistDragStart;
            dragHandle.ondragend = handleChecklistDragEnd;
            dragHandle.onmousedown = (event) => event.preventDefault();
        }
    });
}

function applyChecklistStepOrder(stepOrder) {
    const container = document.querySelector('#checklistContainer');
    if (!container || !Array.isArray(stepOrder) || !stepOrder.length) {
        return;
    }

    const currentItems = Array.from(container.querySelectorAll('.checklist-item'));
    const itemMap = new Map(currentItems.map((item) => [item.querySelector('input[type="checkbox"]')?.id, item]));

    stepOrder.forEach((stepId) => {
        const item = itemMap.get(stepId);
        if (item) {
            container.appendChild(item);
            itemMap.delete(stepId);
        }
    });

    itemMap.forEach((item) => container.appendChild(item));
}

function resetChecklistDragState() {
    currentChecklistDragItem = null;
    document.querySelectorAll('#checklistContainer .checklist-item').forEach((item) => {
        item.classList.remove('dragging', 'drag-over');
        const dragHandle = item.querySelector('.checklist-drag-handle');
        if (dragHandle) {
            dragHandle.classList.remove('dragging');
        }
    });
}

function handleChecklistDragStart(event) {
    const sourceElement = event.currentTarget || event.target;
    const item = sourceElement.classList?.contains('checklist-item')
        ? sourceElement
        : sourceElement.closest?.('.checklist-item') || event.target.closest('.checklist-item');
    if (!item || !event.dataTransfer) {
        return;
    }

    currentChecklistDragItem = item;
    item.classList.add('dragging');
    const dragHandle = item.querySelector('.checklist-drag-handle');
    if (dragHandle) {
        dragHandle.classList.add('dragging');
    }
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/plain', item.querySelector('input[type="checkbox"]')?.id || '');
}

function handleChecklistDragOver(event) {
    if (!currentChecklistDragItem) {
        return;
    }

    const targetItem = event.currentTarget?.classList?.contains('checklist-item')
        ? event.currentTarget
        : event.target.closest('.checklist-item');
    if (!targetItem) {
        event.preventDefault();
        return;
    }

    if (targetItem === currentChecklistDragItem) {
        return;
    }

    event.preventDefault();
    const bounds = targetItem.getBoundingClientRect();
    const insertAfter = event.clientY > bounds.top + (bounds.height / 2);

    document.querySelectorAll('#checklistContainer .checklist-item').forEach((item) => {
        item.classList.remove('drag-over');
    });

    targetItem.classList.add('drag-over');
    if (insertAfter) {
        targetItem.after(currentChecklistDragItem);
    } else {
        targetItem.before(currentChecklistDragItem);
    }
}

function handleChecklistDrop(event) {
    if (!currentChecklistDragItem) {
        return;
    }

    event.preventDefault();
    resetChecklistDragState();
    updateProgressionStatus();
    saveProgressionData();
}

function handleChecklistDragEnd() {
    resetChecklistDragState();
}

function addChecklistItem() {
    const container = document.querySelector('#checklistContainer');
    if (!container) return;
    
    const stepId = getNextChecklistStepId();
    
    const newItem = document.createElement('div');
    newItem.className = 'card mb-2 border-0 bg-light checklist-item';
    newItem.draggable = true;
    newItem.innerHTML = `
        <div class="card-body p-2">
            <div class="d-flex align-items-start gap-2">
                <button type="button" class="btn btn-sm btn-light checklist-drag-handle" draggable="true" title="Drag to reorder" aria-label="Drag to reorder step">
                    <i class="bi bi-grip-vertical"></i>
                </button>
                <div class="form-check mt-1">
                    <input class="form-check-input" type="checkbox" id="${stepId}" onchange="updateProgressionStatus()">
                </div>
                <div class="flex-grow-1">
                    <input type="text" class="form-control form-control-sm step-title mb-2" placeholder="Enter step title..." style="font-weight: 600;">
                    <small class="text-muted d-block step-time" id="${stepId}-time"></small>
                    <small class="d-block mt-1"><span class="badge bg-info" id="${stepId}-assignees">Unassigned</span></small>
                </div>
                <div class="btn-group-vertical btn-group-sm">
                    <button type="button" class="btn btn-outline-info" onclick="assignStepEmployees(this, '${stepId}')" title="Assign">
                        <i class="bi bi-people-fill"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="deleteChecklistItem(this)" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(newItem);
    refreshChecklistDragBindings();
    updateProgressionStatus();
    saveProgressionData();
}

function buildChecklistStepElement(stepId, titleText) {
    const newItem = document.createElement('div');
    newItem.className = 'card mb-2 border-0 bg-light checklist-item';
    newItem.draggable = true;
    newItem.innerHTML = `
        <div class="card-body p-2">
            <div class="d-flex align-items-start gap-2">
                <button type="button" class="btn btn-sm btn-light checklist-drag-handle" draggable="true" title="Drag to reorder" aria-label="Drag to reorder step">
                    <i class="bi bi-grip-vertical"></i>
                </button>
                <div class="form-check mt-1">
                    <input class="form-check-input" type="checkbox" id="${stepId}" onchange="updateProgressionStatus()">
                </div>
                <div class="flex-grow-1">
                    <input type="text" class="form-control form-control-sm step-title mb-2" value="${escapeHtml(titleText)}" style="font-weight: 600;">
                    <small class="text-muted d-block step-time" id="${stepId}-time"></small>
                    <small class="d-block mt-1"><span class="badge bg-info" id="${stepId}-assignees">Unassigned</span></small>
                </div>
                <div class="btn-group-vertical btn-group-sm">
                    <button type="button" class="btn btn-outline-info" onclick="assignStepEmployees(this, '${stepId}')" title="Assign">
                        <i class="bi bi-people-fill"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="deleteChecklistItem(this)" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;

    return newItem;
}

function replaceChecklistSteps(stepNames) {
    const container = document.querySelector('#checklistContainer');
    if (!container) {
        return;
    }

    container.innerHTML = '';

    stepNames.forEach((name, index) => {
        const stepId = `step${index + 1}`;
        container.appendChild(buildChecklistStepElement(stepId, name));
    });

    refreshChecklistDragBindings();

    localStorage.setItem('stepAssignments', JSON.stringify({}));
    updateProgressionStatus();
    saveProgressionData();
}

async function importButlerSteps() {
    const butlerButton = document.querySelector('button[onclick="importButlerSteps()"]');
    const originalButlerButtonHtml = butlerButton ? butlerButton.innerHTML : '';

    try {
        if (butlerButton) {
            butlerButton.disabled = true;
            butlerButton.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Generating...';
        }

        const orderNumber = document.querySelector('#orderNumber')?.value?.trim() || '';
        const customerName = document.querySelector('#customerName')?.value?.trim() || 'Customer';
        const printType = document.querySelector('#printType')?.value?.trim() || 'Custom';
        const quantity = Number(document.querySelector('#quantity')?.value || 1);
        const priority = document.querySelector('#priority')?.value?.trim() || 'Normal';
        const dueDate = document.querySelector('#dueDate')?.value?.trim() || '';
        const specifications = document.querySelector('#specifications')?.value?.trim() || '';

        let generatedSteps = [];

        if (typeof puter !== 'undefined' && puter.ai && typeof puter.ai.chat === 'function') {
            const aiPrompt = `Generate 5-8 production checklist steps for this print order.

Order details:
- Order Number: ${orderNumber || 'N/A'}
- Customer: ${customerName}
- Print Type: ${printType || 'Custom'}
- Quantity: ${Number.isFinite(quantity) && quantity > 0 ? quantity : 1}
- Priority: ${priority}
- Due Date: ${dueDate || 'Not set'}
- Specifications: ${specifications || 'None'}

Rules:
- Return only checklist steps, one per line
- No numbering
- No explanation text`;

            try {
                const response = await puter.ai.chat(aiPrompt, {
                    model: 'claude-sonnet-4-5'
                });

                const text = response?.message?.content?.[0]?.text || '';
                generatedSteps = text
                    .split('\n')
                    .map(line => line.replace(/^[\s\-•\d\.]+/, '').trim())
                    .filter(line => line.length > 0)
                    .slice(0, 8);
            } catch (error) {
                console.warn('Puter AI generation failed, falling back to local rules:', error);
            }
        }

        if (generatedSteps.length === 0) {
            if (printType === 'Business Cards') {
                generatedSteps = [
                    'Confirm business card details and final layout',
                    'Approve proof with customer',
                    'Prepare stock and print settings',
                    `Print batch (${quantity || 1} units)`,
                    'Trim and finish cards',
                    'Run quality check and package'
                ];
            } else if (printType === 'Flyers' || printType === 'Brochures' || printType === 'Posters') {
                generatedSteps = [
                    'Validate artwork dimensions and bleed',
                    'Approve proof and paper stock',
                    'Set machine and color profile',
                    `Print production run (${quantity || 1} units)`,
                    'Cut/fold/finish as required',
                    'Quality check and bundle for delivery'
                ];
            } else {
                generatedSteps = [
                    'Review order requirements and specs',
                    'Confirm artwork/assets and approval',
                    'Prepare materials and setup',
                    `Run production for ${quantity || 1} unit(s)`,
                    'Perform final quality control',
                    'Package and mark ready to ship'
                ];
            }

            if (priority === 'Rush' || priority === 'Express') {
                generatedSteps.unshift('Prioritize this order in production queue');
            }
        }

        replaceChecklistSteps(generatedSteps);
        alert(`Generated ${generatedSteps.length} step(s). Review before creating order.`);
    } catch (error) {
        console.error('Error generating steps:', error);
        alert('Failed to generate steps: ' + error.message);
    } finally {
        if (butlerButton) {
            butlerButton.disabled = false;
            butlerButton.innerHTML = originalButlerButtonHtml || '<i class="bi bi-magic me-1"></i> Butler Steps';
        }
    }
}

// Auto Task Delegation using AI
async function autoTaskDelegation() {
    const steps = document.querySelectorAll('.checklist-item');
    if (steps.length === 0) {
        alert('No steps to assign. Add some steps first!');
        return;
    }
    
    // Collect step information
    const stepsList = Array.from(steps).map(step => {
        const titleElement = step.querySelector('.step-title');
        return titleElement ? titleElement.textContent.trim() : 'Unknown Step';
    });
    
    if (employees.length === 0) {
        alert('No employees available. Add employees first!');
        return;
    }
    
    const employeeList = employees.map((e, i) => `${i+1}. ${e.name} (${e.role})`).join('\n');
    
    try {
        // Show loading message
        alert('🤖 AI is analyzing tasks and assigning employees...');
        
        // Build a smarter prompt that considers roles
        const prompt = `You are a smart task delegation system for a print shop.

STEPS TO ASSIGN:
${stepsList.map((s, i) => `${i+1}. ${s}`).join('\n')}

AVAILABLE EMPLOYEES:
${employeeList}

Assign each step to the BEST employee(s) based on their role. Consider:
- Designers handle design/review tasks
- Printers handle printing/production
- QA/Checkers handle quality/inspection
- Packers/Shippers handle final steps

Return ONLY a JSON object (no other text):
{"step_number": "employee_name", ...}
Example: {"1": "John Smith", "2": "Sarah Johnson"}`;

        // Use Puter.js to get AI suggestions
        let assignments = {};
        
        try {
            // Try using puter.ai.txt2txt or similar
            if (puter.ai && typeof puter.ai.txt2txt === 'function') {
                const result = await puter.ai.txt2txt({ prompt });
                const jsonMatch = result.match(/\{[\s\S]*\}/);
                if (jsonMatch) {
                    assignments = JSON.parse(jsonMatch[0]);
                }
            } else {
                throw new Error('Puter.ai not available');
            }
        } catch (puterError) {
            console.warn('Puter AI not available, using smart fallback:', puterError);
            // Use smart fallback based on roles
            assignments = smartTaskDistribution(stepsList);
        }
        
        // Apply assignments
        let assignmentCount = 0;
        stepsList.forEach((stepName, index) => {
            const stepElement = Array.from(steps)[index];
            const checkbox = stepElement.querySelector('input[type="checkbox"]');
            if (!checkbox) return;
            
            const stepId = checkbox.id;
            const assignedName = assignments[String(index + 1)] || assignments[stepName];
            
            if (assignedName) {
                // Find employee by name (partial match)
                const emp = employees.find(e => 
                    e.name.toLowerCase().includes(assignedName.toLowerCase()) ||
                    assignedName.toLowerCase().includes(e.name.split(' ')[0].toLowerCase())
                );
                
                if (emp) {
                    // Save to localStorage
                    const storedAssignments = JSON.parse(localStorage.getItem('stepAssignments') || '{}');
                    storedAssignments[stepId] = [String(emp.id)];
                    localStorage.setItem('stepAssignments', JSON.stringify(storedAssignments));
                    
                    // Update badge
                    const assigneesBadge = stepElement.querySelector(`#${stepId}-assignees`);
                    if (assigneesBadge) {
                        assigneesBadge.textContent = emp.name.split(' ')[0];
                        assigneesBadge.classList.remove('bg-info');
                        assigneesBadge.classList.add('bg-success');
                        assignmentCount++;
                    }
                }
            }
        });
        
        if (assignmentCount > 0) {
            alert(`✅ Auto-assigned ${assignmentCount} step(s) intelligently!`);
        } else {
            alert(`✅ Tasks distributed among employees!`);
        }
        
    } catch (error) {
        console.error('Delegation error:', error);
        // Use smart fallback
        const assignments = smartTaskDistribution(stepsList);
        
        let assignmentCount = 0;
        stepsList.forEach((stepName, index) => {
            const stepElement = Array.from(steps)[index];
            const checkbox = stepElement.querySelector('input[type="checkbox"]');
            if (!checkbox) return;
            
            const stepId = checkbox.id;
            const empName = assignments[String(index + 1)];
            const emp = employees.find(e => e.name === empName);
            
            if (emp) {
                const storedAssignments = JSON.parse(localStorage.getItem('stepAssignments') || '{}');
                storedAssignments[stepId] = [String(emp.id)];
                localStorage.setItem('stepAssignments', JSON.stringify(storedAssignments));
                
                const assigneesBadge = stepElement.querySelector(`#${stepId}-assignees`);
                if (assigneesBadge) {
                    assigneesBadge.textContent = emp.name.split(' ')[0];
                    assigneesBadge.classList.remove('bg-info');
                    assigneesBadge.classList.add('bg-success');
                    assignmentCount++;
                }
            }
        });
        
        alert(`✅ Smart-distributed ${assignmentCount} task(s) based on employee roles!`);
    }
}

// Smart task distribution based on employee roles
function smartTaskDistribution(steps) {
    const assignments = {};
    
    // Group employees by role
    const designerEmp = employees.find(e => e.role.toLowerCase().includes('design'));
    const printerEmp = employees.find(e => e.role.toLowerCase().includes('print'));
    const qaEmp = employees.find(e => e.role.toLowerCase().includes('qa') || e.role.toLowerCase().includes('quality'));
    const packerEmp = employees.find(e => e.role.toLowerCase().includes('pack') || e.role.toLowerCase().includes('ship'));
    
    // Map steps to roles intelligently
    steps.forEach((step, index) => {
        const stepLower = step.toLowerCase();
        let assignedEmp = null;
        
        if (stepLower.includes('design') || stepLower.includes('review') || stepLower.includes('approval')) {
            assignedEmp = designerEmp || employees[0];
        } else if (stepLower.includes('print') || stepLower.includes('production')) {
            assignedEmp = printerEmp || employees[1] || employees[0];
        } else if (stepLower.includes('quality') || stepLower.includes('check') || stepLower.includes('inspect')) {
            assignedEmp = qaEmp || employees[2] || employees[0];
        } else if (stepLower.includes('pack') || stepLower.includes('ship') || stepLower.includes('delivery')) {
            assignedEmp = packerEmp || employees[3] || employees[0];
        } else {
            // Default: round-robin
            assignedEmp = employees[index % employees.length];
        }
        
        assignments[String(index + 1)] = assignedEmp.name;
    });
    
    return assignments;
}

// Delete checklist item
function deleteChecklistItem(btn) {
    const item = btn.closest('.checklist-item');
    if (item) {
        item.remove();
        updateProgressionStatus();
        saveProgressionData();
    }
}


// Update progression status and progress bar
function updateProgressionStatus() {
    const container = document.querySelector('#checklistContainer');
    if (!container) return;
    
    const checkboxes = container.querySelectorAll('input[type="checkbox"]');
    const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
    const totalSteps = checkboxes.length;
    const percentage = totalSteps > 0 ? Math.round((checkedCount / totalSteps) * 100) : 0;
    
    const progressFill = document.querySelector('#progressFill');
    const progressBar = document.querySelector('#progressBar');
    
    if (progressFill && progressBar) {
        progressFill.style.width = percentage + '%';
        progressFill.textContent = percentage + '%';
        
        // Change color based on progress
        if (percentage < 33) {
            progressFill.className = 'progress-bar bg-warning';
        } else if (percentage < 66) {
            progressFill.className = 'progress-bar bg-info';
        } else if (percentage < 100) {
            progressFill.className = 'progress-bar bg-success';
        } else {
            progressFill.className = 'progress-bar bg-success';
        }
    }
    
    // Update timestamps for checked items
    checkboxes.forEach((checkbox) => {
        const timeElement = checkbox.closest('.checklist-item')?.querySelector('.step-time');
        
        if (timeElement) {
            if (checkbox.checked) {
                const now = new Date();
                const formattedTime = now.toLocaleString();
                timeElement.textContent = `✓ Completed at ${formattedTime}`;
                timeElement.style.color = '#28a745';
                timeElement.style.fontWeight = '500';
            } else {
                timeElement.textContent = '';
            }
        }
    });
    
    // Update summary tab
    updateProgressionSummary(checkedCount, totalSteps, percentage);
    
    // Save progress to localStorage
    saveProgressionData();
}

// Update progression summary in tab 2
function updateProgressionSummary(completed, total, percentage) {
    // Update counts
    const completedCount = document.querySelector('#completedCount');
    const totalCount = document.querySelector('#totalCount');
    const statusText = document.querySelector('#statusText');
    
    if (completedCount) completedCount.textContent = completed;
    if (totalCount) totalCount.textContent = total;
    
    // Update status text
    if (statusText) {
        if (percentage === 0) {
            statusText.textContent = 'No steps completed yet';
        } else if (percentage < 50) {
            statusText.textContent = 'Order in progress - early stages';
        } else if (percentage < 100) {
            statusText.textContent = 'Order nearing completion';
        } else {
            statusText.textContent = '✅ All steps completed!';
        }
    }
    
    // Update step details list
    const container = document.querySelector('#checklistContainer');
    const detailsList = document.querySelector('#stepDetailsList');
    
    if (detailsList && container) {
        const items = container.querySelectorAll('.checklist-item');
        let detailsHTML = '';
        
        items.forEach((item, index) => {
            const checkbox = item.querySelector('input[type="checkbox"]');
            const title = item.querySelector('.step-title')?.textContent || 'Step ' + (index + 1);
            const timeEl = item.querySelector('.step-time');
            const time = timeEl?.textContent || 'Not started';
            const isChecked = checkbox?.checked;
            
            detailsHTML += `
                <div class="list-group-item ${isChecked ? 'bg-light-success' : ''}">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            ${isChecked ? '<i class="bi bi-check-circle-fill text-success" style="font-size: 20px;"></i>' : '<i class="bi bi-circle text-muted" style="font-size: 20px;"></i>'}
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-1 ${isChecked ? 'text-success' : ''}">${title}</p>
                            <small class="text-muted">${time}</small>
                        </div>
                    </div>
                </div>
            `;
        });
        
        detailsList.innerHTML = detailsHTML || '<p class="text-muted">No steps added yet</p>';
    }
}

// Save progression data to localStorage
function saveProgressionData() {
    const container = document.querySelector('#checklistContainer');
    const steps = container ? Array.from(container.querySelectorAll('.checklist-item')) : [];
    const progressData = {};

    progressData.order = steps.map((step) => step.querySelector('input[type="checkbox"]')?.id).filter(Boolean);

    steps.forEach((step) => {
        const checkbox = step.querySelector('input[type="checkbox"]');
        if (checkbox) {
            progressData[checkbox.id] = checkbox.checked;
        }
    });
    
    localStorage.setItem(PROGRESSION_ORDER_STORAGE_KEY, JSON.stringify(progressData));
}

// Load progression data from localStorage
function loadProgressionData() {
    const progressData = localStorage.getItem(PROGRESSION_ORDER_STORAGE_KEY);
    if (progressData) {
        try {
            const data = JSON.parse(progressData);
            const stepStates = data.steps && typeof data.steps === 'object' ? data.steps : data;

            if (Array.isArray(data.order)) {
                applyChecklistStepOrder(data.order);
            }

            refreshChecklistDragBindings();

            Object.keys(stepStates).forEach(step => {
                if (step === 'order' || step === 'steps') {
                    return;
                }

                const checkbox = document.querySelector(`#${step}`);
                if (checkbox) {
                    checkbox.checked = stepStates[step];
                }
            });
            updateProgressionStatus();
        } catch (error) {
            console.error('Error loading progression data:', error);
        }
    }
}

// Reset progression checklist
function resetProgressionChecklist() {
    const steps = document.querySelectorAll('#checklistContainer .checklist-item input[type="checkbox"]');
    steps.forEach((checkbox) => {
        if (checkbox) {
            checkbox.checked = false;
        }
    });
    updateProgressionStatus();
}

// Attach event listener to status select to show/hide progression section
document.addEventListener('DOMContentLoaded', () => {
    const listSelect = document.querySelector('#listSelect');
    if (listSelect) {
        listSelect.addEventListener('change', showOrderProgressSection);
    }

    const checklistContainer = document.querySelector('#checklistContainer');
    if (checklistContainer) {
        refreshChecklistDragBindings();
    }
    
    // Load progression data when modal is shown
    const newOrderModal = document.querySelector('#newOrderModal');
    if (newOrderModal) {
        newOrderModal.addEventListener('show.bs.modal', loadProgressionData);
    }
});