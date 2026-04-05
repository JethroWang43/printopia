<style>
    .account-tab {
        background: #ffffff;
        border: 1px solid #d6dfed;
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(16, 34, 79, 0.06);
        padding: 10px;
        min-height: 620px;
    }

    .account-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 10px;
        padding: 2px 4px;
    }

    .account-topbar h3 {
        margin: 0;
        color: #243458;
        font-size: 1.06rem;
        font-weight: 700;
    }

    .account-add-btn {
        min-height: 36px;
        border: 1px solid #7f1d1d;
        border-radius: 8px;
        background: linear-gradient(180deg, #9b1b11 0%, #7f150d 100%);
        color: #ffffff;
        padding: 0 14px;
        font-family: inherit;
        font-size: 0.84rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .account-toolbar {
        border: 1px solid #5ea5f6;
        border-radius: 8px;
        padding: 8px;
        display: grid;
        grid-template-columns: minmax(220px, 1fr) 180px;
        gap: 8px;
        margin-bottom: 12px;
    }

    .account-search,
    .account-filter {
        min-height: 30px;
        border: 1px solid #b4bfd4;
        border-radius: 14px;
        background: #ffffff;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0 10px;
        color: #4e5d7d;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .account-search input,
    .account-filter select {
        width: 100%;
        border: 0;
        outline: none;
        background: transparent;
        font: inherit;
        color: #2f3e64;
    }

    .account-list {
        display: grid;
        gap: 8px;
    }

    .account-list-head {
        border: 1px solid #d8e1f1;
        border-radius: 8px;
        background: #f8fbff;
        padding: 8px 10px;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }

    .account-list-head .head-name {
        text-align: left;
    }

    .account-list-head span {
        color: #4f5f83;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    .account-list-head .head-users {
        text-align: center;
    }

    .account-list-head .head-edit {
        text-align: center;
    }

    .account-item {
        border: 1px solid #e1e6f1;
        border-radius: 8px;
        background: #f4f5f7;
        padding: 8px 10px;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        align-items: center;
        gap: 10px;
        cursor: pointer;
        transition: border-color 0.16s ease, box-shadow 0.16s ease, transform 0.16s ease;
    }

    .account-item > div {
        text-align: left;
    }

    .account-item:hover {
        border-color: #c8d4ea;
        box-shadow: 0 8px 16px rgba(16, 34, 79, 0.07);
        transform: translateY(-1px);
    }

    .account-item strong {
        display: block;
        color: #253556;
        font-size: 0.96rem;
        line-height: 1.2;
    }

    .account-item p {
        margin: 0;
        color: #5e6d8e;
        font-size: 0.79rem;
        line-height: 1.25;
    }

    .account-role {
        border-radius: 999px;
        padding: 2px 10px;
        color: #fff;
        font-size: 0.68rem;
        font-weight: 700;
        line-height: 1.3;
        text-transform: capitalize;
        justify-self: center;
    }

    .account-role.employee {
        background: #d59312;
    }

    .account-role.user {
        background: #0bb71c;
    }

    .account-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 24, 44, 0.5);
        z-index: 1300;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 14px;
    }

    .account-modal-backdrop.show {
        display: flex;
    }

    .account-modal {
        width: min(520px, 96vw);
        border: 1px solid #d7dff0;
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 20px 36px rgba(16, 34, 79, 0.24);
        overflow: hidden;
    }

    .account-modal-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 12px 14px;
        border-bottom: 1px solid #e8edf7;
        background: #f7f9ff;
    }

    .account-modal-head h4 {
        margin: 0;
        color: #23345a;
        font-size: 1rem;
    }

    .account-modal-head small {
        display: block;
        margin-top: 2px;
        color: #647392;
        font-size: 0.76rem;
    }

    .account-modal-close {
        width: 32px;
        height: 32px;
        border: 1px solid #d0d8e9;
        border-radius: 8px;
        background: #ffffff;
        color: #33486f;
        font-size: 1rem;
        cursor: pointer;
    }

    .account-modal-body {
        padding: 14px;
        display: grid;
        gap: 12px;
    }

    .account-modal-grid {
        display: grid;
        gap: 12px;
    }

    .account-name-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .account-password-row {
        position: relative;
    }

    .account-password-row input {
        padding-right: 40px;
    }

    .account-password-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        border: 0;
        background: transparent;
        color: #33486f;
        cursor: pointer;
        font-size: 0.95rem;
        line-height: 1;
        padding: 0;
    }

    .account-form-field {
        display: grid;
        gap: 6px;
    }

    .account-form-field label {
        color: #45567c;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .account-form-field input,
    .account-form-field select {
        min-height: 38px;
        border: 1px solid #cfd8ea;
        border-radius: 8px;
        padding: 0 10px;
        font-family: inherit;
        font-size: 0.86rem;
        color: #2f3e64;
    }

    .account-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 4px;
    }

    .account-modal-actions button {
        min-height: 36px;
        border-radius: 8px;
        border: 1px solid #cfd8ea;
        padding: 0 12px;
        font-family: inherit;
        font-size: 0.82rem;
        font-weight: 700;
        cursor: pointer;
    }

    .account-modal-cancel {
        background: #f4f7fd;
        color: #374a72;
    }

    .account-modal-save {
        border-color: #7f1d1d;
        background: linear-gradient(180deg, #9b1b11 0%, #7f150d 100%);
        color: #ffffff;
    }

    .account-modal-save.update-mode {
        background: linear-gradient(180deg, #7f1d1d 0%, #66150f 100%);
    }

    .account-edit-btn {
        width: 30px;
        height: 30px;
        border: 1px solid #d1d7e6;
        border-radius: 8px;
        background: #ffffff;
        color: #33486f;
        font-size: 0.95rem;
        cursor: pointer;
        display: inline-grid;
        place-items: center;
        justify-self: center;
    }

    .account-empty {
        border: 1px dashed #d7deec;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        color: #647392;
        font-size: 0.86rem;
        background: #f8faff;
    }

    @media (max-width: 900px) {
        .account-toolbar {
            grid-template-columns: 1fr;
        }

        .account-item {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .account-list-head {
            grid-template-columns: 1fr;
            gap: 6px;
        }

        .account-list-head .head-users,
        .account-list-head .head-edit {
            width: auto;
            min-width: 0;
            text-align: left;
        }
    }
</style>

<article class="content-section" id="account-management">
    <section class="account-tab" data-account-tab>
        <div class="account-topbar">
            <h3>Account Management</h3>
            <button type="button" class="account-add-btn" id="accountAddBtn">＋ Add Accounts</button>
        </div>

        <div class="account-toolbar">
            <label class="account-search" aria-label="Search accounts">
                <span>🔍</span>
                <input id="accountSearchInput" type="text" placeholder="Search here">
            </label>
            <label class="account-filter" aria-label="Filter account role">
                <span>⛃</span>
                <select id="accountRoleFilter">
                    <option value="all">All Categories</option>
                    <option value="employee">Employee</option>
                    <option value="user">User</option>
                </select>
            </label>
        </div>

        <div class="account-list-head" aria-hidden="true">
            <span class="head-name">Name</span>
            <span class="head-users">Users</span>
            <span class="head-edit">Edit</span>
        </div>

        <div class="account-list" id="accountList"></div>

        <div class="account-modal-backdrop" id="accountCreateModal" aria-hidden="true">
            <form class="account-modal" id="accountCreateForm">
                <div class="account-modal-head">
                    <div>
                        <h4 id="accountModalTitle">Create Account</h4>
                        <small id="accountModalSubtitle">Choose a user or employee account type before saving.</small>
                    </div>
                    <button type="button" class="account-modal-close" id="accountCreateClose" aria-label="Close create account">✕</button>
                </div>

                <div class="account-modal-body">
                    <div class="account-modal-grid">
                        <div class="account-name-grid">
                            <div class="account-form-field">
                                <label for="accountCreateFirstName">First Name <span style="color:#a11c13">*</span></label>
                                <input id="accountCreateFirstName" type="text" placeholder="Enter first name" required>
                            </div>

                            <div class="account-form-field">
                                <label for="accountCreateLastName">Last Name <span style="color:#a11c13">*</span></label>
                                <input id="accountCreateLastName" type="text" placeholder="Enter last name" required>
                            </div>
                        </div>

                        <div class="account-form-field">
                            <label for="accountCreateEmail">Email Address <span style="color:#a11c13">*</span></label>
                            <input id="accountCreateEmail" type="email" placeholder="Enter your email address" required>
                        </div>

                        <div class="account-form-field">
                            <label for="accountCreatePhone">Phone Number <span style="color:#a11c13">*</span></label>
                            <input id="accountCreatePhone" type="tel" placeholder="Enter your phone number" required>
                        </div>

                        <div class="account-form-field account-password-row">
                            <label for="accountCreatePassword">Password <span style="color:#a11c13">*</span></label>
                            <input id="accountCreatePassword" type="password" placeholder="Create a password" required>
                            <button type="button" class="account-password-toggle" id="accountCreatePasswordToggle" aria-label="Toggle password visibility">👁</button>
                        </div>

                        <div class="account-form-field account-password-row">
                            <label for="accountCreateConfirmPassword">Password <span style="color:#a11c13">*</span></label>
                            <input id="accountCreateConfirmPassword" type="password" placeholder="Confirm your password" required>
                            <button type="button" class="account-password-toggle" id="accountCreateConfirmPasswordToggle" aria-label="Toggle confirm password visibility">👁</button>
                        </div>

                        <div class="account-form-field">
                            <label for="accountCreateRole">Account Type</label>
                            <select id="accountCreateRole" required>
                                <option value="user">User</option>
                                <option value="employee">Employee</option>
                            </select>
                        </div>

                        <div class="account-form-field" id="accountEmployeeRoleField" style="display:none;">
                            <label for="accountCreateEmployeeRole">Employee Role <span style="color:#a11c13">*</span></label>
                            <select id="accountCreateEmployeeRole">
                                <option value="">Select role</option>
                                <option value="production">Production Staff</option>
                                <option value="designer">Designer</option>
                                <option value="operator">Machine Operator</option>
                                <option value="quality-control">Quality Control</option>
                                <option value="others">Others</option>
                            </select>
                        </div>

                        <div class="account-form-field" id="accountEmployeeRoleOtherField" style="display:none;">
                            <label for="accountCreateEmployeeRoleOther">Specify Role <span style="color:#a11c13">*</span></label>
                            <input id="accountCreateEmployeeRoleOther" type="text" placeholder="Type employee role">
                        </div>

                        <div class="account-modal-actions">
                            <button type="button" class="account-modal-cancel" id="accountCreateCancel">Cancel</button>
                            <button type="submit" class="account-modal-save" id="accountSaveBtn">Create Account →</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</article>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const root = document.querySelector('[data-account-tab]');
        if (!root) {
            return;
        }

        const listEl = document.querySelector('#accountList');
        const searchInput = document.querySelector('#accountSearchInput');
        const roleFilter = document.querySelector('#accountRoleFilter');
        const addBtn = document.querySelector('#accountAddBtn');
        const createModal = document.querySelector('#accountCreateModal');
        const createForm = document.querySelector('#accountCreateForm');
        const createCloseBtn = document.querySelector('#accountCreateClose');
        const createCancelBtn = document.querySelector('#accountCreateCancel');
        const createFirstNameInput = document.querySelector('#accountCreateFirstName');
        const createLastNameInput = document.querySelector('#accountCreateLastName');
        const createPhoneInput = document.querySelector('#accountCreatePhone');
        const createPasswordInput = document.querySelector('#accountCreatePassword');
        const createConfirmPasswordInput = document.querySelector('#accountCreateConfirmPassword');
        const createPasswordToggle = document.querySelector('#accountCreatePasswordToggle');
        const createConfirmPasswordToggle = document.querySelector('#accountCreateConfirmPasswordToggle');
        const createRoleInput = document.querySelector('#accountCreateRole');
        const employeeRoleField = document.querySelector('#accountEmployeeRoleField');
        const employeeRoleOtherField = document.querySelector('#accountEmployeeRoleOtherField');
        const employeeRoleSelectInput = document.querySelector('#accountCreateEmployeeRole');
        const employeeRoleOtherInput = document.querySelector('#accountCreateEmployeeRoleOther');
        const createEmailInput = document.querySelector('#accountCreateEmail');
        const modalTitle = document.querySelector('#accountModalTitle');
        const modalSubtitle = document.querySelector('#accountModalSubtitle');
        const saveBtn = document.querySelector('#accountSaveBtn');
        const accountsStorageKey = 'printopiaAccountsV1';

        let editAccountId = null;
        let modalMode = 'create';

        const seedAccounts = [
            {
                id: 1,
                firstName: 'John',
                lastName: 'Smith',
                name: 'John Smith',
                role: 'employee',
                employeeRole: 'operator',
                email: 'john.smith@printopia.test',
                phone: '0917-111-0001',
                details: 'john.smith@printopia.test | Role: operator | Date Joined: 03-20-2026'
            },
            {
                id: 2,
                firstName: 'Maria',
                lastName: 'Garcia',
                name: 'Maria Garcia',
                role: 'employee',
                employeeRole: 'designer',
                email: 'maria.garcia@printopia.test',
                phone: '0917-222-0002',
                details: 'maria.garcia@printopia.test | Role: designer | Date Joined: 02-10-2026'
            },
            {
                id: 3,
                firstName: 'James',
                lastName: 'Wilson',
                name: 'James Wilson',
                role: 'employee',
                employeeRole: 'quality-control',
                email: 'james.wilson@printopia.test',
                phone: '0917-333-0003',
                details: 'james.wilson@printopia.test | Role: quality-control | Date Joined: 02-14-2026'
            },
            {
                id: 4,
                firstName: 'Emily',
                lastName: 'Chen',
                name: 'Emily Chen',
                role: 'employee',
                employeeRole: 'production-manager',
                email: 'emily.chen@printopia.test',
                phone: '0917-444-0004',
                details: 'emily.chen@printopia.test | Role: production-manager | Date Joined: 02-18-2026'
            },
            {
                id: 5,
                firstName: 'Michael',
                lastName: 'Brown',
                name: 'Michael Brown',
                role: 'employee',
                employeeRole: 'finishing-specialist',
                email: 'michael.brown@printopia.test',
                phone: '0917-555-0005',
                details: 'michael.brown@printopia.test | Role: finishing-specialist | Date Joined: 02-21-2026'
            },
            {
                id: 6,
                firstName: 'Sarah',
                lastName: 'Davis',
                name: 'Sarah Davis',
                role: 'employee',
                employeeRole: 'customer-service',
                email: 'sarah.davis@printopia.test',
                phone: '0917-666-0006',
                details: 'sarah.davis@printopia.test | Role: customer-service | Date Joined: 02-25-2026'
            }
        ];

        const loadAccounts = () => {
            try {
                const raw = JSON.parse(localStorage.getItem(accountsStorageKey) || '[]');
                if (Array.isArray(raw) && raw.length) {
                    const merged = [...raw];
                    const seen = new Set(
                        raw.map((account) => String(account?.email || account?.name || '').trim().toLowerCase()).filter(Boolean)
                    );

                    seedAccounts.forEach((seedAccount) => {
                        const seedKey = String(seedAccount?.email || seedAccount?.name || '').trim().toLowerCase();
                        if (!seedKey || seen.has(seedKey)) {
                            return;
                        }

                        merged.push(seedAccount);
                        seen.add(seedKey);
                    });

                    if (merged.length !== raw.length) {
                        localStorage.setItem(accountsStorageKey, JSON.stringify(merged));
                        document.dispatchEvent(new CustomEvent('printopia:accounts-updated', { detail: merged }));
                    }

                    return merged;
                }
            } catch (error) {
                // Fall back to seed data if storage is invalid.
            }

            return [...seedAccounts];
        };

        const saveAccounts = () => {
            localStorage.setItem(accountsStorageKey, JSON.stringify(accounts));
            document.dispatchEvent(new CustomEvent('printopia:accounts-updated', { detail: accounts }));
        };

        let accounts = loadAccounts();

        const updateEmployeeRoleVisibility = () => {
            const isEmployee = createRoleInput?.value === 'employee';
            const roleSelection = (employeeRoleSelectInput?.value || '').trim();
            const isOtherRole = isEmployee && roleSelection === 'others';

            if (employeeRoleField) {
                employeeRoleField.style.display = isEmployee ? '' : 'none';
            }
            if (employeeRoleOtherField) {
                employeeRoleOtherField.style.display = isOtherRole ? '' : 'none';
            }

            if (employeeRoleSelectInput) {
                employeeRoleSelectInput.required = isEmployee;
            }
            if (employeeRoleOtherInput) {
                employeeRoleOtherInput.required = isOtherRole;
                if (!isOtherRole) {
                    employeeRoleOtherInput.value = '';
                }
            }
        };

        const setModalContext = (mode, account = null) => {
            modalMode = mode;
            editAccountId = account ? account.id : null;

            if (modalTitle) {
                modalTitle.textContent = mode === 'edit' ? 'Update Account' : 'Create Account';
            }

            if (modalSubtitle) {
                modalSubtitle.textContent = mode === 'edit'
                    ? 'Review the account details below, then update and save changes.'
                    : 'Choose a user or employee account type before saving.';
            }

            if (saveBtn) {
                saveBtn.textContent = mode === 'edit' ? 'Update Account →' : 'Create Account →';
                saveBtn.classList.toggle('update-mode', mode === 'edit');
            }
        };

        const fillModal = (account) => {
            const [firstName = '', lastName = ''] = String(account?.name || '').split(' ');
            const email = account?.email || '';
            const phone = account?.phone || '';

            if (createFirstNameInput) createFirstNameInput.value = firstName;
            if (createLastNameInput) createLastNameInput.value = lastName || '';
            if (createEmailInput) createEmailInput.value = email;
            if (createPhoneInput) createPhoneInput.value = phone;
            if (createRoleInput) createRoleInput.value = account?.role || 'user';
            if (employeeRoleSelectInput) {
                const incomingEmployeeRole = String(account?.employeeRole || '').trim();
                if (incomingEmployeeRole && ['production', 'designer', 'operator', 'quality-control'].includes(incomingEmployeeRole)) {
                    employeeRoleSelectInput.value = incomingEmployeeRole;
                    if (employeeRoleOtherInput) {
                        employeeRoleOtherInput.value = '';
                    }
                } else if (incomingEmployeeRole) {
                    employeeRoleSelectInput.value = 'others';
                    if (employeeRoleOtherInput) {
                        employeeRoleOtherInput.value = incomingEmployeeRole;
                    }
                } else {
                    employeeRoleSelectInput.value = '';
                    if (employeeRoleOtherInput) {
                        employeeRoleOtherInput.value = '';
                    }
                }
            }
            if (createPasswordInput) createPasswordInput.value = '';
            if (createConfirmPasswordInput) createConfirmPasswordInput.value = '';
            updateEmployeeRoleVisibility();
        };

        const setModalOpen = (isOpen) => {
            if (!createModal) {
                return;
            }

            createModal.classList.toggle('show', isOpen);
            createModal.setAttribute('aria-hidden', isOpen ? 'false' : 'true');

            if (isOpen) {
                setTimeout(() => createFirstNameInput?.focus(), 0);
            }
        };

        const escapeHtml = (value) => String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#39;');

        const render = () => {
            const query = (searchInput.value || '').trim().toLowerCase();
            const selectedRole = roleFilter.value;

            const filtered = accounts.filter((account) => {
                const matchesQuery = !query ||
                    account.name.toLowerCase().includes(query) ||
                    account.details.toLowerCase().includes(query);
                const matchesRole = selectedRole === 'all' || account.role === selectedRole;
                return matchesQuery && matchesRole;
            });

            if (!filtered.length) {
                listEl.innerHTML = '<div class="account-empty">No account matched your filters.</div>';
                return;
            }

            listEl.innerHTML = filtered.map((account) => `
                <article class="account-item" data-id="${account.id}" data-open-account="${account.id}" tabindex="0" role="button" aria-label="View or edit ${escapeHtml(account.name)}">
                    <div>
                        <strong>${escapeHtml(account.name)}</strong>
                        <p>${escapeHtml(account.details)}</p>
                    </div>
                    <span class="account-role ${escapeHtml(account.role)}">${escapeHtml(account.role)}</span>
                    <button type="button" class="account-edit-btn" data-edit-id="${account.id}" title="Edit account">✎</button>
                </article>
            `).join('');
        };

        addBtn.addEventListener('click', () => {
            createForm?.reset();
            if (createRoleInput) {
                createRoleInput.value = 'user';
            }
            if (employeeRoleSelectInput) {
                employeeRoleSelectInput.value = '';
            }
            if (employeeRoleOtherInput) {
                employeeRoleOtherInput.value = '';
            }
            setModalContext('create');
            updateEmployeeRoleVisibility();
            setModalOpen(true);
        });

        createRoleInput?.addEventListener('change', updateEmployeeRoleVisibility);
        employeeRoleSelectInput?.addEventListener('change', updateEmployeeRoleVisibility);

        createCloseBtn?.addEventListener('click', () => setModalOpen(false));
        createCancelBtn?.addEventListener('click', () => setModalOpen(false));

        createModal?.addEventListener('click', (event) => {
            if (event.target === createModal) {
                setModalOpen(false);
            }
        });

        createForm?.addEventListener('submit', (event) => {
            event.preventDefault();

            const firstName = (createFirstNameInput?.value || '').trim();
            const lastName = (createLastNameInput?.value || '').trim();
            const role = (createRoleInput?.value || 'user').trim();
            const email = (createEmailInput?.value || '').trim();
            const phone = (createPhoneInput?.value || '').trim();
            const employeeRoleSelection = (employeeRoleSelectInput?.value || '').trim();
            const employeeRoleOther = (employeeRoleOtherInput?.value || '').trim();
            const password = createPasswordInput?.value || '';
            const confirmPassword = createConfirmPasswordInput?.value || '';

            if (!firstName || !lastName || !email || !phone || !password || !confirmPassword) {
                return;
            }

            let employeeRole = '';
            if (role === 'employee') {
                if (!employeeRoleSelection) {
                    window.alert('Please select an employee role.');
                    return;
                }

                if (employeeRoleSelection === 'others') {
                    if (!employeeRoleOther) {
                        window.alert('Please specify the employee role.');
                        return;
                    }
                    employeeRole = employeeRoleOther;
                } else {
                    employeeRole = employeeRoleSelection;
                }
            }

            if (password !== confirmPassword) {
                window.alert('Passwords do not match.');
                return;
            }

            const joined = new Date().toLocaleDateString('en-CA');
            const name = `${firstName} ${lastName}`.trim();
            const roleDetails = role === 'employee' && employeeRole ? ` | Role: ${employeeRole}` : '';
            const details = `${email}${roleDetails} | Date Joined: ${joined}`;

            if (modalMode === 'edit' && editAccountId !== null) {
                accounts = accounts.map((account) => {
                    if (account.id !== editAccountId) {
                        return account;
                    }

                    return {
                        ...account,
                        firstName,
                        lastName,
                        name,
                        role,
                        employeeRole,
                        email,
                        phone,
                        details,
                    };
                });
            } else {
                const nextId = accounts.length ? Math.max(...accounts.map((a) => a.id)) + 1 : 1;
                accounts = [{ id: nextId, firstName, lastName, name, role, employeeRole, email, phone, details }, ...accounts];
            }

            saveAccounts();

            const hciDetail = {
                action: modalMode === 'edit' ? 'update_account' : 'create_account',
                source: 'admin-account-tab',
                timestamp: new Date().toISOString(),
                payload: {
                    accountId: editAccountId,
                    name,
                    role,
                    employeeRole,
                    email,
                    phone,
                }
            };

            document.dispatchEvent(new CustomEvent('hci:interaction', { detail: hciDetail }));
            if (window.parent && window.parent !== window) {
                window.parent.postMessage({ type: 'hci:interaction', detail: hciDetail }, '*');
            }

            setModalOpen(false);
            render();
        });

        const togglePasswordVisibility = (input) => {
            if (!input) {
                return;
            }

            input.type = input.type === 'password' ? 'text' : 'password';
        };

        createPasswordToggle?.addEventListener('click', () => togglePasswordVisibility(createPasswordInput));
        createConfirmPasswordToggle?.addEventListener('click', () => togglePasswordVisibility(createConfirmPasswordInput));

        const openAccountFromTarget = (target) => {
            const row = target.closest('[data-open-account]');
            if (!row) {
                return;
            }

            const accountId = row.getAttribute('data-open-account');
            const account = accounts.find((item) => String(item.id) === accountId);
            if (!account) {
                return;
            }

            setModalContext('edit', account);
            fillModal(account);
            setModalOpen(true);
        };

        listEl.addEventListener('click', (event) => {
            const target = event.target;
            if (!(target instanceof HTMLElement)) {
                return;
            }

            const editId = target.getAttribute('data-edit-id');
            if (editId) {
                const account = accounts.find((item) => String(item.id) === editId);
                if (account) {
                    setModalContext('edit', account);
                    fillModal(account);
                    setModalOpen(true);
                }
                return;
            }

            openAccountFromTarget(target);
        });

        listEl.addEventListener('keydown', (event) => {
            if (!(event.target instanceof HTMLElement)) {
                return;
            }

            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                openAccountFromTarget(event.target);
            }
        });

        searchInput.addEventListener('input', render);
        roleFilter.addEventListener('change', render);

        if (!localStorage.getItem(accountsStorageKey)) {
            saveAccounts();
        }

        render();
    });
</script>
