<?= view('include/head_view', ['title' => $title]); ?>

    <style>
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
            background: transparent;
            border: 0;
            border-radius: 0;
            padding: 0;
            box-shadow: none;
        }

        .task-frame-panel h3 {
            margin: 0 0 8px;
            color: #1f2430;
            font-size: 1.22rem;
        }

        .task-embed {
            width: 100%;
            height: 84vh;
            border: 0;
            border-radius: 12px;
            background: transparent;
            display: block;
            box-shadow: 0 12px 22px rgba(16, 34, 79, 0.08);
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
    <?= view('include/nav_view', ['activePage' => '', 'userLabel' => 'Admin User']); ?>

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
                    <li><a href="#" data-section="notification-management">Notification</a></li>
                    <li><a href="#" data-section="calendar-management">Calendar</a></li>
                    <li><a href="#">Gallery Management</a></li>
                    <li><a href="#" data-section="order-management">Order Management</a></li>
                    <li><a href="#" data-section="inventory-management">Inventory Management</a></li>
                    <li><a href="#">Account Management</a></li>
                    <li><a href="#" data-section="task-management">Task Management</a></li>
                    <li><a href="#" data-section="discount-management">Discount</a></li>
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
                                <div class="status-row" data-status-row="pending">
                                    <div class="status-head"><span>Pending</span><span data-status-count="pending">0</span></div>
                                    <div class="track"><span class="bar" data-status-bar="pending" style="width:0%"></span></div>
                                </div>
                                <div class="status-row" data-status-row="processing">
                                    <div class="status-head"><span>Processing</span><span data-status-count="processing">0</span></div>
                                    <div class="track"><span class="bar" data-status-bar="processing" style="width:0%"></span></div>
                                </div>
                                <div class="status-row" data-status-row="completed">
                                    <div class="status-head"><span>Completed</span><span data-status-count="completed">0</span></div>
                                    <div class="track"><span class="bar" data-status-bar="completed" style="width:0%"></span></div>
                                </div>
                                <div class="status-row" data-status-row="cancelled">
                                    <div class="status-head"><span>Cancelled</span><span data-status-count="cancelled">0</span></div>
                                    <div class="track"><span class="bar" data-status-bar="cancelled" style="width:0%"></span></div>
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

                <?= view('admin_tab/calendar_tab'); ?>

                <?= view('admin_tab/notification_tab'); ?>

                <?= view('admin_tab/order_tab'); ?>

                <?= view('admin_tab/Inventory_tab'); ?>

                <?= view('admin_tab/discount_tab'); ?>

                <?= view('admin_tab/task_tab'); ?>

                <iframe
                    id="task-summary-loader"
                    src="about:blank"
                    data-src="<?= base_url('trello-task/index.html?view=tasks'); ?>"
                    title="Task Summary Loader"
                    loading="eager"
                    referrerpolicy="no-referrer"
                    style="position:absolute;left:-9999px;top:0;width:1px;height:1px;opacity:0;pointer-events:none;border:0;"
                    aria-hidden="true"
                    tabindex="-1"
                ></iframe>
            </section>
        </div>
    </main>

    <?= view('include/foot_view'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const emitHciInteraction = (action, payload = {}) => {
                const detail = {
                    action,
                    source: 'admin-embed',
                    timestamp: new Date().toISOString(),
                    payload
                };

                document.dispatchEvent(new CustomEvent('hci:interaction', { detail }));
                if (window.parent && window.parent !== window) {
                    window.parent.postMessage({ type: 'hci:interaction', detail }, '*');
                }
            };

            const links = document.querySelectorAll('.menu a[data-section]');
            const sections = document.querySelectorAll('.content .content-section');
            const taskFrame = document.querySelector('#task-management-frame');
            const taskSummaryLoader = document.querySelector('#task-summary-loader');
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

            const updateOrderStatusBreakdown = (summary) => {
                const statuses = ['pending', 'processing', 'completed', 'cancelled'];
                const counts = summary?.counts || {};
                const percentages = summary?.percentages || {};

                statuses.forEach((status) => {
                    const countEl = document.querySelector(`[data-status-count="${status}"]`);
                    const barEl = document.querySelector(`[data-status-bar="${status}"]`);

                    const count = Number(counts[status] || 0);
                    const pct = Math.max(0, Math.min(100, Number(percentages[status] || 0)));

                    if (countEl) {
                        countEl.textContent = String(count);
                    }
                    if (barEl) {
                        barEl.style.width = `${pct}%`;
                    }
                });
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
                    emitHciInteraction('embed_view_opened', { section: 'task-management' });
                }

                if (sectionId === 'calendar-management' && calendarFrame && calendarFrame.src === 'about:blank') {
                    calendarFrame.src = calendarFrame.dataset.src;
                    emitHciInteraction('embed_view_opened', { section: 'calendar-management' });
                }
            };

            if (taskFrame) {
                taskFrame.addEventListener('load', () => {
                    emitHciInteraction('embed_loaded', { section: 'task-management' });
                });
            }

            if (calendarFrame) {
                calendarFrame.addEventListener('load', () => {
                    emitHciInteraction('embed_loaded', { section: 'calendar-management' });
                });
            }

            window.addEventListener('message', (event) => {
                const data = event.data;
                if (!data || data.type !== 'trello:board-summary') {
                    return;
                }

                updateOrderStatusBreakdown(data.payload || {});
                emitHciInteraction('status_breakdown_updated', {
                    sourceBoard: data.payload?.boardName || 'Unknown',
                    total: data.payload?.total || 0
                });
            });

            // Preload task iframe in the background so dashboard metrics can sync immediately.
            if (taskFrame && taskFrame.src === 'about:blank' && taskFrame.dataset.src) {
                taskFrame.src = taskFrame.dataset.src;
                emitHciInteraction('embed_preload_started', { section: 'task-management' });
            }

            // Guaranteed preload source for dashboard summary, independent from visible task tab iframe.
            if (taskSummaryLoader && taskSummaryLoader.src === 'about:blank' && taskSummaryLoader.dataset.src) {
                taskSummaryLoader.src = taskSummaryLoader.dataset.src;
                emitHciInteraction('summary_loader_started', { section: 'task-management' });
            }

            // Preload calendar iframe shortly after page load to reduce first-open delay.
            if (calendarFrame && calendarFrame.src === 'about:blank' && calendarFrame.dataset.src) {
                setTimeout(() => {
                    if (calendarFrame.src === 'about:blank') {
                        calendarFrame.src = calendarFrame.dataset.src;
                        emitHciInteraction('embed_preload_started', { section: 'calendar-management', delayMs: 1000 });
                    }
                }, 1000);
            }

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
