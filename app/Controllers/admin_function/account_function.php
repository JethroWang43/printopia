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

        const extractDateFromDetails = (details) => {
            const match = String(details || '').match(/Date Joined:\s*([0-9]{2,4}-[0-9]{2}-[0-9]{2})/i);
            return match ? match[1] : '';
        };

        const formatDateForDisplay = (value) => {
            const raw = String(value || '').trim();
            if (!raw) {
                return '-';
            }

            const parts = raw.split('-');
            if (parts.length !== 3) {
                return raw;
            }

            if (parts[0].length === 4) {
                return `${parts[1]}-${parts[2]}-${parts[0]}`;
            }

            return raw;
        };

        const getLastEnteredDate = (account) => {
            const explicit = String(account?.lastEntered || '').trim();
            if (explicit) {
                return explicit;
            }

            return extractDateFromDetails(account?.details);
        };

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
                    </div>
                    <div class="account-email">${escapeHtml(account.email || '-')}</div>
                    <div class="account-phone">${escapeHtml(account.phone || '-')}</div>
                    <span class="account-role ${escapeHtml(account.role)}">${escapeHtml(account.role)}</span>
                    <div class="account-last-entered">${escapeHtml(formatDateForDisplay(getLastEnteredDate(account)))}</div>
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
                        lastEntered: joined,
                    };
                });
            } else {
                const nextId = accounts.length ? Math.max(...accounts.map((a) => a.id)) + 1 : 1;
                accounts = [{ id: nextId, firstName, lastName, name, role, employeeRole, email, phone, details, lastEntered: joined }, ...accounts];
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