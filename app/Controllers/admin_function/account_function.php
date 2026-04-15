<script>
    document.addEventListener('DOMContentLoaded', () => {
        const root = document.querySelector('[data-account-tab]');
        if (!root) {
            return;
        }

        const API_BASE = '<?php echo base_url('admin/account'); ?>';
        const API_LIST = `${API_BASE}/list`;
        const API_SAVE = `${API_BASE}/save`;
        const API_DELETE_BASE = `${API_BASE}/delete`;

        const listEl = document.querySelector('#accountList');
        const searchInput = document.querySelector('#accountSearchInput');
        const roleFilter = document.querySelector('#accountRoleFilter');
        const addBtn = document.querySelector('#accountAddBtn');
        const createModal = document.querySelector('#accountCreateModal');
        const viewModal = document.querySelector('#accountViewModal');
        const createForm = document.querySelector('#accountCreateForm');
        const createCloseBtn = document.querySelector('#accountCreateClose');
        const viewCloseBtn = document.querySelector('#accountViewClose');
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
        const viewNameEl = document.querySelector('#accountViewName');
        const viewEmailEl = document.querySelector('#accountViewEmail');
        const viewPhoneEl = document.querySelector('#accountViewPhone');
        const viewLastEnteredEl = document.querySelector('#accountViewLastEntered');
        const deleteBtn = document.createElement('button');
        deleteBtn.type = 'button';
        deleteBtn.id = 'accountDeleteBtn';
        deleteBtn.className = 'account-danger-btn';
        deleteBtn.textContent = 'Delete Account →';
        deleteBtn.style.display = 'none';

        let editAccountId = null;
        let modalMode = 'create';
        let accounts = [];

        /**
         * Load accounts from Supabase API
         */
        const loadAccounts = async () => {
            try {
                const response = await fetch(API_LIST);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const result = await response.json();
                
                if (result.success && Array.isArray(result.data)) {
                    // Map database fields to UI model
                    accounts = result.data.map(user => ({
                        id: user.user_id,
                        user_id: user.user_id,
                        firstName: user.first_name || '',
                        lastName: user.last_name || '',
                        name: `${user.first_name} ${user.last_name}`.trim(),
                        email: user.email || '',
                        phone: user.phone_number || '',
                        role: user.role_id || 'user',
                        middlewareName: user.middle_name || '',
                        password: user.password || '',
                        dateCreated: user.date_created || '',
                        dateUpdated: user.date_updated || '',
                        lastEntered: user.last_entered || user.date_updated || '',
                    }));
                    render();
                } else {
                    showErrorMessage('Failed to load accounts: Invalid response format');
                }
            } catch (error) {
                log_message('error', `Account load error: ${error.message}`);
                showErrorMessage(`Failed to load accounts: ${error.message}`);
            }
        };

        const showErrorMessage = (message) => {
            if (listEl) {
                listEl.innerHTML = `<div class="account-empty" style="color: #d32f2f;">${message}</div>`;
            }
        };

        /**
         * Delete account with confirmation
         */
        const deleteAccount = async (accountId) => {
            if (!accountId) return;

            const confirmed = confirm('Are you sure you want to delete this account? This action cannot be undone.');
            if (!confirmed) return;

            try {
                const response = await fetch(`${API_DELETE_BASE}/${accountId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || `HTTP error! status: ${response.status}`);
                }

                if (result.success) {
                    accounts = accounts.filter(acc => acc.id !== accountId);
                    setModalOpen(false);
                    render();
                    
                    const hciDetail = {
                        action: 'delete_account',
                        source: 'admin-account-tab',
                        timestamp: new Date().toISOString(),
                        payload: { accountId }
                    };
                    document.dispatchEvent(new CustomEvent('hci:interaction', { detail: hciDetail }));
                } else {
                    alert(`Failed to delete account: ${result.message}`);
                }
            } catch (error) {
                alert(`Error deleting account: ${error.message}`);
            }
        };

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

            // Show/hide delete button based on mode
            if (deleteBtn) {
                deleteBtn.style.display = mode === 'edit' ? '' : 'none';
            }
        };

        const fillModal = (account) => {
            const firstName = account?.firstName || '';
            const lastName = account?.lastName || '';
            const email = account?.email || '';
            const phone = account?.phone || '';
            const role = account?.role || 'user';
            const middleName = account?.middlewareName || '';

            if (createFirstNameInput) createFirstNameInput.value = firstName;
            if (createLastNameInput) createLastNameInput.value = lastName;
            if (createEmailInput) createEmailInput.value = email;
            if (createPhoneInput) createPhoneInput.value = phone;
            if (createRoleInput) createRoleInput.value = role;

            // For now, we'll assume role_id values map to 'user' or 'employee'
            // This can be enhanced based on actual role_id values in the database

            if (employeeRoleSelectInput) {
                employeeRoleSelectInput.value = '';
                if (employeeRoleOtherInput) {
                    employeeRoleOtherInput.value = '';
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

        const setViewModalOpen = (isOpen) => {
            if (!viewModal) {
                return;
            }

            viewModal.classList.toggle('show', isOpen);
            viewModal.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
        };

        const escapeHtml = (value) => String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#39;');

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

        const render = () => {
            const query = (searchInput.value || '').trim().toLowerCase();
            const selectedRole = roleFilter.value;

            const filtered = accounts.filter((account) => {
                const matchesQuery = !query ||
                    account.name.toLowerCase().includes(query) ||
                    account.email.toLowerCase().includes(query);
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
                    <div class="account-last-entered">${escapeHtml(formatDateForDisplay(account.lastEntered || ''))}</div>
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

        viewCloseBtn?.addEventListener('click', () => setViewModalOpen(false));
        viewModal?.addEventListener('click', (event) => {
            if (event.target === viewModal) {
                setViewModalOpen(false);
            }
        });

        createForm?.addEventListener('submit', async (event) => {
            event.preventDefault();

            const firstName = (createFirstNameInput?.value || '').trim();
            const lastName = (createLastNameInput?.value || '').trim();
            const email = (createEmailInput?.value || '').trim();
            const phone = (createPhoneInput?.value || '').trim();
            const role = (createRoleInput?.value || 'user').trim();
            const password = createPasswordInput?.value || '';
            const confirmPassword = createConfirmPasswordInput?.value || '';

            if (!firstName || !lastName || !email || !phone) {
                alert('Please fill in all required fields.');
                return;
            }

            // Only require password for new accounts
            if (!editAccountId && (!password || !confirmPassword)) {
                alert('Please enter a password for new accounts.');
                return;
            }

            if (password && password !== confirmPassword) {
                alert('Passwords do not match.');
                return;
            }

            try {
                const payload = {
                    first_name: firstName,
                    last_name: lastName,
                    email: email,
                    phone_number: phone,
                    role_id: role,
                };

                if (password) {
                    payload.password = password;
                }

                if (editAccountId) {
                    payload.user_id = editAccountId;
                }

                const response = await fetch(API_SAVE, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(payload),
                });

                const result = await response.json();

                if (!response.ok) {
                    const errorMessage = result.message || 'Failed to save account';
                    const errorDetails = result.errors ? ` - ${result.errors.join(', ')}` : '';
                    const errorDetail = result.error ? ` - ${result.error}` : '';
                    alert(`${errorMessage}${errorDetails}${errorDetail}`);
                    return;
                }

                if (result.success) {
                    const hciDetail = {
                        action: editAccountId ? 'update_account' : 'create_account',
                        source: 'admin-account-tab',
                        timestamp: new Date().toISOString(),
                        payload: {
                            accountId: editAccountId,
                            firstName,
                            lastName,
                            email,
                            phone,
                            role,
                        }
                    };

                    document.dispatchEvent(new CustomEvent('hci:interaction', { detail: hciDetail }));

                    setModalOpen(false);
                    await loadAccounts();
                } else {
                    alert(`Failed to save account: ${result.message}`);
                }
            } catch (error) {
                alert(`Error saving account: ${error.message}`);
            }
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

            if (viewNameEl) viewNameEl.textContent = account.name || '-';
            if (viewEmailEl) viewEmailEl.textContent = account.email || '-';
            if (viewPhoneEl) viewPhoneEl.textContent = account.phone || '-';
            if (viewLastEnteredEl) viewLastEnteredEl.textContent = formatDateForDisplay(account.lastEntered || '');
            setViewModalOpen(true);
        };

        listEl.addEventListener('click', (event) => {
            const target = event.target;
            if (!(target instanceof HTMLElement)) {
                return;
            }

            // Handle delete button
            if (target.id === 'accountDeleteBtn') {
                event.preventDefault();
                event.stopPropagation();
                deleteAccount(editAccountId);
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

        // Add delete button to modal actions
        const modalActions = document.querySelector('.account-modal-actions');
        if (modalActions) {
            modalActions.insertBefore(deleteBtn, modalActions.firstChild);
        }

        // Set delete button click handler
        deleteBtn.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            deleteAccount(editAccountId);
        });

        // Initial load
        loadAccounts();
    });
</script>