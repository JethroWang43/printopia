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
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }

        .menu li a.active {
            background: linear-gradient(90deg, #e8f1ff 0%, #f3f6fc 100%);
            color: #1a2b5f;
            border-left-color: #1e91d6;
            box-shadow: inset 0 2px 8px rgba(30, 145, 214, 0.1);
        }

        .menu li a:hover {
            background: #f3f6fc;
            color: #152a5e;
            border-left-color: #d0e4f7;
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

        #dashboard-overview {
            position: relative;
            min-height: 420px;
        }

        .dashboard-loading-overlay {
            position: absolute;
            inset: 0;
            z-index: 12;
            background: rgba(245, 249, 255, 0.92);
            border: 1px solid #d9e4f4;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: all;
            transition: opacity 220ms ease, visibility 220ms ease;
        }

        .dashboard-loading-overlay.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .dashboard-loading-card {
            min-width: 240px;
            display: grid;
            place-items: center;
            gap: 10px;
            padding: 18px 20px;
            border: 1px solid #cfe0f5;
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0 8px 16px rgba(16, 34, 79, 0.08);
            color: #29416d;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .dashboard-loading-spinner {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 3px solid #d7e4f7;
            border-top-color: #2a80c7;
            animation: dashboardSpin 800ms linear infinite;
        }

        @keyframes dashboardSpin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
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

        .recent-orders-shell {
            border: 1px solid #b8d7eb;
            border-radius: 8px;
            overflow: hidden;
            background: #ffffff;
        }

        .table-head {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 8px;
            border-bottom: 1px solid #b8d7eb;
            padding: 10px 12px;
            margin: 0;
            color: #5a637d;
            font-size: 0.88rem;
            font-weight: 600;
            background: #f9fbff;
        }

        .table-body {
            margin-top: 0;
            min-height: 320px;
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
            transition: width 700ms cubic-bezier(0.22, 1, 0.36, 1);
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
                    <li><a href="#" data-section="gallery-management">Gallery Management</a></li>
                    <li><a href="#" data-section="order-management">Order Management</a></li>
                    <li><a href="#" data-section="inventory-management">Inventory Management</a></li>
                    <li><a href="#" data-section="account-management">Account Management</a></li>
                    <li><a href="#" data-section="task-management">Task Management</a></li>
                    <li><a href="#" data-section="discount-management">Discount</a></li>
                    <li><a href="#" data-section="control-management">Control Management</a></li>
                </ul>
            </aside>

            <button type="button" class="sidebar-toggle-btn sidebar-show-btn" id="sidebar-show-btn">Show Menu</button>

            <section class="content">
                <div id="dashboard-overview" class="content-section active">
                    <div class="dashboard-loading-overlay" id="dashboardLoadingOverlay" aria-live="polite" aria-busy="true">
                        <div class="dashboard-loading-card">
                            <div class="dashboard-loading-spinner" aria-hidden="true"></div>
                            <span id="dashboardLoadingText">Loading dashboard data...</span>
                        </div>
                    </div>

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
                            <strong id="calendarCount">0</strong>
                        </article>
                    </div>

                    <div class="panels">
                        <article class="panel">
                            <h3>Recent Orders</h3>
                            <div class="recent-orders-shell">
                                <div class="table-head">
                                    <span>Orders</span>
                                    <span>Customer</span>
                                    <span>Status</span>
                                    <span>Total</span>
                                    <span>Created</span>
                                </div>
                                <div class="table-body">No orders yet.</div>
                            </div>
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
                                
                                <style>
                                    .inventory-filter-buttons {
                                        display: flex;
                                        gap: 6px;
                                        margin-bottom: 10px;
                                        flex-wrap: wrap;
                                    }
                                    
                                    .inventory-filter-btn {
                                        flex: 1;
                                        min-width: 60px;
                                        padding: 6px 8px;
                                        border: 1px solid #ddd;
                                        border-radius: 6px;
                                        background: #fff;
                                        font-size: 0.75rem;
                                        font-weight: 600;
                                        cursor: pointer;
                                        color: #666;
                                        transition: all 0.15s ease;
                                    }
                                    
                                    .inventory-filter-btn:hover {
                                        background: #f0f0f0;
                                    }
                                    
                                    .inventory-filter-btn.active {
                                        background: #132659;
                                        color: #fff;
                                        border-color: #132659;
                                    }
                                    
                                    .inventory-list-scroll {
                                        max-height: 320px;
                                        overflow-y: auto;
                                        border: 1px solid #e0e0e0;
                                        border-radius: 6px;
                                        padding: 8px;
                                    }
                                    
                                    .inventory-list-scroll::-webkit-scrollbar {
                                        width: 6px;
                                    }
                                    
                                    .inventory-list-scroll::-webkit-scrollbar-thumb {
                                        background: #ccc;
                                        border-radius: 3px;
                                    }
                                    
                                    .inventory-list-scroll::-webkit-scrollbar-track {
                                        background: #f5f5f5;
                                    }
                                    
                                    .inventory-item-scrollable {
                                        padding: 8px;
                                        border-radius: 6px;
                                        margin-bottom: 6px;
                                        background: #f9f9f9;
                                        border-left: 4px solid #999;
                                        font-size: 0.85rem;
                                    }
                                    
                                    .inventory-item-scrollable.good {
                                        border-left-color: #0b84cf;
                                        background: #f0f7ff;
                                    }
                                    
                                    .inventory-item-scrollable.warn {
                                        border-left-color: #c98a16;
                                        background: #fff9f0;
                                    }
                                    
                                    .inventory-item-scrollable.restock {
                                        border-left-color: #ce1f1f;
                                        background: #fff0f0;
                                    }
                                    
                                    .inventory-item-scrollable strong {
                                        display: block;
                                        color: #132659;
                                        margin-bottom: 2px;
                                        font-weight: 700;
                                    }
                                    
                                    .inventory-item-scrollable span {
                                        display: block;
                                        color: #666;
                                        font-size: 0.8rem;
                                        line-height: 1.3;
                                    }
                                </style>
                                
                                <div class="inventory-filter-buttons">
                                    <button class="inventory-filter-btn active" data-filter="all">All</button>
                                    <button class="inventory-filter-btn" data-filter="good">Good</button>
                                    <button class="inventory-filter-btn" data-filter="warn">Warning</button>
                                    <button class="inventory-filter-btn" data-filter="restock">Re-Stock</button>
                                </div>
                                
                                <div class="inventory-list-scroll" id="sidebarInventoryList">
                                    <div style="text-align:center; color:#999; padding:20px; font-size:0.9rem;">Loading inventory...</div>
                                </div>
                            </article>

                            <script>
                                // Global inventory refres function for sidebar
                                window.refreshSidebarInventory = window.refreshSidebarInventory || (async function() {
                                    try {
                                        const response = await fetch('<?= base_url('admin/inventory/list'); ?>');
                                        const data = await response.json();
                                        const items = Array.isArray(data?.data) ? data.data : [];
                                        
                                        const container = document.getElementById('sidebarInventoryList');
                                        if (!container) return;
                                        
                                        const computeStatus = (item) => {
                                            const qty = Number(item.stock_qty) || 0;
                                            const reorder = Number(item.reorder_level) || 0;
                                            if (qty <= reorder) return 'restock';
                                            if (qty <= reorder + 5) return 'warn';
                                            return 'good';
                                        };
                                        
                                        const statusLabels = { good: 'Good', warn: 'Low Stock', restock: 'Re-Stock' };
                                        const selectedFilter = document.querySelector('.inventory-filter-btn.active')?.dataset.filter || 'all';
                                        const filtered = selectedFilter === 'all' 
                                            ? items 
                                            : items.filter(item => computeStatus(item) === selectedFilter);
                                        
                                        if (!filtered.length) {
                                            container.innerHTML = '<div style="text-align:center; color:#999; padding:20px; font-size:0.9rem;">No items in this category</div>';
                                            return;
                                        }
                                        
                                        container.innerHTML = filtered.map(item => {
                                            const status = computeStatus(item);
                                            const qty = Number(item.stock_qty) || 0;
                                            const reorder = Number(item.reorder_level) || 0;
                                            
                                            return `
                                                <div class="inventory-item-scrollable ${status}">
                                                    <strong>${statusLabels[status]}</strong>
                                                    <span>${item.description}</span>
                                                    <span>${qty} left (threshold ${reorder}) • #${item.inventory_id}</span>
                                                </div>
                                            `;
                                        }).join('');
                                    } catch (error) {
                                        console.error('Failed to refresh sidebar inventory:', error);
                                    }
                                });
                                
                                (async function() {
                                    let allItems = [];
                                    let selectedFilter = 'all';
                                    
                                    const computeStatus = (item) => {
                                        const qty = Number(item.stock_qty) || 0;
                                        const reorder = Number(item.reorder_level) || 0;
                                        if (qty <= reorder) return 'restock';
                                        if (qty <= reorder + 5) return 'warn';
                                        return 'good';
                                    };
                                    
                                    const statusLabels = { good: 'Good', warn: 'Low Stock', restock: 'Re-Stock' };
                                    
                                    const renderItems = () => {
                                        const container = document.getElementById('sidebarInventoryList');
                                        const filtered = selectedFilter === 'all' 
                                            ? allItems 
                                            : allItems.filter(item => computeStatus(item) === selectedFilter);
                                        
                                        if (!filtered.length) {
                                            container.innerHTML = '<div style="text-align:center; color:#999; padding:20px; font-size:0.9rem;">No items in this category</div>';
                                            return;
                                        }
                                        
                                        container.innerHTML = filtered.map(item => {
                                            const status = computeStatus(item);
                                            const qty = Number(item.stock_qty) || 0;
                                            const reorder = Number(item.reorder_level) || 0;
                                            
                                            return `
                                                <div class="inventory-item-scrollable ${status}">
                                                    <strong>${statusLabels[status]}</strong>
                                                    <span>${item.description}</span>
                                                    <span>${qty} left (threshold ${reorder}) • #${item.inventory_id}</span>
                                                </div>
                                            `;
                                        }).join('');
                                    };
                                    
                                    // Setup filter buttons
                                    document.querySelectorAll('.inventory-filter-btn').forEach(btn => {
                                        btn.addEventListener('click', () => {
                                            document.querySelectorAll('.inventory-filter-btn').forEach(b => b.classList.remove('active'));
                                            btn.classList.add('active');
                                            selectedFilter = btn.dataset.filter;
                                            renderItems();
                                        });
                                    });
                                    
                                    // Load data
                                    try {
                                        const response = await fetch('<?= base_url('admin/inventory/list'); ?>');
                                        const data = await response.json();
                                        allItems = Array.isArray(data?.data) ? data.data : [];
                                        
                                        if (!allItems.length) {
                                            document.getElementById('sidebarInventoryList').innerHTML = '<div style="text-align:center; color:#999; padding:20px; font-size:0.9rem;">No inventory items</div>';
                                            return;
                                        }
                                        
                                        renderItems();
                                    } catch (error) {
                                        console.error('Failed to load sidebar inventory:', error);
                                        document.getElementById('sidebarInventoryList').innerHTML = '<div style="text-align:center; color:#999; padding:20px; font-size:0.9rem;">Error loading</div>';
                                    }
                                })();
                            </script>
                        </div>
                    </div>
                </div>

                <?= view('admin_tab/calendar_tab'); ?>

                <?= view('admin_tab/notification_tab'); ?>

                <?= view('admin_tab/gallery_tab'); ?>

                <?= view('admin_tab/order_tab'); ?>

                <?= view('admin_tab/account_tab'); ?>

                <?= view('admin_tab/Inventory_tab'); ?>

                <?= view('admin_tab/discount_tab'); ?>

                <?= view('admin_tab/task_tab'); ?>

                <?= view('admin_tab/control_tab'); ?>

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
            const calendarCountEl = document.querySelector('#calendarCount');
            const dashboardLoadingOverlay = document.querySelector('#dashboardLoadingOverlay');
            const dashboardLoadingText = document.querySelector('#dashboardLoadingText');
            const dashboardGrid = document.querySelector('.dashboard-grid');
            const sidebarHideBtn = document.querySelector('#sidebar-hide-btn');
            const sidebarShowBtn = document.querySelector('#sidebar-show-btn');
            const sidebarStateKey = 'adminSidebarHidden';
            const controlSettingsKey = 'printopiaControlSettingsV1';
            let dashboardSummaryReceived = false;

            const setDashboardLoading = (isLoading, text) => {
                if (!dashboardLoadingOverlay) {
                    return;
                }

                dashboardLoadingOverlay.classList.toggle('hidden', !isLoading);
                dashboardLoadingOverlay.setAttribute('aria-busy', isLoading ? 'true' : 'false');
                if (dashboardLoadingText && text) {
                    dashboardLoadingText.textContent = text;
                }
            };

            const normalize = (value) => String(value || '').trim().toLowerCase();
            const normalizeEmployeeKey = (value) => normalize(value).replace(/[^a-z0-9]+/g, ' ').replace(/\s+/g, ' ').trim();

            const currentViewerRole = 'admin';

            const currentViewerName = normalizeEmployeeKey(
                new URLSearchParams(window.location.search).get('employee') ||
                new URLSearchParams(window.location.search).get('name') ||
                localStorage.getItem('printopiaCurrentUserName') ||
                ''
            );

            const defaultControlSettings = {
                adminSections: {
                    'notification-management': true,
                    'calendar-management': true,
                    'gallery-management': true,
                    'order-management': true,
                    'inventory-management': true,
                    'account-management': true,
                    'task-management': true,
                    'discount-management': true,
                    'control-management': true,
                },
                adminEmployeeAccess: {
                    'notification-management': {},
                    'calendar-management': {},
                    'gallery-management': {},
                    'order-management': {},
                    'inventory-management': {},
                    'account-management': {},
                    'task-management': {},
                    'discount-management': {},
                    'control-management': {},
                },
                employeeTabs: {
                    overview: true,
                    tasks: true,
                    notifications: true,
                }
            };

            const loadControlSettings = () => {
                try {
                    const raw = JSON.parse(localStorage.getItem(controlSettingsKey) || '{}');
                    return {
                        ...defaultControlSettings,
                        adminSections: {
                            ...defaultControlSettings.adminSections,
                            ...(raw.adminSections || {}),
                        },
                        adminEmployeeAccess: Object.keys(defaultControlSettings.adminEmployeeAccess).reduce((acc, sectionKey) => {
                            acc[sectionKey] = {
                                ...defaultControlSettings.adminEmployeeAccess[sectionKey],
                                ...(raw?.adminEmployeeAccess?.[sectionKey] || {}),
                            };
                            return acc;
                        }, {}),
                        employeeTabs: {
                            ...defaultControlSettings.employeeTabs,
                            ...(raw.employeeTabs || {}),
                        }
                    };
                } catch (error) {
                    return defaultControlSettings;
                }
            };

            const applyControlSettings = () => {
                const settings = loadControlSettings();
                const sectionStates = settings.adminSections || {};

                if (currentViewerRole === 'admin') {
                    links.forEach((link) => {
                        link.parentElement.style.display = '';
                    });

                    return;
                }

                Object.entries(sectionStates).forEach(([sectionId, isEnabled]) => {
                    const link = document.querySelector(`.menu a[data-section="${sectionId}"]`);
                    const section = document.getElementById(sectionId);
                    const employeeAccessMap = settings?.adminEmployeeAccess?.[sectionId] || {};
                    const assignedEmployees = Object.keys(employeeAccessMap).filter((employeeKey) => employeeAccessMap[employeeKey]);
                    const employeeAllowed = currentViewerRole === 'admin' || assignedEmployees.includes(currentViewerName);
                    const visible = Boolean(isEnabled) && employeeAllowed;

                    if (link) {
                        link.parentElement.style.display = visible ? '' : 'none';
                    }

                    if (section && !visible) {
                        section.classList.remove('active');
                    }
                });

                const activeVisibleLink = Array.from(links).find((link) => {
                    return link.classList.contains('active') && link.parentElement.style.display !== 'none';
                });

                if (!activeVisibleLink) {
                    const fallbackLink = document.querySelector('.menu a[data-section="dashboard-overview"]');
                    if (fallbackLink) {
                        openSection('dashboard-overview', fallbackLink);
                    }
                }
            };

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
                const total = Number(summary?.total || 0);

                if (calendarCountEl) {
                    calendarCountEl.textContent = String(total);
                }

                statuses.forEach((status) => {
                    const countEl = document.querySelector(`[data-status-count="${status}"]`);
                    const barEl = document.querySelector(`[data-status-bar="${status}"]`);

                    const count = Number(counts[status] || 0);
                    const pct = Math.max(0, Math.min(100, Number(percentages[status] || 0)));

                    if (countEl) {
                        countEl.textContent = String(count);
                    }
                    if (barEl) {
                        // Animate width updates instead of instant jumps.
                        requestAnimationFrame(() => {
                            barEl.style.width = `${pct}%`;
                        });
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

                document.dispatchEvent(new CustomEvent('printopia:section-opened', {
                    detail: { sectionId }
                }));
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

                dashboardSummaryReceived = true;
                setDashboardLoading(false);
                updateOrderStatusBreakdown(data.payload || {});
                emitHciInteraction('status_breakdown_updated', {
                    sourceBoard: data.payload?.boardName || 'Unknown',
                    total: data.payload?.total || 0
                });
            });

            setDashboardLoading(true, 'Loading dashboard data...');

            // Fallback: avoid blocking the dashboard forever if summary is delayed.
            setTimeout(() => {
                if (!dashboardSummaryReceived) {
                    setDashboardLoading(false);
                }
            }, 4500);

            // Guaranteed preload source for dashboard summary, independent from visible task tab iframe.
            if (taskSummaryLoader && taskSummaryLoader.src === 'about:blank' && taskSummaryLoader.dataset.src) {
                taskSummaryLoader.src = taskSummaryLoader.dataset.src;
                emitHciInteraction('summary_loader_started', { section: 'task-management' });
            }

            links.forEach(link => {
                link.addEventListener('click', (event) => {
                    event.preventDefault();
                    openSection(link.dataset.section, link);
                });
            });

            document.addEventListener('printopia:controls-updated', applyControlSettings);
            window.addEventListener('storage', (event) => {
                if (event.key === controlSettingsKey) {
                    applyControlSettings();
                }
            });

            if (sidebarHideBtn) {
                sidebarHideBtn.addEventListener('click', () => applySidebarState(true));
            }

            if (sidebarShowBtn) {
                sidebarShowBtn.addEventListener('click', () => applySidebarState(false));
            }

            applySidebarState(localStorage.getItem(sidebarStateKey) === '1');
            applyControlSettings();
        });
    </script>
</body>
</html>
