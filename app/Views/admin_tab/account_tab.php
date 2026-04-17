<style>
    .account-tab {
        background: #ffffff;
        border: 1px solid #d6dfed;
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(16, 34, 79, 0.06);
        padding: 14px;
        min-height: 620px;
    }

    .account-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 14px;
        padding: 4px 2px;
    }

    .account-topbar h3 {
        margin: 0;
        color: #1a2847;
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: -0.3px;
    }

    .account-add-btn {
        min-height: 36px;
        border: 1px solid #8a1c1c;
        border-radius: 8px;
        background: linear-gradient(180deg, #a82424 0%, #7f1512 100%);
        color: #ffffff;
        padding: 0 14px;
        font-family: inherit;
        font-size: 0.84rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.15s ease;
    }

    .account-add-btn:hover {
        background: linear-gradient(180deg, #b82c2c 0%, #8f1f19 100%);
        box-shadow: 0 6px 12px rgba(168, 36, 36, 0.25);
        transform: translateY(-1px);
    }

    .account-toolbar {
        border: 1px solid #5ea5f6;
        border-radius: 8px;
        padding: 10px;
        display: grid;
        grid-template-columns: minmax(220px, 1fr) 180px;
        gap: 10px;
        margin-bottom: 14px;
        background: linear-gradient(135deg, #f9fbff 0%, #f5f8ff 100%);
    }

    .account-search,
    .account-filter {
        min-height: 32px;
        border: 1px solid #b4bfd4;
        border-radius: 16px;
        background: #ffffff;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0 12px;
        color: #4e5d7d;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.15s ease;
    }

    .account-search:focus-within,
    .account-filter:focus-within {
        border-color: #5ea5f6;
        box-shadow: 0 0 0 3px rgba(94, 165, 246, 0.1);
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

    .account-filter select {
        cursor: pointer;
    }

    .account-list {
        display: grid;
        gap: 8px;
    }

    .account-pagination {
        margin-top: 12px;
        border: 1px solid #d0dcea;
        border-radius: 10px;
        padding: 10px 12px;
        background: linear-gradient(135deg, #f9fbff 0%, #f4f8ff 100%);
        display: none;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
    }

    .account-pagination-summary {
        color: #4f6288;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .account-pagination-controls {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .account-pagination-btn {
        min-height: 30px;
        border: 1px solid #cfd8ea;
        border-radius: 8px;
        background: #ffffff;
        color: #334667;
        font-family: inherit;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 0 10px;
        cursor: pointer;
    }

    .account-pagination-btn:disabled {
        opacity: 0.55;
        cursor: not-allowed;
    }

    .account-pagination-info {
        color: #3d5480;
        font-size: 0.78rem;
        font-weight: 700;
        min-width: 86px;
        text-align: center;
    }

    .account-pagination-size {
        min-height: 30px;
        border: 1px solid #cfd8ea;
        border-radius: 8px;
        background: #ffffff;
        color: #334667;
        font-family: inherit;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 0 8px;
    }

    .account-list-head {
        border: 1px solid #d0dcea;
        border-radius: 10px;
        background: linear-gradient(135deg, #f5f8ff 0%, #eef2fb 100%);
        padding: 10px 14px;
        display: grid;
        grid-template-columns: 1fr 1.2fr 1fr 1fr 44px;
        align-items: center;
        gap: 14px;
        margin-bottom: 10px;
    }

    .account-list-head .head-name {
        text-align: left;
    }

    .account-list-head span {
        color: #3d5480;
        font-size: 0.77rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
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
        border-radius: 10px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        padding: 12px 14px;
        display: grid;
        grid-template-columns: 1fr 1.2fr 1fr 1fr 44px;
        align-items: center;
        gap: 14px;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .account-item > div {
        text-align: left;
    }

    .account-item:hover {
        border-color: #b4c7e8;
        background: linear-gradient(135deg, #ffffff 0%, #e8f0ff 100%);
        box-shadow: 0 12px 24px rgba(16, 34, 79, 0.1), 0 0 0 1px rgba(88, 130, 220, 0.2);
        transform: translateY(-2px);
    }

    .account-item strong {
        display: block;
        color: #1a2847;
        font-size: 0.98rem;
        font-weight: 600;
        line-height: 1.3;
        letter-spacing: -0.3px;
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
        color: #556b85;
        font-size: 0.85rem;
        line-height: 1.25;
        text-align: left;
        font-weight: 500;
    }

    .account-email {
        color: #5a7ba8;
    }

    .account-phone {
        color: #5f6d8a;
    }

    .account-last-entered {
        color: #3d5480;
        font-weight: 600;
        font-size: 0.84rem;
        text-align: left;
    }

    .account-role {
        display: none;
        border-radius: 999px;
        padding: 3px 12px;
        color: #fff;
        font-size: 0.7rem;
        font-weight: 700;
        line-height: 1.4;
        text-transform: capitalize;
        justify-self: center;
    }

    .account-role.employee {
        background: linear-gradient(180deg, #d59312 0%, #c68510 100%);
    }

    .account-role.user {
        background: linear-gradient(180deg, #22c55e 0%, #16a34a 100%);
    }

    .account-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 24, 44, 0.55);
        z-index: 1300;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 14px;
        backdrop-filter: blur(2px);
    }

    .account-modal-backdrop.show {
        display: flex;
    }

    .account-modal {
        width: min(520px, 96vw);
        border: 1px solid #dce4f3;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 24px 48px rgba(16, 34, 79, 0.22), 0 0 1px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        animation: modalSlideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes modalSlideUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .account-modal-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 16px 18px;
        border-bottom: 1px solid #eaeffb;
        background: linear-gradient(135deg, #f7f9ff 0%, #f0f4fc 100%);
    }

    .account-modal-head h4 {
        margin: 0;
        color: #1a2847;
        font-size: 1.04rem;
        font-weight: 700;
        letter-spacing: -0.2px;
    }

    .account-modal-head small {
        display: block;
        margin-top: 3px;
        color: #667691;
        font-size: 0.77rem;
        font-weight: 500;
    }

    .account-modal-close {
        width: 32px;
        height: 32px;
        border: 1px solid #d8dfe9;
        border-radius: 8px;
        background: linear-gradient(180deg, #ffffff 0%, #f8f9fb 100%);
        color: #3d5480;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .account-modal-close:hover {
        border-color: #c8d4e6;
        background: linear-gradient(180deg, #eef2fb 0%, #e5ecf8 100%);
        box-shadow: 0 4px 10px rgba(88, 130, 220, 0.15);
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
        color: #3d5480;
        font-size: 0.77rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .account-form-field input,
    .account-form-field select {
        min-height: 38px;
        border: 1px solid #d0daea;
        border-radius: 8px;
        padding: 0 12px;
        font-family: inherit;
        font-size: 0.86rem;
        color: #2f3e64;
        transition: all 0.15s ease;
    }

    .account-form-field input:focus,
    .account-form-field select:focus {
        outline: none;
        border-color: #5ea5f6;
        box-shadow: 0 0 0 3px rgba(94, 165, 246, 0.1);
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
    }

    .account-modal-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-top: 8px;
    }

    .account-modal-actions button {
        min-height: 36px;
        border-radius: 8px;
        border: 1px solid #cfd8ea;
        padding: 0 14px;
        font-family: inherit;
        font-size: 0.82rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .account-modal-cancel {
        background: linear-gradient(180deg, #f5f7fd 0%, #eff3fa 100%);
        color: #4a5d7a;
        border-color: #d8dfe9;
    }

    .account-modal-cancel:hover {
        border-color: #c8d4e6;
        background: linear-gradient(180deg, #eff3fa 0%, #e8edf7 100%);
        box-shadow: 0 4px 8px rgba(16, 34, 79, 0.08);
    }

    .account-modal-save {
        border-color: #8a1c1c;
        background: linear-gradient(180deg, #a82424 0%, #7f1512 100%);
        color: #ffffff;
    }

    .account-modal-save:hover {
        background: linear-gradient(180deg, #b82c2c 0%, #8f1f19 100%);
        border-color: #9a2222;
        box-shadow: 0 6px 12px rgba(168, 36, 36, 0.2);
        transform: translateY(-1px);
    }

    .account-modal-save.update-mode {
        background: linear-gradient(180deg, #8a1c1c 0%, #6f1410 100%);
    }

    .account-modal-save.update-mode:hover {
        background: linear-gradient(180deg, #9a2222 0%, #7f1812 100%);
    }

    .account-edit-btn {
        width: 36px;
        height: 36px;
        border: 1px solid #c8d4e6;
        border-radius: 8px;
        background: linear-gradient(180deg, #ffffff 0%, #f8f9fb 100%);
        color: #3d5480;
        font-size: 0.95rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }

    .account-edit-btn:hover {
        border-color: #b4c7e8;
        background: linear-gradient(180deg, #eef2fb 0%, #e5ecf8 100%);
        color: #2a4680;
        box-shadow: 0 4px 10px rgba(88, 130, 220, 0.15);
    }

    .account-danger-btn {
        min-height: 36px;
        border: 1px solid #f0c5d9;
        border-radius: 8px;
        background: linear-gradient(180deg, #ffe8f1 0%, #ffdae6 100%);
        color: #c23e4a;
        padding: 0 14px;
        font-family: inherit;
        font-size: 0.82rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .account-danger-btn:hover {
        border-color: #e8a5bb;
        background: linear-gradient(180deg, #ffd4e8 0%, #ffc5d7 100%);
        color: #a8293b;
        box-shadow: 0 6px 12px rgba(211, 47, 47, 0.18);
        transform: translateY(-1px);
    }

    .account-empty {
        border: 2px dashed #d0dcea;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        color: #667691;
        font-size: 0.88rem;
        background: linear-gradient(135deg, #f9fbff 0%, #f5f8ff 100%);
    }

    .account-view-modal {
        width: min(440px, 96vw);
        border: 1px solid #dce4f3;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 24px 48px rgba(16, 34, 79, 0.22), 0 0 1px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .account-view-body {
        padding: 14px;
        display: grid;
        gap: 10px;
    }

    .account-view-row {
        display: grid;
        gap: 4px;
        border: 1px solid #e5ebf7;
        border-radius: 10px;
        padding: 10px;
        background: #f8fbff;
    }

    .account-view-row label {
        color: #5a6c8f;
        font-size: 0.74rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .account-view-row span {
        color: #1f3158;
        font-size: 0.9rem;
        font-weight: 600;
        word-break: break-word;
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
            <span class="head-last-entered">Last Entered</span>
            <span class="head-edit">Edit</span>
        </div>

        <div class="account-list" id="accountList"></div>

        <div class="account-pagination" id="accountPagination" aria-label="Account list pagination">
            <div class="account-pagination-summary" id="accountPaginationSummary">Showing 0-0 of 0 accounts</div>
            <div class="account-pagination-controls">
                <select class="account-pagination-size" id="accountPaginationPageSize" aria-label="Accounts per page">
                    <option value="5">5 / page</option>
                    <option value="8" selected>8 / page</option>
                    <option value="10">10 / page</option>
                    <option value="20">20 / page</option>
                </select>
                <button type="button" class="account-pagination-btn" id="accountPaginationPrev">Previous</button>
                <span class="account-pagination-info" id="accountPaginationPageInfo">Page 1 of 1</span>
                <button type="button" class="account-pagination-btn" id="accountPaginationNext">Next</button>
            </div>
        </div>

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

        <div class="account-modal-backdrop" id="accountViewModal" aria-hidden="true">
            <article class="account-view-modal" role="dialog" aria-labelledby="accountViewTitle">
                <div class="account-modal-head">
                    <div>
                        <h4 id="accountViewTitle">Account Overview</h4>
                        <small>Read-only details for this account.</small>
                    </div>
                    <button type="button" class="account-modal-close" id="accountViewClose" aria-label="Close account overview">✕</button>
                </div>

                <div class="account-view-body">
                    <div class="account-view-row">
                        <label>Name</label>
                        <span id="accountViewName">-</span>
                    </div>
                    <div class="account-view-row">
                        <label>Email</label>
                        <span id="accountViewEmail">-</span>
                    </div>
                    <div class="account-view-row">
                        <label>Phone Number</label>
                        <span id="accountViewPhone">-</span>
                    </div>
                    <div class="account-view-row">
                        <label>Last Entered</label>
                        <span id="accountViewLastEntered">-</span>
                    </div>
                    <div class="account-view-row" id="accountViewEmployeeRoleRow" style="display:none;">
                        <label>Employee Role</label>
                        <span id="accountViewEmployeeRole">-</span>
                    </div>
                </div>
            </article>
        </div>
    </section>
</article>

<?php include APPPATH . 'Controllers/admin_function/account_function.php'; ?>
