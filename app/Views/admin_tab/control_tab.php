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

    .control-panel-tools {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .control-panel-count {
        color: #5f6d8b;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .control-list {
        display: grid;
        gap: 8px;
    }

    .control-role-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 6px;
        max-height: 280px;
        overflow: auto;
        padding-right: 2px;
    }

    .control-employee-tools {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 200px;
        gap: 8px;
        margin-bottom: 10px;
    }

    .control-search,
    .control-select {
        min-height: 34px;
        border: 1px solid #cfd8ea;
        border-radius: 10px;
        padding: 0 10px;
        font-family: inherit;
        font-size: 0.84rem;
        color: #2f3e64;
        background: #ffffff;
    }

    .control-bulk-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        flex-wrap: wrap;
    }

    .control-bulk-actions {
        display: inline-flex;
        gap: 6px;
    }

    .control-bulk-btn {
        border: 1px solid #cfd8ea;
        border-radius: 8px;
        background: #ffffff;
        color: #314364;
        font-family: inherit;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 8px;
        cursor: pointer;
    }

    .control-bulk-btn.enable {
        border-color: #b7dfc8;
        color: #0e7f42;
        background: #f0fbf5;
    }

    .control-bulk-btn.disable {
        border-color: #ebc2c2;
        color: #8d2a2a;
        background: #fff5f5;
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

        .control-employee-tools {
            grid-template-columns: 1fr;
        }

        .control-role-grid {
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
                <div class="control-panel-tools">
                    <span class="control-panel-count" id="adminFeaturesCount">0/0 enabled</span>
                    <div class="control-bulk-actions">
                        <button type="button" class="control-bulk-btn enable" data-bulk-scope="adminSections" data-bulk-mode="enable">Enable All</button>
                        <button type="button" class="control-bulk-btn disable" data-bulk-scope="adminSections" data-bulk-mode="disable">Disable All</button>
                    </div>
                </div>
                <div class="control-list" id="adminControlsList"></div>
            </section>

            <section class="control-panel">
                <h4>Admin Access by Employee</h4>
                <div class="control-employee-tools">
                    <input type="search" class="control-search" id="controlEmployeeSearch" placeholder="Search employee name or role">
                    <select class="control-select" id="controlEmployeeRoleFilter">
                        <option value="all">All roles</option>
                    </select>
                </div>
                <div class="control-list" id="adminEmployeeAccessList"></div>
            </section>

            <section class="control-panel">
                <h4>Employee Tab Access</h4>
                <div class="control-panel-tools">
                    <span class="control-panel-count" id="employeeTabsCount">0/0 enabled</span>
                    <div class="control-bulk-actions">
                        <button type="button" class="control-bulk-btn enable" data-bulk-scope="employeeTabs" data-bulk-mode="enable">Enable All</button>
                        <button type="button" class="control-bulk-btn disable" data-bulk-scope="employeeTabs" data-bulk-mode="disable">Disable All</button>
                    </div>
                </div>
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

<?php include APPPATH . 'Models/admin_function/control_function.php'; ?>
