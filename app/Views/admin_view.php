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
            --muted: #5f677d;
            --line: #d7deea;
            --accent: #1e91d6;
            --danger: #c81e1e;
            --shadow: 0 12px 22px rgba(16, 34, 79, 0.1);
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
        }

        html {
            font-size: 17px;
        }

        body {
            margin: 0;
            font-family: "Sora", sans-serif;
            color: var(--ink);
            background: linear-gradient(180deg, #f2f5fb 0%, #e9eef7 100%);
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

        .main-nav a.active::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 3px;
            border-radius: 99px;
            background: #13265b;
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
            border: 0;
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
            padding: 28px 0 34px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 20px;
            align-items: start;
        }

        .dashboard-grid.sidebar-hidden {
            grid-template-columns: 1fr;
        }

        .dashboard-grid.sidebar-hidden .sidebar {
            display: none;
        }

        .sidebar {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 16px 12px;
        }

        .sidebar-toggle-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 1px solid #cfd8ea;
            border-radius: 10px;
            padding: 9px 12px;
            font-family: inherit;
            font-size: 0.88rem;
            font-weight: 700;
            color: #1a2b5f;
            background: #f3f6fc;
            cursor: pointer;
        }

        .sidebar-hide-wrap {
            display: flex;
            justify-content: flex-end;
            margin: 8px 8px 12px;
        }

        .sidebar-show-btn {
            position: fixed;
            left: 14px;
            top: 92px;
            z-index: 50;
            display: none;
            box-shadow: 0 10px 18px rgba(16, 34, 79, 0.18);
        }

        .profile {
            border-bottom: 1px solid #edf2f8;
            padding: 6px 8px 12px;
            margin-bottom: 10px;
        }

        .profile h4 {
            margin: 0 0 6px;
            color: #1f2f63;
            font-size: 1.08rem;
        }

        .profile .badge {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 700;
            color: #ffffff;
            background: var(--danger);
            padding: 3px 9px;
            border-radius: 999px;
        }

        .menu {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 4px;
        }

        .menu li a {
            display: block;
            border-radius: 8px;
            padding: 11px 12px;
            color: #2f3857;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .menu li a.active,
        .menu li a:hover {
            background: #f3f6fc;
            color: #152a5e;
        }

        .content {
            display: grid;
            gap: 16px;
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .stat {
            background: var(--card);
            border: 1px solid #b8d7eb;
            border-radius: 10px;
            padding: 14px 16px;
            box-shadow: 0 6px 12px rgba(16, 34, 79, 0.05);
        }

        .stat h5 {
            margin: 0;
            color: #4f5871;
            font-size: 0.92rem;
            font-weight: 600;
        }

        .stat strong {
            display: block;
            margin-top: 6px;
            color: #1f2f63;
            font-size: 2.05rem;
            line-height: 1;
        }

        .panels {
            display: grid;
            grid-template-columns: 2fr 1.2fr;
            gap: 14px;
            margin-top: 14px;
        }

        .panel {
            background: var(--card);
            border: 1px solid #b8d7eb;
            border-radius: 10px;
            padding: 14px;
            box-shadow: 0 8px 16px rgba(16, 34, 79, 0.05);
        }

        .panel h3 {
            margin: 0 0 10px;
            color: #1f2430;
            font-size: 1.22rem;
        }

        .table-head {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 8px;
            border: 1px solid #b8d7eb;
            border-radius: 8px;
            padding: 10px 12px;
            color: #5a637d;
            font-size: 0.88rem;
            font-weight: 600;
        }

        .table-body {
            margin-top: 8px;
            min-height: 320px;
            border: 1px solid #b8d7eb;
            border-radius: 8px;
            background: #f9fbff;
            display: grid;
            place-items: center;
            color: #7a849d;
            font-size: 1rem;
            font-weight: 600;
        }

        .status-row {
            margin: 10px 0 14px;
        }

        .status-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 0.97rem;
            font-weight: 700;
            color: #2a3150;
        }

        .track {
            width: 100%;
            height: 10px;
            border-radius: 99px;
            background: #d7deec;
            overflow: hidden;
        }

        .bar {
            display: block;
            height: 100%;
            background: #7dc0e8;
            width: 0;
        }

        .inventory-list {
            display: grid;
            gap: 8px;
            margin-top: 8px;
        }

        .inventory-item {
            border: 1px solid #b8d7eb;
            border-radius: 8px;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
            font-size: 0.92rem;
            color: #4d5670;
        }

        .inventory-item strong {
            color: #2a3150;
        }

        .task-frame-panel {
            background: var(--card);
            border: 1px solid #b8d7eb;
            border-radius: 10px;
            padding: 14px;
            box-shadow: 0 8px 16px rgba(16, 34, 79, 0.05);
        }

        .task-frame-panel h3 {
            margin: 0 0 10px;
            color: #1f2430;
            font-size: 1.22rem;
        }

        .task-embed {
            width: 100%;
            height: 84vh;
            border: 1px solid #d8e0ee;
            border-radius: 10px;
            background: #ffffff;
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

            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
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
                    <h4>Admin User</h4>
                    <span class="badge">Admin</span>
                </div>

                <div class="sidebar-hide-wrap">
                    <button type="button" class="sidebar-toggle-btn" id="sidebar-hide-btn">Hide Menu</button>
                </div>

                <ul class="menu">
                    <li><a href="#" class="active" data-section="dashboard-overview">Dashboard Overview</a></li>
                    <li><a href="#">Notification</a></li>
                    <li><a href="#" data-section="calendar-management">Calendar</a></li>
                    <li><a href="#">Gallery Management</a></li>
                    <li><a href="#">Order Management</a></li>
                    <li><a href="#">Inventory Management</a></li>
                    <li><a href="#">Account Management</a></li>
                    <li><a href="#" data-section="task-management">Task Management</a></li>
                    <li><a href="#">Discount</a></li>
                    <li><a href="#">Control Management</a></li>
                </ul>
            </aside>

            <button type="button" class="sidebar-toggle-btn sidebar-show-btn" id="sidebar-show-btn">Show Menu</button>

            <section class="content">
                <div id="dashboard-overview" class="content-section active">
                    <div class="stats">
                        <article class="stat">
                            <h5>Orders</h5>
                            <strong>0</strong>
                        </article>
                        <article class="stat">
                            <h5>Customer</h5>
                            <strong>0</strong>
                        </article>
                        <article class="stat">
                            <h5>Inventory</h5>
                            <strong>0</strong>
                        </article>
                        <article class="stat">
                            <h5>Calendar</h5>
                            <strong>0</strong>
                        </article>
                    </div>

                    <div class="panels">
                        <article class="panel">
                            <h3>Recent Orders</h3>
                            <div class="table-head">
                                <span>Orders</span>
                                <span>Customer</span>
                                <span>Status</span>
                                <span>Total</span>
                                <span>Created</span>
                            </div>
                            <div class="table-body">No orders yet.</div>
                        </article>

                        <div class="content">
                            <article class="panel">
                                <h3>Order Status Breakdown</h3>
                                <div class="status-row">
                                    <div class="status-head"><span>Pending</span><span>0</span></div>
                                    <div class="track"><span class="bar" style="width:0%"></span></div>
                                </div>
                                <div class="status-row">
                                    <div class="status-head"><span>Processing</span><span>0</span></div>
                                    <div class="track"><span class="bar" style="width:0%"></span></div>
                                </div>
                                <div class="status-row">
                                    <div class="status-head"><span>Completed</span><span>0</span></div>
                                    <div class="track"><span class="bar" style="width:0%"></span></div>
                                </div>
                                <div class="status-row">
                                    <div class="status-head"><span>Cancelled</span><span>0</span></div>
                                    <div class="track"><span class="bar" style="width:0%"></span></div>
                                </div>
                            </article>

                            <article class="panel">
                                <h3>Inventory</h3>
                                <div class="inventory-list">
                                    <div class="inventory-item">
                                        <span>SKU INK-MG-002 -<br>0 left (threshold 10)</span>
                                        <strong>Low Stock<br>Last Updated: -</strong>
                                    </div>
                                    <div class="inventory-item">
                                        <span>SKU INK-MG-002 -<br>0 left (threshold 10)</span>
                                        <strong>Low Stock<br>Last Updated: -</strong>
                                    </div>
                                    <div class="inventory-item">
                                        <span>SKU INK-MG-002 -<br>0 left (threshold 10)</span>
                                        <strong>Low Stock<br>Last Updated: -</strong>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>

                <article class="task-frame-panel content-section" id="calendar-management">
                    <h3>Calendar</h3>
                    <iframe
                        class="task-embed"
                        id="calendar-management-frame"
                        src="about:blank"
                        data-src="<?= base_url('trello-task/index.html?view=calendar'); ?>"
                        title="Trello Calendar"
                        loading="lazy"
                        referrerpolicy="no-referrer"
                    ></iframe>
                </article>

                <article class="task-frame-panel content-section" id="task-management">
                    <h3>Task Management</h3>
                    <iframe
                        class="task-embed"
                        id="task-management-frame"
                        src="about:blank"
                        data-src="<?= base_url('trello-task/index.html?view=tasks'); ?>"
                        title="Trello Task Management"
                        loading="lazy"
                        referrerpolicy="no-referrer"
                    ></iframe>
                </article>
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const links = document.querySelectorAll('.menu a[data-section]');
            const sections = document.querySelectorAll('.content .content-section');
            const taskFrame = document.querySelector('#task-management-frame');
            const calendarFrame = document.querySelector('#calendar-management-frame');
            const dashboardGrid = document.querySelector('.dashboard-grid');
            const sidebarHideBtn = document.querySelector('#sidebar-hide-btn');
            const sidebarShowBtn = document.querySelector('#sidebar-show-btn');
            const sidebarStateKey = 'adminSidebarHidden';

            const applySidebarState = (isHidden) => {
                if (!dashboardGrid || !sidebarShowBtn) {
                    return;
                }

                dashboardGrid.classList.toggle('sidebar-hidden', isHidden);
                sidebarShowBtn.style.display = isHidden ? 'inline-flex' : 'none';
                localStorage.setItem(sidebarStateKey, isHidden ? '1' : '0');
            };

            const openSection = (sectionId, activeLink) => {
                sections.forEach(section => {
                    section.classList.toggle('active', section.id === sectionId);
                });

                links.forEach(link => {
                    link.classList.toggle('active', link === activeLink);
                });

                if (sectionId === 'task-management' && taskFrame && taskFrame.src === 'about:blank') {
                    taskFrame.src = taskFrame.dataset.src;
                }

                if (sectionId === 'calendar-management' && calendarFrame && calendarFrame.src === 'about:blank') {
                    calendarFrame.src = calendarFrame.dataset.src;
                }
            };

            links.forEach(link => {
                link.addEventListener('click', (event) => {
                    event.preventDefault();
                    openSection(link.dataset.section, link);
                });
            });

            if (sidebarHideBtn) {
                sidebarHideBtn.addEventListener('click', () => applySidebarState(true));
            }

            if (sidebarShowBtn) {
                sidebarShowBtn.addEventListener('click', () => applySidebarState(false));
            }

            applySidebarState(localStorage.getItem(sidebarStateKey) === '1');
        });
    </script>
</body>
</html>
