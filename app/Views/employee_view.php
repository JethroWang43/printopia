<?= view('include/head_view', ['title' => $title]); ?>

    <style>
        .dashboard {
            flex: 1 0 auto;
            padding: 28px 0 38px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 24px;
            align-items: start;
        }

        .sidebar,
        .card,
        .panel {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            box-shadow: var(--shadow);
        }

        .sidebar {
            padding: 18px 16px;
            position: sticky;
            top: 96px;
        }

        .profile {
            border-bottom: 1px solid #edf2f8;
            padding: 8px 8px 14px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .profile-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 2px solid #dbe4f4;
            display: grid;
            place-items: center;
            font-size: 1.2rem;
            color: #21356d;
            background: linear-gradient(160deg, #f7faff 0%, #e9eef8 100%);
        }

        .profile h4 {
            margin: 0 0 6px;
            color: #1f2f63;
            font-size: 1.16rem;
        }

        .profile .badge {
            display: inline-block;
            font-size: 0.74rem;
            font-weight: 700;
            color: #ffffff;
            background: linear-gradient(180deg, #dca218 0%, #be870c 100%);
            padding: 4px 10px;
            border-radius: 999px;
        }

        .menu {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 6px;
        }

        .menu li a {
            display: block;
            border-radius: 8px;
            padding: 11px 12px;
            color: #2f3857;
            font-weight: 600;
            font-size: 0.95rem;
            border-left: 3px solid transparent;
            transition: all 0.16s ease;
        }

        .menu li a.active {
            background: linear-gradient(90deg, #e8f1ff 0%, #f2f6ff 100%);
            color: #152a5e;
            border-left-color: #1e91d6;
            border-color: #dde6f7;
            box-shadow: inset 0 2px 8px rgba(30, 145, 214, 0.08);
        }

        .menu li a:hover {
            background: #f2f6ff;
            color: #152a5e;
            border-color: #dde6f7;
            border-left-color: #b8d7f5;
            transform: translateX(2px);
        }

        .employee-picker {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #edf2f8;
        }

        .employee-picker label {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            color: #4f5d7f;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .employee-picker select {
            width: 100%;
            border: 1px solid #d7e0f1;
            border-radius: 8px;
            padding: 8px 10px;
            font-family: inherit;
            font-size: 0.88rem;
            color: #24345f;
            background: #fff;
        }

        .content {
            display: grid;
            gap: 14px;
        }

        .tab-section {
            display: none;
            gap: 14px;
        }

        .tab-section.active {
            display: grid;
        }

        .task-frame-panel {
            background: transparent;
            border: 0;
            border-radius: 0;
            padding: 0;
            box-shadow: none;
        }

        .task-frame-panel h3 {
            margin: 0 0 8px;
            color: #1f2430;
            font-size: 1.5rem;
        }

        .task-embed {
            width: 100%;
            min-height: 680px;
            height: 78vh;
            border: 1px solid #d6dfef;
            border-radius: 12px;
            background: #ffffff;
            display: block;
            box-shadow: 0 12px 22px rgba(16, 34, 79, 0.08);
        }

        .panel-empty {
            padding: 26px 18px;
            border: 1px dashed #d8e2f3;
            border-radius: 10px;
            color: #64708a;
            font-size: 0.9rem;
            text-align: center;
            background: #f9fbff;
        }

        .status-msg {
            font-size: 0.82rem;
            color: #5d6b88;
            margin-top: 8px;
            line-height: 1.4;
        }

        .status-msg strong {
            color: #1f2f63;
        }

        .heading {
            margin: 0;
            color: #2b3346;
            font-size: 2.2rem;
            letter-spacing: 0.2px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            max-width: 1040px;
        }

        .card {
            padding: 16px 18px;
            border-color: #e8edf8;
            box-shadow: var(--soft-shadow);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: "";
            position: absolute;
            inset: 0;
            border-top: 3px solid transparent;
        }

        .card.task::before {
            border-top-color: #f0b0a8;
        }

        .card.completed::before {
            border-top-color: #86d39a;
        }

        .card.notification::before {
            border-top-color: #f3c972;
        }

        .card h5 {
            margin: 0;
            color: #596079;
            font-size: 0.93rem;
            font-weight: 600;
        }

        .card strong {
            display: block;
            margin-top: 4px;
            color: #29355d;
            font-size: 2.2rem;
            line-height: 1;
        }

        .card .icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: 1.4rem;
            color: #2a3150;
            background: #f4f7fd;
            border: 1px solid #e2e9f7;
        }

        .panels {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .panel {
            padding: 18px;
            min-height: 340px;
        }

        .panel h3 {
            margin: 0 0 14px;
            color: #2a3348;
            font-size: 1.15rem;
            letter-spacing: 0.2px;
        }

        .task-list {
            display: grid;
            gap: 12px;
        }

        .task-item {
            border: 1px solid #e8edf7;
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            background: linear-gradient(180deg, #fbfdff 0%, #f6f9ff 100%);
            transition: transform 0.16s ease, box-shadow 0.16s ease;
        }

        .task-item:hover {
            transform: translateY(-1px);
            box-shadow: var(--soft-shadow);
        }

        .task-button {
            width: 100%;
            border: 0;
            text-align: left;
            font-family: inherit;
            cursor: pointer;
            color: inherit;
        }

        .employee-task-modal {
            position: fixed;
            inset: 0;
            background: rgba(18, 28, 48, 0.55);
            z-index: 120;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
        }

        .employee-task-modal.show {
            display: flex;
        }

        .employee-task-modal-card {
            width: min(780px, 96vw);
            max-height: 85vh;
            overflow: auto;
            background: #fff;
            border-radius: 14px;
            border: 1px solid #dfe7f5;
            box-shadow: 0 20px 42px rgba(16, 34, 79, 0.24);
        }

        .employee-task-modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 14px 16px;
            background: #0f1b35;
            color: #fff;
            border-top-left-radius: 14px;
            border-top-right-radius: 14px;
        }

        .employee-task-modal-head h4 {
            margin: 0;
            font-size: 1.12rem;
        }

        .employee-task-modal-close {
            border: 0;
            background: transparent;
            color: #fff;
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
        }

        .employee-task-modal-body {
            padding: 14px 16px 18px;
        }

        .employee-task-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 12px;
            color: #2a3553;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .employee-task-box {
            border: 1px solid #e2e9f5;
            border-radius: 10px;
            background: #f9fbff;
            padding: 12px;
            white-space: pre-wrap;
            line-height: 1.55;
            color: #263251;
            font-size: 0.9rem;
            margin-bottom: 12px;
        }

        #employeeChecklistContainer {
            display: flex;
            flex-direction: column;
            gap: 6px;
            padding: 10px;
            white-space: normal;
            line-height: 1.25;
        }

        .employee-task-checklist-item {
            display: grid;
            grid-template-columns: 18px minmax(0, 1fr) auto;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            color: #2a3552;
            font-size: 0.88rem;
            border-radius: 10px;
            border: 1px solid #e4ebf7;
            background: linear-gradient(180deg, #ffffff 0%, #f7faff 100%);
            margin-bottom: 0;
            transition: transform 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease, color 0.18s ease, border-color 0.18s ease;
        }

        .employee-task-checklist-item[data-is-assigned="yes"] {
            cursor: pointer;
        }

        .employee-task-checklist-item[data-is-assigned="yes"]:hover {
            background: linear-gradient(180deg, #f9fcff 0%, #edf5ff 100%);
            border-color: #c7dbf7;
            color: #1d66c2;
            box-shadow: 0 10px 18px rgba(26, 60, 117, 0.08);
            transform: translateY(-1px);
        }

        .employee-task-checklist-item.done {
            border-color: rgba(47, 179, 68, 0.22);
            background: linear-gradient(180deg, #f3fbf4 0%, #eaf8ed 100%);
        }

        .employee-task-checklist-item.done .employee-task-checklist-text {
            text-decoration: line-through;
            color: #4d8b59;
        }

        .employee-task-checklist-item[data-is-assigned="yes"].done:hover {
            background: linear-gradient(180deg, #eef8f0 0%, #e1f3e5 100%);
            border-color: rgba(47, 179, 68, 0.28);
            color: #2f7a3d;
            box-shadow: 0 10px 18px rgba(47, 179, 68, 0.08);
            transform: translateY(-1px);
        }

        .employee-task-checklist-mark {
            width: 18px;
            text-align: center;
            font-size: 1rem;
            line-height: 1;
            flex: 0 0 auto;
        }

        .employee-task-checklist-text {
            min-width: 0;
            line-height: 1.4;
        }

        .employee-task-checklist-by {
            white-space: nowrap;
            margin-left: 8px;
            color: #6a748d;
            font-size: 0.8rem;
            text-decoration: none;
        }

        .task-meta {
            color: #65708a;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .task-name {
            margin-top: 2px;
            color: #2b344d;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .tag {
            display: inline-block;
            border-radius: 999px;
            padding: 6px 14px;
            color: #ffffff;
            font-size: 0.74rem;
            font-weight: 700;
            min-width: 74px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
        }

        .tag.pending {
            background: linear-gradient(180deg, #f4b324 0%, #d89209 100%);
            box-shadow: 0 4px 10px rgba(212, 146, 9, 0.3);
        }

        .tag.todo {
            background: var(--danger);
        }

        .tag.done {
            background: linear-gradient(180deg, #2fb344 0%, #208637 100%);
            box-shadow: 0 4px 10px rgba(47, 179, 68, 0.28);
        }

        .site-footer {
            margin-top: auto;
            background: var(--brand-gold);
            border-top: 1px solid #cc9516;
            color: #172a5d;
            padding: 40px 0 18px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1.2fr;
            gap: 34px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.3rem;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .site-footer h5 {
            margin: 0 0 12px;
            font-size: 1.08rem;
        }

        .site-footer p,
        .site-footer li {
            margin: 0;
            font-size: 0.92rem;
            line-height: 1.65;
        }

        .site-footer ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 7px;
        }

        .footer-bottom {
            margin-top: 24px;
            padding-top: 14px;
            border-top: 1px solid rgba(16, 34, 79, 0.35);
            text-align: center;
            font-size: 0.88rem;
            font-weight: 600;
        }

        @media (max-width: 1180px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
            }

            .panels {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 900px) {
            .topbar .container,
            .site-footer .container {
                padding-left: 14px;
                padding-right: 14px;
            }

            .topbar-inner {
                flex-wrap: wrap;
                min-height: auto;
                padding: 14px 0;
            }

            .main-nav {
                width: 100%;
                flex-wrap: wrap;
                gap: 16px;
                justify-content: center;
            }

            .logo,
            .user-menu {
                flex: 0 0 auto;
                margin-left: 0;
            }

            .stats {
                grid-template-columns: 1fr;
                max-width: 100%;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <?= view('include/nav_view', ['activePage' => '', 'userLabel' => 'Employee User']); ?>

    <main class="dashboard">
        <div class="container dashboard-grid">
            <aside class="sidebar">
                <div class="profile">
                    <div class="profile-avatar">◉</div>
                    <div>
                        <h4>Employee User</h4>
                        <span class="badge">Employee</span>
                    </div>
                </div>

                <ul class="menu" id="employeeTabMenu">
                    <li><a href="#" data-tab="overview" class="active">Dashboard Overview</a></li>
                    <li><a href="#" data-tab="tasks">Task</a></li>
                    <li><a href="#" data-tab="notifications">Notification</a></li>
                    <li><a href="#" data-tab="notification-management" style="display:none;">Notification Management</a></li>
                    <li><a href="#" data-tab="calendar-management" style="display:none;">Calendar</a></li>
                    <li><a href="#" data-tab="gallery-management" style="display:none;">Gallery Management</a></li>
                    <li><a href="#" data-tab="order-management" style="display:none;">Order Management</a></li>
                    <li><a href="#" data-tab="inventory-management" style="display:none;">Inventory Management</a></li>
                    <li><a href="#" data-tab="account-management" style="display:none;">Account Management</a></li>
                    <li><a href="#" data-tab="task-management" style="display:none;">Task Management</a></li>
                    <li><a href="#" data-tab="discount-management" style="display:none;">Discount Management</a></li>
                    <li><a href="#" data-tab="control-management" style="display:none;">Control Management</a></li>
                </ul>

                <div class="employee-picker">
                    <label for="employeeFilter">Employee</label>
                    <select id="employeeFilter" disabled>
                        <option value="">Loading employees...</option>
                    </select>
                    <div class="status-msg" id="employeeStatusMsg">Loading tasks from Trello...</div>
                </div>
            </aside>

            <section class="content">
                <div class="tab-section active" id="tab-overview">
                    <h2 class="heading">Dashboard Overview</h2>

                    <div class="stats">
                        <article class="card task">
                            <div>
                                <h5>Task</h5>
                                <strong id="taskCount">0</strong>
                            </div>
                            <span class="icon">✎</span>
                        </article>

                        <article class="card completed">
                            <div>
                                <h5>Task Completed</h5>
                                <strong id="completedTaskCount">0</strong>
                            </div>
                            <span class="icon">✔</span>
                        </article>

                        <article class="card notification">
                            <div>
                                <h5>Notifications</h5>
                                <strong id="notificationCount">0</strong>
                            </div>
                            <span class="icon">🔔</span>
                        </article>
                    </div>

                    <div class="panels">
                        <article class="panel">
                            <h3 id="pendingTaskHeading">Pending Task (0)</h3>
                            <div class="task-list" id="pendingTaskList">
                                <div class="panel-empty">Loading Trello tasks...</div>
                            </div>
                        </article>

                        <article class="panel">
                            <h3 id="completedTaskHeading">Completed Task (0)</h3>
                            <div class="task-list" id="doneTaskList">
                                <div class="panel-empty">Loading completed tasks...</div>
                            </div>
                        </article>

                    </div>
                </div>

                <div class="tab-section" id="tab-tasks">
                    <h2 class="heading">Task</h2>
                    <article class="panel">
                        <h3>My Trello Tasks</h3>
                        <div class="task-list" id="allTaskList">
                            <div class="panel-empty">Loading Trello tasks...</div>
                        </div>
                    </article>
                </div>

                <div class="tab-section" id="tab-notifications">
                    <h2 class="heading">Notification</h2>
                    <article class="panel">
                        <h3>Task Alerts</h3>
                        <div class="task-list" id="notificationList">
                            <div class="panel-empty">Loading notifications...</div>
                        </div>
                    </article>
                </div>

                <div class="tab-section" id="tab-calendar-management">
                    <?= view('admin_tab/calendar_tab'); ?>
                </div>

                <div class="tab-section" id="tab-notification-management">
                    <?= view('admin_tab/notification_tab'); ?>
                </div>

                <div class="tab-section" id="tab-gallery-management">
                    <?= view('admin_tab/gallery_tab'); ?>
                </div>

                <div class="tab-section" id="tab-order-management">
                    <?= view('admin_tab/order_tab'); ?>
                </div>

                <div class="tab-section" id="tab-inventory-management">
                    <?= view('admin_tab/inventory_tab'); ?>
                </div>

                <div class="tab-section" id="tab-account-management">
                    <?= view('admin_tab/account_tab'); ?>
                </div>

                <div class="tab-section" id="tab-task-management">
                    <?= view('admin_tab/task_tab'); ?>
                </div>

                <div class="tab-section" id="tab-discount-management">
                    <?= view('admin_tab/discount_tab'); ?>
                </div>

                <div class="tab-section" id="tab-control-management">
                    <?= view('admin_tab/control_tab'); ?>
                </div>

            </section>
        </div>
    </main>

    <?= view('include/foot_view'); ?>

    <div class="employee-task-modal" id="employeeTaskModal" aria-hidden="true">
        <div class="employee-task-modal-card">
            <div class="employee-task-modal-head">
                <h4 id="employeeTaskModalTitle">Task Details</h4>
                <button type="button" class="employee-task-modal-close" id="employeeTaskModalClose" aria-label="Close">×</button>
            </div>
            <div class="employee-task-modal-body" id="employeeTaskModalBody"></div>
        </div>
    </div>

    <script>
        const TRELLO_PROXY_URL = <?= json_encode(base_url('trello-task/proxy')); ?>;
        const EMPLOYEE_LIST_URL = <?= json_encode(base_url('admin/account/list')); ?>;
        const CHECKED_BY_STORAGE_KEY = 'checklistCheckedBy';
        const CONTROL_SETTINGS_KEY = 'printopiaControlSettingsV1';
        const EMPLOYEE_TASK_CACHE_KEY = 'printopiaEmployeeTaskCacheV1';
        const EMPLOYEE_TASK_CACHE_MAX_AGE_MS = 3 * 60 * 1000;

        const state = {
            selectedEmployee: '',
            tasks: []
        };

        let employeeAccounts = [];

        function normalizeEmployeeName(value) {
            return normalize(value).replace(/[^a-z0-9]+/g, ' ').replace(/\s+/g, ' ').trim();
        }

        function normalizeEmployeeRole(role, customRole = '') {
            const normalizedRole = normalize(role);
            const normalizedCustomRole = String(customRole || '').trim();

            if (!normalizedRole) {
                return 'Employee';
            }

            const roleLabels = {
                production: 'Production Staff',
                designer: 'Designer',
                operator: 'Machine Operator',
                'quality-control': 'Quality Control',
                others: normalizedCustomRole || 'Employee'
            };

            return roleLabels[normalizedRole] || normalizedCustomRole || normalizedRole.replace(/-/g, ' ').replace(/\b\w/g, function (match) {
                return match.toUpperCase();
            });
        }

        function getEmployeeAvatar(role) {
            const normalizedRole = normalize(role);

            if (normalizedRole.includes('design')) return '👩‍🎨';
            if (normalizedRole.includes('print') || normalizedRole.includes('production')) return '👨‍💼';
            if (normalizedRole.includes('quality') || normalizedRole.includes('check')) return '👨‍🔧';
            if (normalizedRole.includes('pack') || normalizedRole.includes('ship') || normalizedRole.includes('finishing')) return '👨‍🏭';
            if (normalizedRole.includes('service')) return '👩‍💻';
            return '👤';
        }

        function getEmployeeColor(employeeName) {
            const palette = ['#FF6B6B', '#4ECDC4', '#95E1D3', '#F38181', '#AA96DA', '#FCBAD3', '#81A1C1', '#FFD166'];
            const value = String(employeeName || '').trim().toLowerCase();

            if (!value) {
                return palette[0];
            }

            let hash = 0;
            for (let index = 0; index < value.length; index += 1) {
                hash = (hash * 31 + value.charCodeAt(index)) >>> 0;
            }

            return palette[hash % palette.length];
        }

        function mapEmployeeAccount(account, index) {
            const firstName = String(account?.first_name || '').trim();
            const middleName = String(account?.middle_name || '').trim();
            const lastName = String(account?.last_name || '').trim();
            const fullName = [firstName, middleName, lastName].filter(Boolean).join(' ').trim();
            const role = normalizeEmployeeRole(account?.employee_role, account?.employee_role_other);

            return {
                id: Number(account?.user_id) || index + 1,
                name: fullName || `Employee ${index + 1}`,
                role: role,
                avatar: getEmployeeAvatar(role),
                color: getEmployeeColor(fullName || role)
            };
        }

        function renderEmployeeFilterOptions() {
            const employeeFilter = document.getElementById('employeeFilter');
            if (!employeeFilter) {
                return;
            }

            if (!employeeAccounts.length) {
                employeeFilter.innerHTML = '<option value="">No employees available</option>';
                employeeFilter.disabled = true;
                state.selectedEmployee = '';
                return;
            }

            employeeFilter.disabled = false;
            const fallbackSelection = state.selectedEmployee && employeeAccounts.some(function (employee) {
                return employee.name === state.selectedEmployee;
            }) ? state.selectedEmployee : employeeAccounts[0].name;

            employeeFilter.innerHTML = employeeAccounts.map(function (employee) {
                return `<option value="${esc(employee.name)}" data-employee-name="${esc(employee.name)}" data-employee-role="${esc(employee.role)}">${esc(employee.name)} (${esc(employee.role)})</option>`;
            }).join('');

            employeeFilter.value = fallbackSelection;
            state.selectedEmployee = employeeFilter.value || fallbackSelection;
        }

        async function loadEmployees() {
            try {
                const response = await fetch(EMPLOYEE_LIST_URL, {
                    headers: {
                        Accept: 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                employeeAccounts = Array.isArray(result?.data)
                    ? result.data.filter(function (user) {
                        return Number(user?.role_id) === 2;
                    }).map(mapEmployeeAccount)
                    : [];
            } catch (error) {
                console.error('Failed to load employee accounts:', error);
                employeeAccounts = [];
            }

            renderEmployeeFilterOptions();
        }

        function getCurrentEmployeeName() {
            const employeeFilter = document.getElementById('employeeFilter');
            if (!employeeFilter) {
                return normalizeEmployeeName(state.selectedEmployee);
            }

            const option = employeeFilter.options[employeeFilter.selectedIndex];
            return normalizeEmployeeName(option?.dataset.employeeName || option?.textContent || state.selectedEmployee);
        }

        function getCalendarAssignments() {
            try {
                const raw = JSON.parse(localStorage.getItem(CONTROL_SETTINGS_KEY) || '{}');
                return raw?.adminEmployeeAccess?.['calendar-management'] || {};
            } catch (error) {
                return {};
            }
        }

        function getAdminEmployeeAssignments() {
            try {
                const raw = JSON.parse(localStorage.getItem(CONTROL_SETTINGS_KEY) || '{}');
                return raw?.adminEmployeeAccess || {};
            } catch (error) {
                return {};
            }
        }

        function getAssignedAdminTabsForCurrentEmployee() {
            const currentEmployee = getCurrentEmployeeName();
            const assignments = getAdminEmployeeAssignments();
            return Object.entries(assignments)
                .filter(([, map]) => map && map[currentEmployee])
                .map(([sectionKey]) => sectionKey);
        }

        function isCalendarAllowedForCurrentEmployee() {
            const assignments = getCalendarAssignments();
            const currentEmployee = getCurrentEmployeeName();
            return Boolean(assignments[currentEmployee]);
        }

        function esc(text) {
            return String(text || '').replace(/[&<>"']/g, function (m) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                }[m];
            });
        }

        function normalize(text) {
            return String(text || '').trim().toLowerCase();
        }

        function getEmployeeTaskCacheStore() {
            try {
                return JSON.parse(localStorage.getItem(EMPLOYEE_TASK_CACHE_KEY) || '{}');
            } catch (error) {
                return {};
            }
        }

        function readCachedEmployeeTasks(employeeName) {
            const normalizedEmployee = normalize(employeeName);
            if (!normalizedEmployee) {
                return null;
            }

            const store = getEmployeeTaskCacheStore();
            const cachedEntry = store[normalizedEmployee];
            if (!cachedEntry || !Array.isArray(cachedEntry.tasks)) {
                return null;
            }

            const cachedAt = Number(cachedEntry.cachedAt) || 0;
            if (!cachedAt || (Date.now() - cachedAt) > EMPLOYEE_TASK_CACHE_MAX_AGE_MS) {
                return null;
            }

            return cachedEntry;
        }

        function writeCachedEmployeeTasks(employeeName, tasks) {
            const normalizedEmployee = normalize(employeeName);
            if (!normalizedEmployee) {
                return;
            }

            try {
                const store = getEmployeeTaskCacheStore();
                store[normalizedEmployee] = {
                    cachedAt: Date.now(),
                    tasks: Array.isArray(tasks) ? tasks : []
                };
                localStorage.setItem(EMPLOYEE_TASK_CACHE_KEY, JSON.stringify(store));
            } catch (error) {
                // Ignore cache write errors so task rendering never fails.
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

        function getCurrentEmployeeDisplayName() {
            const employeeFilter = document.getElementById('employeeFilter');
            if (!employeeFilter) {
                return state.selectedEmployee || 'Employee';
            }
            const option = employeeFilter.options[employeeFilter.selectedIndex];
            const label = option ? String(option.textContent || '').split('(')[0].trim() : '';
            return label || state.selectedEmployee || 'Employee';
        }

        async function trelloRequest(endpoint) {
            const response = await fetch(TRELLO_PROXY_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ endpoint, method: 'GET' })
            });

            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`Trello API error (${response.status}): ${errorText}`);
            }

            return response.json();
        }

        async function trelloRequestWithMethod(endpoint, method, body) {
            const response = await fetch(TRELLO_PROXY_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ endpoint, method, body })
            });

            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`Trello API error (${response.status}): ${errorText}`);
            }

            if (response.status === 204) {
                return null;
            }
            return response.json();
        }

        function parseChecklistAssignees(itemName) {
            const match = String(itemName || '').match(/→\s*(.+)$/);
            if (!match || !match[1]) {
                return [];
            }

            return match[1]
                .split(',')
                .map(function (name) { return normalize(name); })
                .filter(function (name) { return name && name !== 'unassigned'; });
        }

        function listStatusTag(listName) {
            const name = normalize(listName);
            if (name.includes('done') || name.includes('completed') || name.includes('complete') || name.includes('finished')) {
                return 'done';
            }
            if (name.includes('todo') || name.includes('to do') || name.includes('backlog')) {
                return 'todo';
            }
            return 'pending';
        }

        function taskCompletionTag(task, employeeName) {
            const selected = normalize(employeeName || state.selectedEmployee);

            const checklists = Array.isArray(task && task.checklists) ? task.checklists : [];
            const allItems = checklists.flatMap(function (checklist) {
                return Array.isArray(checklist.checkItems) ? checklist.checkItems : [];
            });

            const assignedItems = allItems.filter(function (item) {
                return parseChecklistAssignees(item.name).includes(selected);
            });

            if (assignedItems.length) {
                const assignedDone = assignedItems.filter(function (item) {
                    return item.state === 'complete';
                }).length;

                if (assignedDone >= assignedItems.length) {
                    return 'done';
                }

                if (assignedDone === 0) {
                    return 'todo';
                }

                return 'pending';
            }

            if (allItems.length && allItems.every(function (item) {
                return item.state === 'complete';
            })) {
                return 'done';
            }

            if (allItems.length) {
                const completeCount = allItems.filter(function (item) {
                    return item.state === 'complete';
                }).length;

                if (completeCount === 0) {
                    return 'todo';
                }

                if (completeCount < allItems.length) {
                    return 'pending';
                }
            }

            return listStatusTag(task && task.listName);
        }

        function getAssignedChecklistStats(task, employeeName) {
            const selected = normalize(employeeName || state.selectedEmployee);
            const checklists = Array.isArray(task && task.checklists) ? task.checklists : [];
            const allItems = checklists.flatMap(function (checklist) {
                return Array.isArray(checklist.checkItems) ? checklist.checkItems : [];
            });

            const assignedItems = allItems.filter(function (item) {
                return parseChecklistAssignees(item.name).includes(selected);
            });

            const completedAssigned = assignedItems.filter(function (item) {
                return item.state === 'complete';
            }).length;

            return {
                total: assignedItems.length,
                completed: completedAssigned,
                pending: Math.max(assignedItems.length - completedAssigned, 0),
            };
        }

        function normalizeListName(name) {
            return String(name || '')
                .trim()
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, ' ')
                .replace(/\s+/g, ' ')
                .trim();
        }

        function findFirstListByPriority(boardLists, namesInPriority) {
            const normalizedLists = (boardLists || []).map(function (list) {
                return {
                    raw: list,
                    normalized: normalizeListName(list.name)
                };
            });

            for (const targetName of namesInPriority) {
                const normalizedTarget = normalizeListName(targetName);

                // Prefer exact match first
                const exact = normalizedLists.find(function (entry) {
                    return entry.normalized === normalizedTarget;
                });
                if (exact) {
                    return exact.raw;
                }

                // Fall back to contains match for flexible names like "to process"
                const partial = normalizedLists.find(function (entry) {
                    return entry.normalized.includes(normalizedTarget) || normalizedTarget.includes(entry.normalized);
                });
                if (partial) {
                    return partial.raw;
                }
            }

            return null;
        }

        async function autoMoveEmployeeCardByChecklistProgress(cardId) {
            try {
                const card = await trelloRequest(`/cards/${cardId}`);
                if (!card) return;

                const boardLists = await trelloRequest(`/boards/${card.idBoard}/lists`);
                if (!boardLists || boardLists.length === 0) return;

                const checklists = await trelloRequest(`/cards/${cardId}/checklists`);
                if (!checklists) return;

                const allItems = checklists.flatMap(function (checklist) {
                    return checklist.checkItems || [];
                });
                const totalItems = allItems.length;
                const completedItems = allItems.filter(function (item) {
                    return item.state === 'complete';
                }).length;

                if (totalItems === 0) return;

                let targetList = null;
                if (completedItems === 0) {
                    targetList = findFirstListByPriority(boardLists, ['to do', 'todo', 'backlog']);
                } else if (completedItems === totalItems) {
                    targetList = findFirstListByPriority(boardLists, ['completed', 'done', 'complete']);
                } else {
                    targetList = findFirstListByPriority(boardLists, [
                        'to process',
                        'process',
                        'in progress',
                        'processing',
                        'printing',
                        'quality check',
                        'ready for pickup'
                    ]);
                }

                if (!targetList) return;
                if (card.idList === targetList.id) return;

                await trelloRequestWithMethod(`/cards/${cardId}`, 'PUT', { idList: targetList.id });
            } catch (error) {
                console.error('Employee auto-move by checklist progress failed:', error);
            }
        }

        function formatDate(rawDate) {
            if (!rawDate) {
                return 'No due date';
            }
            const d = new Date(rawDate);
            if (Number.isNaN(d.getTime())) {
                return 'No due date';
            }
            return d.toLocaleDateString('en-US', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            });
        }

        function renderTaskItems(containerId, tasks) {
            const container = document.getElementById(containerId);
            if (!container) return;

            if (!tasks.length) {
                container.innerHTML = '<div class="panel-empty">No assigned tasks for this employee yet.</div>';
                return;
            }

            container.innerHTML = tasks.map(function (task) {
                const tag = taskCompletionTag(task, state.selectedEmployee);
                const tagLabel = tag === 'done' ? 'Done' : (tag === 'todo' ? 'To Do' : 'Pending');
                return `
                    <button type="button" class="task-item task-button" data-card-id="${esc(task.cardId)}" title="Open task details">
                        <div>
                            <div class="task-meta">${esc(task.orderRef)} · ${esc(formatDate(task.due))}</div>
                            <div class="task-name">${esc(task.cardName)}</div>
                        </div>
                        <span class="tag ${tag}">${esc(tagLabel)}</span>
                    </button>
                `;
            }).join('');
        }

        function renderEmployeeTaskModal(task) {
            const modal = document.getElementById('employeeTaskModal');
            const title = document.getElementById('employeeTaskModalTitle');
            const body = document.getElementById('employeeTaskModalBody');
            if (!modal || !title || !body || !task) return;

            const selected = normalize(state.selectedEmployee);
            const checklistItems = (task.checklists || []).flatMap(function (checklist) {
                return (checklist.checkItems || []).map(function (item) {
                    const done = item.state === 'complete';
                    const checkedByInfo = done ? getChecklistCheckedByInfo(task.cardId, item.id) : null;
                    const checkedByText = checkedByInfo ? formatCheckedByText(checkedByInfo) : '';
                    const assignees = parseChecklistAssignees(item.name);
                    const isAssigned = assignees.includes(selected);
                    const itemHtml = `
                        <div class="employee-task-checklist-item ${done ? 'done' : ''}" 
                             data-card-id="${task.cardId}" 
                             data-checklist-id="${checklist.id}" 
                             data-item-id="${item.id}" 
                             data-is-assigned="${isAssigned ? 'yes' : 'no'}" 
                             ${isAssigned ? 'style="cursor: pointer;"' : 'style="opacity: 0.6;"'}>
                            <span class="employee-task-checklist-mark">${done ? '☑' : '☐'}</span>
                            <span class="employee-task-checklist-text">${esc(item.name || '')}</span>
                            ${checkedByText ? `<small class="employee-task-checklist-by">${esc(checkedByText)}</small>` : ''}
                        </div>
                    `;
                    return itemHtml;
                });
            });

            title.textContent = task.cardName || 'Task Details';
            body.innerHTML = `
                <div class="employee-task-meta">
                    <span><strong>List:</strong> ${esc(task.listName)}</span>
                    <span><strong>Due:</strong> ${esc(formatDate(task.due))}</span>
                    <span><strong>Ref:</strong> ${esc(task.orderRef)}</span>
                </div>
                <h4 style="margin: 0 0 8px; font-size: 0.95rem; color: #253252;">Description</h4>
                <div class="employee-task-box">${esc(task.description || 'No description')}</div>
                <h4 style="margin: 0 0 8px; font-size: 0.95rem; color: #253252;">Checklist</h4>
                <div class="employee-task-box" id="employeeChecklistContainer">${checklistItems.length ? checklistItems.join('') : '<div class="panel-empty">No checklist items available.</div>'}</div>
            `;

            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
            setupEmployeeChecklistInteraction();
        }

        function closeEmployeeTaskModal() {
            const modal = document.getElementById('employeeTaskModal');
            if (!modal) return;
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
        }

        async function updateEmployeeChecklistItem(cardId, checklistId, itemId, newState) {
            try {
                const result = await trelloRequestWithMethod(
                    `/cards/${cardId}/checkItem/${itemId}`,
                    'PUT',
                    { state: newState }
                );

                return result;
            } catch (error) {
                console.error('Checklist item update failed:', error);
                throw error;
            }
        }

        const checklistUpdateLocks = new Set();
        const checklistSyncTimers = new Map();

        function hasChecklistLockForCard(cardId) {
            const prefix = `${cardId}:`;
            for (const lockKey of checklistUpdateLocks) {
                if (lockKey.startsWith(prefix)) {
                    return true;
                }
            }
            return false;
        }

        function queueChecklistCardSync(cardId, delayMs = 1800) {
            if (!cardId) {
                return;
            }

            const existingTimer = checklistSyncTimers.get(cardId);
            if (existingTimer) {
                window.clearTimeout(existingTimer);
            }

            const timer = window.setTimeout(function () {
                if (hasChecklistLockForCard(cardId)) {
                    queueChecklistCardSync(cardId, 800);
                    return;
                }

                checklistSyncTimers.delete(cardId);
                void refreshEmployeeCardData(cardId).catch(function (error) {
                    console.error('Employee card refresh failed:', error);
                });
            }, delayMs);

            checklistSyncTimers.set(cardId, timer);
        }

        function updateChecklistItemInState(cardId, itemId, newState) {
            const taskIndex = state.tasks.findIndex(function (task) {
                return task.cardId === cardId;
            });

            if (taskIndex === -1) {
                return;
            }

            const task = state.tasks[taskIndex];
            const checklists = Array.isArray(task.checklists) ? task.checklists : [];
            let updated = false;

            checklists.forEach(function (checklist) {
                const items = Array.isArray(checklist.checkItems) ? checklist.checkItems : [];
                items.forEach(function (item) {
                    if (String(item.id) === String(itemId)) {
                        item.state = newState;
                        updated = true;
                    }
                });
            });

            if (!updated) {
                return;
            }

            const allItems = checklists.flatMap(function (checklist) {
                return Array.isArray(checklist.checkItems) ? checklist.checkItems : [];
            });

            task.checkItemTotal = allItems.length;
            task.checkItemComplete = allItems.filter(function (item) {
                return item.state === 'complete';
            }).length;

            renderDashboard();

            const modal = document.getElementById('employeeTaskModal');
            if (modal && modal.classList.contains('show')) {
                renderEmployeeTaskModal(task);
            }
        }

        function setupEmployeeChecklistInteraction() {
            const container = document.getElementById('employeeChecklistContainer');
            if (!container) return;

            function setChecklistItemView(item, isDone) {
                item.classList.toggle('done', isDone);
                const checkbox = item.querySelector('.employee-task-checklist-mark');
                if (checkbox) {
                    checkbox.textContent = isDone ? '☑' : '☐';
                }
            }

            const items = container.querySelectorAll('.employee-task-checklist-item');
            items.forEach(function (item) {
                const isAssigned = item.getAttribute('data-is-assigned') === 'yes';
                if (!isAssigned) return;

                item.addEventListener('click', async function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    const cardId = item.getAttribute('data-card-id');
                    const checklistId = item.getAttribute('data-checklist-id');
                    const itemId = item.getAttribute('data-item-id');
                    const isDone = item.classList.contains('done');
                    const newState = isDone ? 'incomplete' : 'complete';
                    const nextDone = newState === 'complete';
                    const lockKey = `${cardId}:${itemId}`;

                    if (checklistUpdateLocks.has(lockKey)) {
                        return;
                    }

                    checklistUpdateLocks.add(lockKey);

                    item.setAttribute('aria-busy', 'true');
                    item.style.pointerEvents = 'none';
                    setChecklistItemView(item, nextDone);

                    try {
                        // Update in Trello
                        await updateEmployeeChecklistItem(cardId, checklistId, itemId, newState);
                        updateChecklistItemInState(cardId, itemId, newState);

                        // Mirror admin logic: persist checked-by metadata for checklist item.
                        if (newState === 'complete') {
                            setChecklistCheckedBy(cardId, itemId, getCurrentEmployeeDisplayName());
                        } else {
                            clearChecklistCheckedBy(cardId, itemId);
                        }

                        // Keep the UI responsive and sync the heavier updates in the background.
                        void autoMoveEmployeeCardByChecklistProgress(cardId).catch(function (error) {
                            console.error('Employee auto-move sync failed:', error);
                        });

                        // Trello checklist updates are eventually consistent.
                        // Coalesce refreshes so the modal does not bounce between states.
                        queueChecklistCardSync(cardId);
                        
                    } catch (error) {
                        setChecklistItemView(item, isDone);
                        alert('Failed to update checklist item. Please try again.');
                    } finally {
                        item.setAttribute('aria-busy', 'false');
                        item.style.pointerEvents = '';
                        checklistUpdateLocks.delete(lockKey);
                    }
                });
            });
        }

        async function refreshEmployeeCardData(cardId) {
            try {
                // Find the task in state and refresh its data from Trello
                const taskIndex = state.tasks.findIndex(function (t) { return t.cardId === cardId; });
                if (taskIndex === -1) return;
                // Fetch fresh card checklist data from Trello
                const freshChecklists = await trelloRequest(`/cards/${cardId}/checklists`);
                
                if (freshChecklists) {
                    // Update the task object with fresh checklist data
                    state.tasks[taskIndex].checklists = freshChecklists;

                    renderDashboard();
                    
                    // Re-render the modal if it's currently open to show latest data
                    const modal = document.getElementById('employeeTaskModal');
                    if (modal && modal.classList.contains('show')) {
                        renderEmployeeTaskModal(state.tasks[taskIndex]);
                    }
                }
            } catch (error) {
                console.error('Failed to refresh card data:', error);
                // Don't throw - silently fail so UI doesn't break
            }
        }

        function setupTaskDetailsOpen() {
            ['pendingTaskList', 'doneTaskList', 'allTaskList', 'notificationList'].forEach(function (containerId) {
                const container = document.getElementById(containerId);
                if (!container) return;

                container.addEventListener('click', function (event) {
                    const button = event.target.closest('button[data-card-id]');
                    if (!button) return;
                    const cardId = button.dataset.cardId;
                    const task = state.tasks.find(function (item) { return item.cardId === cardId; });
                    if (task) {
                        renderEmployeeTaskModal(task);
                    }
                });
            });

            const closeBtn = document.getElementById('employeeTaskModalClose');
            if (closeBtn) {
                closeBtn.addEventListener('click', closeEmployeeTaskModal);
            }

            const modal = document.getElementById('employeeTaskModal');
            if (modal) {
                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        closeEmployeeTaskModal();
                    }
                });
            }

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeEmployeeTaskModal();
                }
            });
        }

        function renderDashboard() {
            const activeTasks = state.tasks.filter(function (task) {
                return taskCompletionTag(task, state.selectedEmployee) !== 'done';
            });
            const doneTasks = state.tasks.filter(function (task) {
                return taskCompletionTag(task, state.selectedEmployee) === 'done';
            });

            const checklistStats = state.tasks.reduce(function (accumulator, task) {
                const stats = getAssignedChecklistStats(task, state.selectedEmployee);
                accumulator.total += stats.total;
                accumulator.completed += stats.completed;
                accumulator.pending += stats.pending;
                return accumulator;
            }, { total: 0, completed: 0, pending: 0 });

            document.getElementById('taskCount').textContent = String(checklistStats.pending);
            document.getElementById('completedTaskCount').textContent = String(checklistStats.completed);
            document.getElementById('notificationCount').textContent = String(checklistStats.pending);
            document.getElementById('pendingTaskHeading').textContent = 'Pending Task (' + activeTasks.length + ')';
            document.getElementById('completedTaskHeading').textContent = 'Completed Task (' + doneTasks.length + ')';
            renderTaskItems('pendingTaskList', activeTasks.slice(0, 6));
            renderTaskItems('doneTaskList', doneTasks.slice(0, 6));
            renderTaskItems('allTaskList', state.tasks);
            renderTaskItems('notificationList', activeTasks.slice(0, 10));
        }

        let employeeTasksLoadInFlight = false;
        let employeeTasksReloadQueued = false;

        async function loadEmployeeTasks(options = {}) {
            const force = Boolean(options && options.force);
            if (employeeTasksLoadInFlight) {
                employeeTasksReloadQueued = true;
                return;
            }

            employeeTasksLoadInFlight = true;
            const statusMessage = document.getElementById('employeeStatusMsg');
            const selectedEmployee = state.selectedEmployee;

            if (!force) {
                const cachedEntry = readCachedEmployeeTasks(selectedEmployee);
                if (cachedEntry) {
                    state.tasks = cachedEntry.tasks;
                    renderDashboard();
                    statusMessage.innerHTML = 'Showing cached tasks for <strong>' + esc(selectedEmployee) + '</strong>...';
                }
            }

            statusMessage.innerHTML = 'Loading Trello tasks for <strong>' + esc(state.selectedEmployee) + '</strong>...';

            try {
                const boards = await trelloRequest('/members/me/boards?fields=id,name');

                if (!boards || !boards.length) {
                    state.tasks = [];
                    renderDashboard();
                    writeCachedEmployeeTasks(selectedEmployee, []);
                    statusMessage.innerHTML = 'No Trello boards found for <strong>' + esc(selectedEmployee || 'this employee') + '</strong>.';
                    return;
                }

                const boardCardPromises = boards.map(function (board) {
                    return Promise.all([
                        trelloRequest(`/boards/${board.id}/lists?fields=id,name`),
                        // Keep payload lean for faster employee dashboard renders.
                        trelloRequest(`/boards/${board.id}/cards?fields=id,name,due,idList,desc,shortLink,badges&checklists=all`)
                    ]).then(function (result) {
                        return {
                            board: board,
                            lists: result[0] || [],
                            cards: result[1] || []
                        };
                    });
                });

                const boardsData = await Promise.all(boardCardPromises);
                const selected = normalize(state.selectedEmployee);
                const collected = [];

                boardsData.forEach(function (boardData) {
                    const listMap = new Map();
                    boardData.lists.forEach(function (list) {
                        listMap.set(list.id, list.name || 'Unknown');
                    });

                    boardData.cards.forEach(function (card) {
                        const cardChecklists = Array.isArray(card.checklists) ? card.checklists : [];
                        let isAssigned = false;

                        for (const checklist of cardChecklists) {
                            const items = Array.isArray(checklist.checkItems) ? checklist.checkItems : [];
                            for (const item of items) {
                                const assignees = parseChecklistAssignees(item.name);
                                if (assignees.includes(selected)) {
                                    isAssigned = true;
                                    break;
                                }
                            }
                            if (isAssigned) break;
                        }

                        if (!isAssigned) return;

                        const orderRef = (card.name || '').split(' - ')[0] || card.shortLink || 'TASK';
                        const badges = card.badges || {};
                        collected.push({
                            cardId: card.id,
                            cardName: card.name || 'Untitled Task',
                            orderRef: orderRef,
                            due: card.due,
                            listName: listMap.get(card.idList) || 'Backlog',
                            description: card.desc || '',
                            checklists: cardChecklists,
                            checkItemTotal: Number(badges.checkItems) || 0,
                            checkItemComplete: Number(badges.checkItemsChecked) || 0
                        });
                    });
                });

                state.tasks = collected;
                writeCachedEmployeeTasks(selectedEmployee, collected);
                renderDashboard();
                statusMessage.innerHTML = 'Connected to Trello. Showing tasks for <strong>' + esc(state.selectedEmployee) + '</strong>.';
            } catch (error) {
                state.tasks = [];
                renderDashboard();
                statusMessage.innerHTML = 'Could not load Trello data. Make sure the server Trello settings are available.';
                console.error('Employee Trello load failed:', error);
            } finally {
                employeeTasksLoadInFlight = false;
                if (employeeTasksReloadQueued) {
                    employeeTasksReloadQueued = false;
                    void loadEmployeeTasks();
                }
            }
        }

        function getEmployeeTabControls() {
            try {
                const raw = JSON.parse(localStorage.getItem(CONTROL_SETTINGS_KEY) || '{}');
                return {
                    overview: raw?.employeeTabs?.overview !== false,
                    tasks: raw?.employeeTabs?.tasks !== false,
                    notifications: raw?.employeeTabs?.notifications !== false,
                    assignedAdminTabs: getAssignedAdminTabsForCurrentEmployee(),
                };
            } catch (error) {
                return {
                    overview: true,
                    tasks: true,
                    notifications: true,
                    assignedAdminTabs: [],
                };
            }
        }

        function getEmployeeGeneralSettings() {
            try {
                const raw = JSON.parse(localStorage.getItem(CONTROL_SETTINGS_KEY) || '{}');
                const enabled = raw?.generalSettings?.employeeAutoRefreshEnabled !== false;
                const parsedInterval = Number(raw?.generalSettings?.employeeAutoRefreshIntervalSec);
                const safeIntervalSec = Math.max(10, Math.min(300, Number.isFinite(parsedInterval) ? parsedInterval : 30));
                return {
                    autoRefreshEnabled: enabled,
                    autoRefreshIntervalMs: safeIntervalSec * 1000,
                };
            } catch (error) {
                return {
                    autoRefreshEnabled: true,
                    autoRefreshIntervalMs: 30000,
                };
            }
        }

        function applyEmployeeTabControls() {
            const tabMenu = document.getElementById('employeeTabMenu');
            if (!tabMenu) return;

            const controls = getEmployeeTabControls();
            const tabLinks = tabMenu.querySelectorAll('a[data-tab]');
            const assignedSet = new Set(controls.assignedAdminTabs || []);

            tabLinks.forEach(function (link) {
                const tab = link.dataset.tab;
                const isDefaultTab = tab === 'overview' || tab === 'tasks' || tab === 'notifications';
                const allowed = isDefaultTab ? controls[tab] !== false : assignedSet.has(tab);
                link.style.display = allowed ? '' : 'none';
                link.parentElement.style.display = allowed ? '' : 'none';

                const section = document.getElementById('tab-' + tab);
                if (section) {
                    section.style.display = allowed ? '' : 'none';
                }
            });

            const activeLink = Array.from(tabLinks).find(function (link) {
                return link.classList.contains('active') && link.parentElement.style.display !== 'none';
            });

            if (!activeLink) {
                const firstVisible = Array.from(tabLinks).find(function (link) {
                    return link.parentElement.style.display !== 'none';
                });

                if (firstVisible) {
                    firstVisible.click();
                }
            }
        }

        function setupTabs() {
            const tabLinks = document.querySelectorAll('#employeeTabMenu a[data-tab]');
            const sections = {};
            document.querySelectorAll('.tab-section[id^="tab-"]').forEach(function (section) {
                const key = section.id.replace(/^tab-/, '');
                sections[key] = section;
            });

            const hydrateTabEmbeds = (tab) => {
                const section = sections[tab];
                if (!section) {
                    return;
                }

                section.querySelectorAll('iframe[data-src]').forEach(function (frame) {
                    if (frame.src === 'about:blank' && frame.dataset.src) {
                        frame.src = frame.dataset.src;
                    }
                });
            };

            tabLinks.forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    const tab = link.dataset.tab;

                    if (link.parentElement.style.display === 'none') {
                        return;
                    }

                    tabLinks.forEach(function (l) { l.classList.remove('active'); });
                    Object.values(sections).forEach(function (section) {
                        if (section) {
                            section.classList.remove('active');
                        }
                    });

                    link.classList.add('active');
                    if (sections[tab]) {
                        sections[tab].classList.add('active');
                    }

                    hydrateTabEmbeds(tab);
                });
            });

            const activeLink = Array.from(tabLinks).find(function (link) {
                return link.classList.contains('active');
            });
            if (activeLink?.dataset.tab) {
                hydrateTabEmbeds(activeLink.dataset.tab);
            }
        }

        function setupEmployeeFilter() {
            const employeeFilter = document.getElementById('employeeFilter');
            if (!employeeFilter) {
                return;
            }

            employeeFilter.addEventListener('change', async function () {
                state.selectedEmployee = employeeFilter.value;
                applyEmployeeTabControls();
                await loadEmployeeTasks();
            });
        }

        let employeeRefreshTimer = null;

        function applyEmployeeRefreshControls() {
            const settings = getEmployeeGeneralSettings();
            if (employeeRefreshTimer) {
                clearInterval(employeeRefreshTimer);
                employeeRefreshTimer = null;
            }

            if (!settings.autoRefreshEnabled) {
                return;
            }

            employeeRefreshTimer = setInterval(async function () {
                await loadEmployeeTasks();
            }, settings.autoRefreshIntervalMs);
        }

        document.addEventListener('DOMContentLoaded', async function () {
            setupTabs();
            await loadEmployees();
            applyEmployeeTabControls();
            setupEmployeeFilter();
            setupTaskDetailsOpen();
            void loadEmployeeTasks();
            applyEmployeeRefreshControls();

            window.addEventListener('storage', function (event) {
                if (event.key === CONTROL_SETTINGS_KEY) {
                    applyEmployeeTabControls();
                    applyEmployeeRefreshControls();
                }
            });

            document.addEventListener('printopia:controls-updated', function () {
                applyEmployeeTabControls();
            });
        });
    </script>
</body>
</html>
