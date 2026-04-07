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
        grid-template-columns: minmax(150px, 1.15fr) minmax(190px, 1.4fr) minmax(140px, 1fr) minmax(92px, 0.7fr) minmax(120px, 0.9fr) 64px;
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

    .account-list-head .head-email,
    .account-list-head .head-phone,
    .account-list-head .head-last-entered {
        text-align: left;
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
        grid-template-columns: minmax(150px, 1.15fr) minmax(190px, 1.4fr) minmax(140px, 1fr) minmax(92px, 0.7fr) minmax(120px, 0.9fr) 64px;
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

    .account-email,
    .account-phone,
    .account-last-entered {
        color: #5e6d8e;
        font-size: 0.82rem;
        line-height: 1.2;
        text-align: left;
    }

    .account-last-entered {
        color: #42557d;
        font-weight: 600;
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

        .account-list-head .head-email,
        .account-list-head .head-phone,
        .account-list-head .head-last-entered,
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
            <span class="head-email">Email</span>
            <span class="head-phone">Phone Number</span>
            <span class="head-users">Users</span>
            <span class="head-last-entered">Last Entered</span>
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

<?php include APPPATH . 'Controllers/admin_function/account_function.php'; ?>
