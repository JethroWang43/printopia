<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-gold: #e7a821;
            --brand-navy: #10224f;
            --ink: #1f2430;
            --surface: #eef1f7;
            --card: #ffffff;
            --line: #dce3f0;
            --muted: #5f677d;
            --shadow: 0 12px 30px rgba(16, 34, 79, 0.08);
            --soft-shadow: 0 6px 16px rgba(16, 34, 79, 0.06);
            --warning: #f4a30a;
            --danger: #b0160f;
            --info: #1e91d6;
            --focus: #d6e6ff;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            margin: 0;
            font-family: "Sora", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 10% 0%, rgba(255, 255, 255, 0.6), transparent 45%),
                linear-gradient(180deg, #edf2fb 0%, #e6edf8 100%);
            display: flex;
            flex-direction: column;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .container {
            width: min(1640px, 97vw);
            margin: 0 auto;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 40;
            background: var(--brand-gold);
            border-bottom: 1px solid rgba(16, 34, 79, 0.15);
            box-shadow: 0 6px 14px rgba(16, 34, 79, 0.15);
        }

        .topbar .container,
        .site-footer .container {
            width: 100%;
            max-width: none;
            padding-left: clamp(20px, 4vw, 84px);
            padding-right: clamp(20px, 4vw, 84px);
        }

        .topbar-inner {
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.85rem;
            font-weight: 800;
            color: #0d1f49;
            flex: 1;
        }

        .logo-mark {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: #0d1f49;
            color: #f8cd64;
            display: grid;
            place-items: center;
            font-size: 1.05rem;
            font-weight: 800;
        }

        .main-nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: clamp(18px, 2.3vw, 34px);
            flex: 1.15;
            color: #162757;
            font-size: 0.95rem;
        }

        .main-nav a {
            position: relative;
            padding: 8px 0;
            font-weight: 700;
            opacity: 0.92;
        }

        .user-menu {
            position: relative;
            margin-left: auto;
        }

        .user-chip-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: 2px solid #122459;
            border-radius: 12px;
            padding: 10px 16px;
            color: #132559;
            font-size: 0.88rem;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.28);
            cursor: pointer;
        }

        .user-dropdown {
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            min-width: 160px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #dbe2f0;
            background: #ffffff;
            box-shadow: 0 12px 20px rgba(16, 34, 79, 0.16);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-6px);
            transition: opacity 0.18s ease, transform 0.18s ease, visibility 0.18s ease;
        }

        .user-menu:hover .user-dropdown,
        .user-menu:focus-within .user-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-role {
            display: block;
            width: 100%;
            text-align: left;
            padding: 10px 12px;
            border-bottom: 1px solid #edf1f8;
            background: transparent;
            color: #273257;
            font-size: 0.86rem;
            font-weight: 600;
            cursor: pointer;
        }

        .user-role:last-child {
            border-bottom: 0;
        }

        .user-role:hover {
            background: #f5f7fb;
        }

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
            border: 1px solid transparent;
            transition: background 0.16s ease, border-color 0.16s ease, transform 0.16s ease;
        }

        .menu li a.active,
        .menu li a:hover {
            background: #f2f6ff;
            color: #152a5e;
            border-color: #dde6f7;
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
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            max-width: 680px;
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

        .employee-task-checklist-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 8px;
            color: #2a3552;
            font-size: 0.88rem;
            border-radius: 4px;
            transition: background-color 0.2s, color 0.2s;
        }

        .employee-task-checklist-item[data-is-assigned="yes"] {
            cursor: pointer;
        }

        .employee-task-checklist-item[data-is-assigned="yes"]:hover {
            background-color: rgba(0, 102, 204, 0.08);
            color: #0066cc;
        }

        .employee-task-checklist-item.done {
            text-decoration: line-through;
            color: #5f6d89;
        }

        .employee-task-checklist-item[data-is-assigned="yes"].done:hover {
            background-color: rgba(0, 102, 204, 0.08);
            color: #0066cc;
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
            background: var(--warning);
        }

        .tag.todo {
            background: var(--danger);
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
    <header class="topbar">
        <div class="container topbar-inner">
            <a class="logo" href="<?= base_url(); ?>">
                <span class="logo-mark">P</span>
                <span>Printopia</span>
            </a>

            <nav class="main-nav" aria-label="Main navigation">
                <a href="<?= base_url(); ?>">Home</a>
                <a href="<?= base_url('products'); ?>">Products</a>
                <a href="<?= base_url('how-it-works'); ?>">How it works</a>
                <a href="<?= base_url('contact'); ?>">Contact Us</a>
            </nav>

            <div class="user-menu" aria-label="Role menu">
                <button type="button" class="user-chip-btn" aria-haspopup="true" aria-expanded="false">
                    <span>◎</span>
                    <span>Sample User</span>
                    <span>▾</span>
                </button>
                <div class="user-dropdown" role="menu" aria-label="Select role">
                    <button type="button" class="user-role" role="menuitem">User</button>
                    <a class="user-role" role="menuitem" href="<?= base_url('admin'); ?>">Admin</a>
                    <a class="user-role" role="menuitem" href="<?= base_url('employee'); ?>">Employee</a>
                </div>
            </div>
        </div>
    </header>

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
                </ul>

                <div class="employee-picker">
                    <label for="employeeFilter">Sample Employee</label>
                    <select id="employeeFilter">
                        <option value="john">John (Sample Employee)</option>
                        <option value="maria">Maria</option>
                        <option value="james">James</option>
                        <option value="emily">Emily</option>
                        <option value="michael">Michael</option>
                        <option value="sarah">Sarah</option>
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

                        <article class="card notification">
                            <div>
                                <h5>Notification</h5>
                                <strong id="notificationCount">0</strong>
                            </div>
                            <span class="icon">🔔</span>
                        </article>
                    </div>

                    <div class="panels">
                        <article class="panel">
                            <h3>Pending Task</h3>
                            <div class="task-list" id="pendingTaskList">
                                <div class="panel-empty">Loading Trello tasks...</div>
                            </div>
                        </article>

                        <article class="panel">
                            <h3>Notifications</h3>
                            <div class="task-list" id="overviewNotificationList">
                                <div class="panel-empty">Loading Trello notifications...</div>
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
            </section>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <section>
                    <div class="footer-logo">
                        <span class="logo-mark">P</span>
                        <span>Printopia</span>
                    </div>
                    <p>Your partner for custom printing solutions with a clean and easy ordering workflow.</p>
                </section>

                <section>
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="<?= base_url(); ?>">Home</a></li>
                        <li><a href="<?= base_url('products'); ?>">Products</a></li>
                        <li><a href="<?= base_url('how-it-works'); ?>">How it works</a></li>
                        <li><a href="<?= base_url('contact'); ?>">Contact Us</a></li>
                    </ul>
                </section>

                <section>
                    <h5>Services</h5>
                    <ul>
                        <li>Custom 3D model design</li>
                        <li>Design consultation</li>
                        <li>Quality assurance</li>
                        <li>Trusted order handling</li>
                    </ul>
                </section>

                <section>
                    <h5>Contact Information</h5>
                    <ul>
                        <li>0922-4756841</li>
                        <li>esensoweta61@gmail.com</li>
                        <li>Tanauan, Batangas</li>
                    </ul>
                </section>
            </div>

            <div class="footer-bottom">&copy; 2026 Printopia. All rights reserved.</div>
        </div>
    </footer>

    <div class="employee-task-modal" id="employeeTaskModal" aria-hidden="true">
        <div class="employee-task-modal-card">
            <div class="employee-task-modal-head">
                <h4 id="employeeTaskModalTitle">Task Details</h4>
                <button type="button" class="employee-task-modal-close" id="employeeTaskModalClose" aria-label="Close">×</button>
            </div>
            <div class="employee-task-modal-body" id="employeeTaskModalBody"></div>
        </div>
    </div>

    <script src="<?= base_url('trello-task/lib/env.js'); ?>"></script>
    <script>
        const TRELLO_API_URL = (window.APP_ENV && window.APP_ENV.TRELLO_API_URL) || 'https://api.trello.com/1';
        const TRELLO_API_KEY = (window.APP_ENV && window.APP_ENV.TRELLO_API_KEY) || '';
        const TRELLO_TOKEN = localStorage.getItem('trelloToken') || ((window.APP_ENV && window.APP_ENV.DEFAULT_TOKEN) || '');
        const CHECKED_BY_STORAGE_KEY = 'checklistCheckedBy';

        const state = {
            selectedEmployee: 'john',
            tasks: []
        };

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

        function getChecklistCheckedByMap() {
            try {
                return JSON.parse(localStorage.getItem(CHECKED_BY_STORAGE_KEY) || '{}');
            } catch (error) {
                console.warn('Failed to parse checklist checked-by map:', error);
                return {};
            }
        }

        function getChecklistCheckedBy(cardId, itemId) {
            const map = getChecklistCheckedByMap();
            return map?.[cardId]?.[itemId] || '';
        }

        function setChecklistCheckedBy(cardId, itemId, checkedByName) {
            const map = getChecklistCheckedByMap();
            if (!map[cardId]) {
                map[cardId] = {};
            }
            map[cardId][itemId] = checkedByName;
            localStorage.setItem(CHECKED_BY_STORAGE_KEY, JSON.stringify(map));
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
            if (!TRELLO_API_KEY || !TRELLO_TOKEN) {
                throw new Error('Missing Trello credentials.');
            }

            const separator = endpoint.includes('?') ? '&' : '?';
            const url = `${TRELLO_API_URL}${endpoint}${separator}key=${encodeURIComponent(TRELLO_API_KEY)}&token=${encodeURIComponent(TRELLO_TOKEN)}`;
            const response = await fetch(url);

            if (!response.ok) {
                throw new Error(`Trello API error (${response.status})`);
            }

            return response.json();
        }

        async function trelloRequestWithMethod(endpoint, method, body) {
            if (!TRELLO_API_KEY || !TRELLO_TOKEN) {
                throw new Error('Missing Trello credentials.');
            }

            const separator = endpoint.includes('?') ? '&' : '?';
            const url = `${TRELLO_API_URL}${endpoint}${separator}key=${encodeURIComponent(TRELLO_API_KEY)}&token=${encodeURIComponent(TRELLO_TOKEN)}`;
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: body ? JSON.stringify(body) : undefined
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
            if (name.includes('todo') || name.includes('to do') || name.includes('backlog')) {
                return 'todo';
            }
            return 'pending';
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
                const tag = listStatusTag(task.listName);
                const tagLabel = tag === 'todo' ? 'To Do' : 'Pending';
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

            console.log('Task data:', task);
            console.log('Checklists:', task.checklists);

            const selected = normalize(state.selectedEmployee);
            const checklistItems = (task.checklists || []).flatMap(function (checklist) {
                console.log('Processing checklist:', checklist);
                return (checklist.checkItems || []).map(function (item) {
                    console.log('Processing item:', item);
                    const done = item.state === 'complete';
                    const checkedByName = done ? getChecklistCheckedBy(task.cardId, item.id) : '';
                    const assignees = parseChecklistAssignees(item.name);
                    const isAssigned = assignees.includes(selected);
                    const itemHtml = `
                        <div class="employee-task-checklist-item ${done ? 'done' : ''}" 
                             data-card-id="${task.cardId}" 
                             data-checklist-id="${checklist.id}" 
                             data-item-id="${item.id}" 
                             data-is-assigned="${isAssigned ? 'yes' : 'no'}" 
                             ${isAssigned ? 'style="cursor: pointer;"' : 'style="opacity: 0.6;"'}>
                            <span>${done ? '☑' : '☐'}</span>
                            <span>${esc(item.name || '')}</span>
                            ${checkedByName ? `<small style="margin-left: auto; color: #6a748d;">Checked by: ${esc(checkedByName)}</small>` : ''}
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
                console.log('Updating checklist item:', { cardId, checklistId, itemId, newState });
                
                const response = await fetch(
                    `${TRELLO_API_URL}/cards/${cardId}/checkItem/${itemId}?key=${TRELLO_API_KEY}&token=${TRELLO_TOKEN}`,
                    {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ state: newState })
                    }
                );
                
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Trello API error:', response.status, errorText);
                    throw new Error(`Trello API error: ${response.status} - ${errorText}`);
                }
                
                console.log('Checklist item updated successfully');
                return await response.json();
            } catch (error) {
                console.error('Checklist item update failed:', error);
                throw error;
            }
        }

        function setupEmployeeChecklistInteraction() {
            const container = document.getElementById('employeeChecklistContainer');
            if (!container) return;

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

                    try {
                        // Update in Trello
                        await updateEmployeeChecklistItem(cardId, checklistId, itemId, newState);

                        // Apply the same checklist-based auto-move behavior used in admin view.
                        await autoMoveEmployeeCardByChecklistProgress(cardId);

                        // Mirror admin logic: persist checked-by metadata for checklist item.
                        if (newState === 'complete') {
                            setChecklistCheckedBy(cardId, itemId, getCurrentEmployeeDisplayName());
                        } else {
                            clearChecklistCheckedBy(cardId, itemId);
                        }
                        
                        // Update UI optimistically
                        item.classList.toggle('done');
                        const checkbox = item.querySelector('span:first-child');
                        if (checkbox) {
                            checkbox.textContent = newState === 'complete' ? '☑' : '☐';
                        }
                        
                        // Refresh the entire card to sync with admin changes and ensure consistency
                        await refreshEmployeeCardData(cardId);
                        await loadEmployeeTasks();
                        
                    } catch (error) {
                        alert('Failed to update checklist item. Please try again.');
                    }
                });
            });
        }

        async function refreshEmployeeCardData(cardId) {
            try {
                // Find the task in state and refresh its data from Trello
                const taskIndex = state.tasks.findIndex(function (t) { return t.cardId === cardId; });
                if (taskIndex === -1) return;

                const task = state.tasks[taskIndex];
                console.log('Refreshing card data for:', cardId);
                
                // Fetch fresh card checklist data from Trello
                const freshChecklists = await trelloRequest(`/cards/${cardId}/checklists`);
                console.log('Fresh checklists data:', freshChecklists);
                
                if (freshChecklists) {
                    // Update the task object with fresh checklist data
                    state.tasks[taskIndex].checklists = freshChecklists;
                    console.log('Updated task checklists, total items:', freshChecklists.reduce(function(sum, c) { return sum + (c.checkItems ? c.checkItems.length : 0); }, 0));
                    
                    // Re-render the modal if it's currently open to show latest data
                    const modal = document.getElementById('employeeTaskModal');
                    if (modal && modal.classList.contains('show')) {
                        console.log('Re-rendering modal with fresh data');
                        renderEmployeeTaskModal(state.tasks[taskIndex]);
                    }
                }
            } catch (error) {
                console.error('Failed to refresh card data:', error);
                // Don't throw - silently fail so UI doesn't break
            }
        }

        function setupTaskDetailsOpen() {
            ['pendingTaskList', 'allTaskList', 'notificationList', 'overviewNotificationList'].forEach(function (containerId) {
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

        function renderNotifications(tasks) {
            const notificationTasks = tasks.filter(function (task) {
                return listStatusTag(task.listName) === 'todo';
            });

            renderTaskItems('notificationList', notificationTasks);
            renderTaskItems('overviewNotificationList', notificationTasks.slice(0, 4));
            document.getElementById('notificationCount').textContent = String(notificationTasks.length);
        }

        function renderDashboard() {
            document.getElementById('taskCount').textContent = String(state.tasks.length);
            renderTaskItems('pendingTaskList', state.tasks.slice(0, 6));
            renderTaskItems('allTaskList', state.tasks);
            renderNotifications(state.tasks);
        }

        async function loadEmployeeTasks() {
            const statusMessage = document.getElementById('employeeStatusMsg');
            statusMessage.innerHTML = 'Loading Trello tasks for <strong>' + esc(state.selectedEmployee) + '</strong>...';

            try {
                const [boards, member] = await Promise.all([
                    trelloRequest('/members/me/boards?fields=id,name'),
                    trelloRequest('/members/me')
                ]);

                if (!boards || !boards.length) {
                    state.tasks = [];
                    renderDashboard();
                    statusMessage.innerHTML = 'No Trello boards found for <strong>' + esc(member.fullName || member.username || 'this account') + '</strong>.';
                    return;
                }

                const boardCardPromises = boards.map(function (board) {
                    return Promise.all([
                        trelloRequest(`/boards/${board.id}/lists?fields=id,name`),
                        // Fetch cards with all fields needed for admin-employee sync
                        trelloRequest(`/boards/${board.id}/cards?fields=all&checklists=all`)
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
                        collected.push({
                            cardId: card.id,
                            cardName: card.name || 'Untitled Task',
                            orderRef: orderRef,
                            due: card.due,
                            listName: listMap.get(card.idList) || 'Backlog',
                            description: card.desc || '',
                            checklists: cardChecklists
                        });
                    });
                });

                state.tasks = collected;
                renderDashboard();
                statusMessage.innerHTML = 'Connected to Trello. Showing tasks for <strong>' + esc(state.selectedEmployee) + '</strong>.';
            } catch (error) {
                state.tasks = [];
                renderDashboard();
                statusMessage.innerHTML = 'Could not load Trello data. Make sure token and API settings are available.';
                console.error('Employee Trello load failed:', error);
            }
        }

        function setupTabs() {
            const tabLinks = document.querySelectorAll('#employeeTabMenu a[data-tab]');
            const sections = {
                overview: document.getElementById('tab-overview'),
                tasks: document.getElementById('tab-tasks'),
                notifications: document.getElementById('tab-notifications')
            };

            tabLinks.forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    const tab = link.dataset.tab;

                    tabLinks.forEach(function (l) { l.classList.remove('active'); });
                    Object.values(sections).forEach(function (section) {
                        section.classList.remove('active');
                    });

                    link.classList.add('active');
                    if (sections[tab]) {
                        sections[tab].classList.add('active');
                    }
                });
            });
        }

        function setupEmployeeFilter() {
            const employeeFilter = document.getElementById('employeeFilter');
            employeeFilter.addEventListener('change', async function () {
                state.selectedEmployee = employeeFilter.value;
                await loadEmployeeTasks();
            });
        }

        document.addEventListener('DOMContentLoaded', async function () {
            setupTabs();
            setupEmployeeFilter();
            setupTaskDetailsOpen();
            await loadEmployeeTasks();
            
            // Setup periodic refresh to sync with admin changes
            // Refresh every 30 seconds to keep data fresh while not overwhelming the API
            setInterval(async function () {
                console.log('Auto-refreshing employee tasks data...');
                await loadEmployeeTasks();
            }, 30000);
        });
    </script>
</body>
</html>
