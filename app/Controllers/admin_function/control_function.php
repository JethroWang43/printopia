<script>
    document.addEventListener('DOMContentLoaded', () => {
        const root = document.querySelector('[data-control-tab]');
        if (!root) return;

        const controlSettingsKey = 'printopiaControlSettingsV1';
        const controlUiStateKey = 'printopiaControlUiStateV1';
        const accountsStorageKey = 'printopiaAccountsV1';
        const accountsApiUrl = '<?= base_url('admin/account/list'); ?>';
        const adminList = document.querySelector('#adminControlsList');
        const adminFeaturesCount = document.querySelector('#adminFeaturesCount');
        const adminEmployeeList = document.querySelector('#adminEmployeeAccessList');
        const employeeSearchInput = document.querySelector('#controlEmployeeSearch');
        const employeeRoleFilterSelect = document.querySelector('#controlEmployeeRoleFilter');
        const employeeList = document.querySelector('#employeeControlsList');
        const employeeTabsCount = document.querySelector('#employeeTabsCount');
        const generalList = document.querySelector('#generalControlsList');
        const statusMsg = document.querySelector('#controlStatusMsg');

        const adminConfig = [
            { key: 'notification-management', label: 'Notification' },
            { key: 'calendar-management', label: 'Calendar' },
            { key: 'gallery-management', label: 'Gallery Management' },
            { key: 'order-management', label: 'Order Management' },
            { key: 'inventory-management', label: 'Inventory Management' },
            { key: 'account-management', label: 'Account Management' },
            { key: 'task-management', label: 'Task Management' },
            { key: 'discount-management', label: 'Discount' },
            { key: 'control-management', label: 'Control Management' }
        ];

        const employeeConfig = [
            { key: 'overview', label: 'Dashboard Overview' },
            { key: 'tasks', label: 'Task Tab' },
            { key: 'notifications', label: 'Notification Tab' }
        ];

        const normalize = (value) => String(value || '').trim().toLowerCase();

        let employeeAccounts = [];

        const toDisplayRole = (role, roleOther = '') => {
            const key = normalize(role);
            const other = String(roleOther || '').trim();

            if (key === 'production') return 'Production Staff';
            if (key === 'designer') return 'Designer';
            if (key === 'operator') return 'Machine Operator';
            if (key === 'quality-control') return 'Quality Control';
            if (key === 'others') return other || 'Employee';
            return other || (key ? String(role).trim() : 'Unassigned');
        };

        const toDisplayName = (account) => {
            const first = String(account?.first_name || '').trim();
            const middle = String(account?.middle_name || '').trim();
            const last = String(account?.last_name || '').trim();
            const full = [first, middle, last].filter(Boolean).join(' ').trim();
            return full || String(account?.name || '').trim();
        };

        const loadEmployeeAccounts = async () => {
            try {
                const response = await fetch(accountsApiUrl, {
                    headers: {
                        Accept: 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error: ${response.status}`);
                }

                const payload = await response.json();
                const rows = Array.isArray(payload?.data) ? payload.data : [];

                employeeAccounts = rows
                    .filter((account) => Number(account?.role_id) === 2)
                    .map((account) => ({
                        name: toDisplayName(account),
                        role: 'employee',
                        employeeRole: toDisplayRole(account?.employee_role, account?.employee_role_other),
                    }))
                    .filter((account) => account.name);
            } catch (error) {
                employeeAccounts = [];
            }
        };

        const getEmployeeAccounts = () => {
            return employeeAccounts.filter((account) => normalize(account?.role) === 'employee');
        };

        const employeeNameKey = (value) => normalize(value).replace(/[^a-z0-9]+/g, ' ').replace(/\s+/g, ' ').trim();

        const getEmployeeOptionList = () => {
            return getEmployeeAccounts().map((account) => ({
                key: employeeNameKey(account?.name || ''),
                roleKey: normalize(account?.employeeRole || 'unassigned'),
                roleLabel: String(account?.employeeRole || 'Unassigned').trim() || 'Unassigned',
                label: account?.employeeRole ? `${account.name} (${account.employeeRole})` : account.name,
            })).filter((item) => item.key);
        };

        const getFilteredEmployeeOptions = (employeeOptions) => {
            const query = employeeFilterQuery;
            const roleKey = employeeRoleFilter;

            return employeeOptions.filter((employee) => {
                const matchesRole = roleKey === 'all' || employee.roleKey === roleKey;
                if (!matchesRole) {
                    return false;
                }

                if (!query) {
                    return true;
                }

                const haystack = `${employee.label} ${employee.roleLabel}`.toLowerCase();
                return haystack.includes(query);
            });
        };

        const defaults = {
            adminSections: Object.fromEntries(adminConfig.map(item => [item.key, true])),
            adminEmployeeAccess: Object.fromEntries(adminConfig.map(item => [item.key, {}])),
            employeeTabs: Object.fromEntries(employeeConfig.map(item => [item.key, true])),
            generalSettings: {
                employeeAutoRefreshEnabled: true,
                employeeAutoRefreshIntervalSec: 30,
            }
        };

        const loadSettings = () => {
            try {
                const raw = JSON.parse(localStorage.getItem(controlSettingsKey) || '{}');
                const mergedEmployeeAccess = { ...defaults.adminEmployeeAccess };
                Object.keys(mergedEmployeeAccess).forEach((sectionKey) => {
                    mergedEmployeeAccess[sectionKey] = {
                        ...defaults.adminEmployeeAccess[sectionKey],
                        ...(raw?.adminEmployeeAccess?.[sectionKey] || {}),
                    };
                });

                return {
                    adminSections: { ...defaults.adminSections, ...(raw.adminSections || {}) },
                    adminEmployeeAccess: mergedEmployeeAccess,
                    employeeTabs: { ...defaults.employeeTabs, ...(raw.employeeTabs || {}) },
                    generalSettings: { ...defaults.generalSettings, ...(raw.generalSettings || {}) },
                };
            } catch (error) {
                return defaults;
            }
        };

        const saveSettings = (settings) => {
            localStorage.setItem(controlSettingsKey, JSON.stringify(settings));
            document.dispatchEvent(new CustomEvent('printopia:controls-updated', { detail: settings }));
            if (statusMsg) {
                statusMsg.textContent = 'Saved at ' + new Date().toLocaleTimeString();
            }
        };

        const loadUiState = () => {
            try {
                const raw = JSON.parse(localStorage.getItem(controlUiStateKey) || '{}');
                return {
                    expandedAdminEmployeeSection: typeof raw?.expandedAdminEmployeeSection === 'string'
                        ? raw.expandedAdminEmployeeSection
                        : ''
                };
            } catch (error) {
                return {
                    expandedAdminEmployeeSection: ''
                };
            }
        };

        const saveUiState = (uiState) => {
            localStorage.setItem(controlUiStateKey, JSON.stringify(uiState));
        };

        const renderSwitches = () => {
            const settings = loadSettings();
            const employeeOptions = getEmployeeOptionList();
            const filteredEmployeeOptions = getFilteredEmployeeOptions(employeeOptions);

            if (employeeRoleFilterSelect) {
                const roleKeys = Array.from(new Set(employeeOptions.map((employee) => employee.roleKey).filter(Boolean))).sort();
                const previousValue = employeeRoleFilterSelect.value || employeeRoleFilter || 'all';
                const roleOptionsHtml = ['<option value="all">All roles</option>']
                    .concat(roleKeys.map((roleKey) => {
                        const roleLabel = employeeOptions.find((employee) => employee.roleKey === roleKey)?.roleLabel || roleKey;
                        return `<option value="${roleKey}">${roleLabel}</option>`;
                    }))
                    .join('');

                employeeRoleFilterSelect.innerHTML = roleOptionsHtml;
                const hasPrevious = previousValue === 'all' || roleKeys.includes(previousValue);
                employeeRoleFilter = hasPrevious ? previousValue : 'all';
                employeeRoleFilterSelect.value = employeeRoleFilter;
            }

            adminList.innerHTML = adminConfig.map((item) => `
                <label class="control-item">
                    <span>${item.label}</span>
                    <input type="checkbox" class="control-switch" data-scope="adminSections" data-key="${item.key}" ${settings.adminSections[item.key] ? 'checked' : ''}>
                </label>
            `).join('');

            if (adminFeaturesCount) {
                const enabledCount = adminConfig.filter((item) => Boolean(settings.adminSections[item.key])).length;
                adminFeaturesCount.textContent = `${enabledCount}/${adminConfig.length} enabled`;
            }

            adminEmployeeList.innerHTML = adminConfig.map((item) => {
                const accessMap = settings.adminEmployeeAccess[item.key] || {};
                const isOpen = expandedAdminEmployeeSection === item.key;
                const enabledCount = filteredEmployeeOptions.filter((employee) => Boolean(accessMap[employee.key])).length;
                return `
                    <div class="control-compact-item ${isOpen ? 'open' : ''}">
                        <button type="button" class="control-compact-toggle" data-admin-employee-toggle="${item.key}">
                            <span>${item.label}</span>
                            <span class="caret">▶</span>
                        </button>
                        <div class="control-compact-body">
                            <div class="control-bulk-row">
                                <p class="control-subhead">Assign to specific employees (${enabledCount}/${filteredEmployeeOptions.length})</p>
                                <div class="control-bulk-actions">
                                    <button type="button" class="control-bulk-btn enable" data-admin-employee-bulk="enable" data-key="${item.key}">Enable Filtered</button>
                                    <button type="button" class="control-bulk-btn disable" data-admin-employee-bulk="disable" data-key="${item.key}">Disable Filtered</button>
                                </div>
                            </div>
                            <div class="control-role-grid">
                                ${filteredEmployeeOptions.length ? filteredEmployeeOptions.map((employee) => `
                                    <label class="control-role-item">
                                        <span>${employee.label}</span>
                                        <input type="checkbox" class="control-switch" data-scope="adminEmployeeAccess" data-key="${item.key}" data-employee-key="${employee.key}" ${accessMap[employee.key] ? 'checked' : ''}>
                                    </label>
                                `).join('') : '<div class="control-subhead">No employees match the current filters.</div>'}
                            </div>
                            <div class="control-subhead">Only checked employees can access this admin tab.</div>
                        </div>
                    </div>
                `;
            }).join('');

            employeeList.innerHTML = employeeConfig.map((item) => `
                <label class="control-item">
                    <span>${item.label}</span>
                    <input type="checkbox" class="control-switch" data-scope="employeeTabs" data-key="${item.key}" ${settings.employeeTabs[item.key] ? 'checked' : ''}>
                </label>
            `).join('');

            if (employeeTabsCount) {
                const enabledCount = employeeConfig.filter((item) => Boolean(settings.employeeTabs[item.key])).length;
                employeeTabsCount.textContent = `${enabledCount}/${employeeConfig.length} enabled`;
            }

            const intervalValue = Number(settings.generalSettings.employeeAutoRefreshIntervalSec || 30);
            generalList.innerHTML = `
                <label class="control-item">
                    <span>Employee Auto Refresh</span>
                    <input type="checkbox" class="control-switch" data-scope="generalSettings" data-key="employeeAutoRefreshEnabled" ${settings.generalSettings.employeeAutoRefreshEnabled ? 'checked' : ''}>
                </label>
                <label class="control-item">
                    <span>Employee Refresh Interval (sec)</span>
                    <input type="number" class="control-input" data-scope="generalSettings" data-key="employeeAutoRefreshIntervalSec" min="10" max="300" step="5" value="${Number.isFinite(intervalValue) ? intervalValue : 30}">
                </label>
            `;
        };

        root.addEventListener('change', (event) => {
            const target = event.target;
            if (!(target instanceof HTMLInputElement) || !target.dataset.scope || !target.dataset.key) {
                return;
            }

            const settings = loadSettings();
            const scope = target.dataset.scope;
            const key = target.dataset.key;
            const employeeKey = target.dataset.employeeKey;

            if (scope === 'adminEmployeeAccess') {
                if (!employeeKey || !settings.adminEmployeeAccess[key]) {
                    return;
                }

                settings.adminEmployeeAccess[key] = {
                    ...settings.adminEmployeeAccess[key],
                    [employeeKey]: target.checked,
                };

                saveSettings(settings);
                return;
            }
            if (!scope || !key || !settings[scope]) {
                return;
            }

            if (target.type === 'checkbox') {
                settings[scope][key] = target.checked;
            } else if (target.type === 'number') {
                const rawValue = Number(target.value);
                const clamped = Math.max(10, Math.min(300, Number.isFinite(rawValue) ? rawValue : 30));
                target.value = String(clamped);
                settings[scope][key] = clamped;
            } else {
                return;
            }

            // Prevent locking the UI completely.
            if (scope === 'adminSections' && key === 'control-management' && !target.checked) {
                settings[scope][key] = true;
                target.checked = true;
            }

            saveSettings(settings);
        });

        root.addEventListener('click', (event) => {
            const target = event.target;
            if (!(target instanceof HTMLElement)) {
                return;
            }

            const bulkButton = target.closest('[data-bulk-scope][data-bulk-mode]');
            if (!bulkButton) {
                return;
            }

            const scope = bulkButton.getAttribute('data-bulk-scope');
            const mode = bulkButton.getAttribute('data-bulk-mode');
            if (!scope || !mode) {
                return;
            }

            const settings = loadSettings();
            const shouldEnable = mode === 'enable';

            if (scope === 'adminSections') {
                adminConfig.forEach((item) => {
                    settings.adminSections[item.key] = shouldEnable;
                });

                // Prevent locking the UI completely.
                settings.adminSections['control-management'] = true;
            } else if (scope === 'employeeTabs') {
                employeeConfig.forEach((item) => {
                    settings.employeeTabs[item.key] = shouldEnable;
                });
            } else {
                return;
            }

            saveSettings(settings);
            renderSwitches();
        });

        const initialUiState = loadUiState();
        let expandedAdminEmployeeSection = initialUiState.expandedAdminEmployeeSection || '';
        let employeeFilterQuery = '';
        let employeeRoleFilter = 'all';

        employeeSearchInput?.addEventListener('input', () => {
            employeeFilterQuery = normalize(employeeSearchInput.value || '');
            renderSwitches();
        });

        employeeRoleFilterSelect?.addEventListener('change', () => {
            employeeRoleFilter = employeeRoleFilterSelect.value || 'all';
            renderSwitches();
        });

        adminEmployeeList?.addEventListener('click', (event) => {
            const target = event.target;
            if (!(target instanceof HTMLElement)) {
                return;
            }

            const bulkButton = target.closest('[data-admin-employee-bulk]');
            if (bulkButton) {
                const sectionKey = bulkButton.getAttribute('data-key');
                const bulkMode = bulkButton.getAttribute('data-admin-employee-bulk');
                if (!sectionKey || !bulkMode) {
                    return;
                }

                const settings = loadSettings();
                if (!settings.adminEmployeeAccess[sectionKey]) {
                    return;
                }

                const filteredEmployeeOptions = getFilteredEmployeeOptions(getEmployeeOptionList());
                const shouldEnable = bulkMode === 'enable';

                settings.adminEmployeeAccess[sectionKey] = {
                    ...settings.adminEmployeeAccess[sectionKey],
                };

                filteredEmployeeOptions.forEach((employee) => {
                    settings.adminEmployeeAccess[sectionKey][employee.key] = shouldEnable;
                });

                saveSettings(settings);
                renderSwitches();
                return;
            }

            const toggle = target.closest('[data-admin-employee-toggle]');
            if (!toggle) {
                return;
            }

            const nextKey = toggle.getAttribute('data-admin-employee-toggle');
            if (!nextKey) {
                return;
            }

            expandedAdminEmployeeSection = expandedAdminEmployeeSection === nextKey ? '' : nextKey;
            saveUiState({ expandedAdminEmployeeSection });
            renderSwitches();
        });

        window.addEventListener('storage', async (event) => {
            if (event.key === controlSettingsKey || event.key === accountsStorageKey) {
                if (event.key === accountsStorageKey) {
                    await loadEmployeeAccounts();
                }
                renderSwitches();
            }
        });

        document.addEventListener('printopia:accounts-updated', async () => {
            await loadEmployeeAccounts();
            renderSwitches();
        });

        const initializeControlTab = async () => {
            await loadEmployeeAccounts();
            renderSwitches();
        };

        void initializeControlTab();
    });
</script>