<style>
    .control-tab {
        background: #ffffff;
        border: 1px solid #d6dfed;
        border-radius: 14px;
        box-shadow: 0 12px 22px rgba(16, 34, 79, 0.08);
        padding: clamp(16px, 2vw, 24px);
        display: grid;
        gap: 16px;
    }

    .control-tab h3 {
        margin: 0;
        color: #1f315d;
        font-size: 1.2rem;
    }

    .control-tab p {
        margin: 0;
        color: #5f6d8b;
        font-size: 0.88rem;
    }

    .control-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .control-panel {
        border: 1px solid #d8e1f1;
        border-radius: 12px;
        padding: 12px;
        background: #fbfdff;
    }

    .control-panel h4 {
        margin: 0 0 10px;
        color: #23345f;
        font-size: 0.98rem;
    }

    .control-list {
        display: grid;
        gap: 8px;
    }

    .control-role-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 6px;
    }

    .control-compact-item {
        border: 1px solid #e2e8f4;
        border-radius: 10px;
        overflow: hidden;
        background: #ffffff;
    }

    .control-compact-toggle {
        width: 100%;
        border: 0;
        background: #fafcff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 8px 10px;
        font-family: inherit;
        font-size: 0.86rem;
        font-weight: 700;
        color: #314364;
        cursor: pointer;
    }

    .control-compact-toggle .caret {
        font-size: 0.78rem;
        transition: transform 0.15s ease;
    }

    .control-compact-item.open .caret {
        transform: rotate(90deg);
    }

    .control-compact-body {
        display: none;
        padding: 8px;
        border-top: 1px solid #edf2fb;
    }

    .control-compact-item.open .control-compact-body {
        display: grid;
        gap: 6px;
    }

    .control-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        border: 1px solid #e2e8f4;
        border-radius: 10px;
        background: #ffffff;
        padding: 8px 10px;
    }

    .control-item span {
        color: #334667;
        font-size: 0.86rem;
        font-weight: 600;
    }

    .control-role-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        border: 1px solid #e4eaf5;
        border-radius: 8px;
        background: #ffffff;
        padding: 7px 9px;
    }

    .control-role-item span {
        color: #334667;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .control-subhead {
        margin: 0;
        color: #5f6d8b;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .control-input {
        width: 92px;
        min-height: 32px;
        border: 1px solid #cfd8ea;
        border-radius: 8px;
        padding: 0 8px;
        font-family: inherit;
        font-size: 0.84rem;
        color: #2f3e64;
        background: #ffffff;
        text-align: right;
    }

    .control-switch {
        appearance: none;
        width: 46px;
        height: 26px;
        border-radius: 999px;
        background: #c8d3e7;
        position: relative;
        cursor: pointer;
        transition: background 0.18s ease;
        border: 0;
    }

    .control-switch::after {
        content: "";
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #ffffff;
        position: absolute;
        top: 3px;
        left: 3px;
        transition: left 0.18s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .control-switch:checked {
        background: #1f8f4f;
    }

    .control-switch:checked::after {
        left: 23px;
    }

    .control-note {
        border: 1px solid #dbe5f3;
        border-radius: 10px;
        background: #f7fbff;
        color: #4f6288;
        font-size: 0.82rem;
        padding: 10px;
    }

    .control-status {
        color: #1f8f4f;
        font-size: 0.83rem;
        font-weight: 700;
    }

    @media (max-width: 980px) {
        .control-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<article class="content-section" id="control-management">
    <section class="control-tab" data-control-tab>
        <h3>Control Management</h3>
        <p>Turn features on/off for Admin and control which tabs Employees can access.</p>

        <div class="control-grid">
            <section class="control-panel">
                <h4>Admin Features</h4>
                <div class="control-list" id="adminControlsList"></div>
            </section>

            <section class="control-panel">
                <h4>Admin Access by Employee</h4>
                <div class="control-list" id="adminEmployeeAccessList"></div>
            </section>

            <section class="control-panel">
                <h4>Employee Tab Access</h4>
                <div class="control-list" id="employeeControlsList"></div>
            </section>

            <section class="control-panel">
                <h4>General Settings</h4>
                <div class="control-list" id="generalControlsList"></div>
            </section>
        </div>

        <div class="control-note">
            Changes are saved instantly and shared across tabs in this browser. Employee tab restrictions apply on next load or when employee page updates from storage.
        </div>

        <div class="control-status" id="controlStatusMsg">Ready</div>
    </section>
</article>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const root = document.querySelector('[data-control-tab]');
        if (!root) return;

        const controlSettingsKey = 'printopiaControlSettingsV1';
        const accountsStorageKey = 'printopiaAccountsV1';
        const adminList = document.querySelector('#adminControlsList');
        const adminEmployeeList = document.querySelector('#adminEmployeeAccessList');
        const employeeList = document.querySelector('#employeeControlsList');
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

        const getEmployeeAccounts = () => {
            try {
                const raw = JSON.parse(localStorage.getItem(accountsStorageKey) || '[]');
                if (!Array.isArray(raw)) {
                    return [];
                }

                return raw.filter((account) => normalize(account?.role) === 'employee');
            } catch (error) {
                return [];
            }
        };

        const employeeNameKey = (value) => normalize(value).replace(/[^a-z0-9]+/g, ' ').replace(/\s+/g, ' ').trim();

        const getEmployeeOptionList = () => {
            return getEmployeeAccounts().map((account) => ({
                key: employeeNameKey(account?.name || ''),
                label: account?.employeeRole ? `${account.name} (${account.employeeRole})` : account.name,
            })).filter((item) => item.key);
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

        const renderSwitches = () => {
            const settings = loadSettings();
            const employeeOptions = getEmployeeOptionList();

            adminList.innerHTML = adminConfig.map((item) => `
                <label class="control-item">
                    <span>${item.label}</span>
                    <input type="checkbox" class="control-switch" data-scope="adminSections" data-key="${item.key}" ${settings.adminSections[item.key] ? 'checked' : ''}>
                </label>
            `).join('');

            adminEmployeeList.innerHTML = adminConfig.map((item) => {
                const accessMap = settings.adminEmployeeAccess[item.key] || {};
                const isOpen = expandedAdminEmployeeSection === item.key;
                return `
                    <div class="control-compact-item ${isOpen ? 'open' : ''}">
                        <button type="button" class="control-compact-toggle" data-admin-employee-toggle="${item.key}">
                            <span>${item.label}</span>
                            <span class="caret">▶</span>
                        </button>
                        <div class="control-compact-body">
                            <p class="control-subhead">Assign to specific employees</p>
                            <div class="control-role-grid">
                                ${employeeOptions.map((employee) => `
                                    <label class="control-role-item">
                                        <span>${employee.label}</span>
                                        <input type="checkbox" class="control-switch" data-scope="adminEmployeeAccess" data-key="${item.key}" data-employee-key="${employee.key}" ${accessMap[employee.key] ? 'checked' : ''}>
                                    </label>
                                `).join('')}
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

        let expandedAdminEmployeeSection = adminConfig[0]?.key || 'notification-management';

        adminEmployeeList?.addEventListener('click', (event) => {
            const target = event.target;
            if (!(target instanceof HTMLElement)) {
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
            renderSwitches();
        });

        window.addEventListener('storage', (event) => {
            if (event.key === controlSettingsKey || event.key === accountsStorageKey) {
                renderSwitches();
            }
        });

        document.addEventListener('printopia:accounts-updated', renderSwitches);

        renderSwitches();
    });
</script>
