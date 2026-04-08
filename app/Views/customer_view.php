<?= view('include/head_view', ['title' => $title]); ?>

<style>
    .dashboard { flex: 1 0 auto; padding: 28px 0 34px; }
    .dashboard-grid { display: grid; grid-template-columns: 280px 1fr; gap: 20px; align-items: start; }
    .dashboard-grid.sidebar-hidden { grid-template-columns: 1fr; }
    .dashboard-grid.sidebar-hidden .sidebar { display: none; }

    .sidebar { 
        background: var(--card); border: 1px solid var(--line); 
        border-radius: 12px; box-shadow: var(--shadow); padding: 16px 12px; 
    }

    .sidebar-toggle-btn { 
        display: inline-flex; align-items: center; justify-content: center; gap: 8px; 
        border: 1px solid #cfd8ea; border-radius: 10px; padding: 9px 12px; 
        font-family: inherit; font-size: 0.88rem; font-weight: 700; 
        color: #1a2b5f; background: #f3f6fc; cursor: pointer; 
    }

    .sidebar-hide-wrap { display: flex; justify-content: flex-end; margin: 8px 8px 12px; }
    .sidebar-show-btn { 
        position: fixed; left: 14px; top: 92px; z-index: 50; 
        display: none; box-shadow: 0 10px 18px rgba(16, 34, 79, 0.18); 
    }

    .profile { border-bottom: 1px solid #edf2f8; padding: 6px 8px 12px; margin-bottom: 10px; }
    .profile h4 { margin: 0 0 6px; color: #1f2f63; font-size: 1.08rem; }
    .profile .badge { 
        display: inline-block; font-size: 0.72rem; font-weight: 700; 
        color: #ffffff; background: #28a745; /* Green for Customer */
        padding: 3px 9px; border-radius: 999px; 
    }

    .menu { list-style: none; margin: 0; padding: 0; display: grid; gap: 4px; }
    .menu li a { 
        display: block; border-radius: 8px; padding: 11px 12px; 
        color: #2f3857; font-weight: 600; font-size: 0.95rem; 
        border-left: 3px solid transparent; transition: all 0.2s ease; 
    }
    .menu li a.active { 
        background: linear-gradient(90deg, #e8f1ff 0%, #f3f6fc 100%); 
        color: #1a2b5f; border-left-color: #1e91d6; 
        box-shadow: inset 0 2px 8px rgba(30, 145, 214, 0.1); 
    }
    .menu li a:hover { background: #f3f6fc; color: #152a5e; border-left-color: #d0e4f7; }

    .content { display: grid; gap: 16px; }
    .content-section { display: none; }
    .content-section.active { display: block; }

    .stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
    .stat { background: var(--card); border: 1px solid #b8d7eb; border-radius: 10px; padding: 14px 16px; box-shadow: 0 6px 12px rgba(16, 34, 79, 0.05); }
    .stat h5 { margin: 0; color: #4f5871; font-size: 0.92rem; font-weight: 600; }
    .stat strong { display: block; margin-top: 6px; color: #1f2f63; font-size: 2.05rem; line-height: 1; }

    .panels { display: grid; grid-template-columns: 2fr 1.2fr; gap: 14px; margin-top: 14px; }
    .panel { background: var(--card); border: 1px solid #b8d7eb; border-radius: 10px; padding: 14px; box-shadow: 0 8px 16px rgba(16, 34, 79, 0.05); }
    
    .recent-orders-shell { border: 1px solid #b8d7eb; border-radius: 8px; overflow: hidden; background: #ffffff; }
    .table-head { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 8px; border-bottom: 1px solid #b8d7eb; padding: 10px 12px; color: #5a637d; font-size: 0.88rem; font-weight: 600; background: #f9fbff; }
    .table-body { min-height: 200px; display: grid; place-items: center; color: #7a849d; font-weight: 600; }

    @media (max-width: 900px) {
        .dashboard-grid { grid-template-columns: 1fr; }
        .stats { grid-template-columns: 1fr; }
        .panels { grid-template-columns: 1fr; }
    }
</style>

<body>
    <?= view('include/nav_view', ['activePage' => '', 'userLabel' => $username]); ?>

    <main class="dashboard">
        <div class="container dashboard-grid">
            <aside class="sidebar">
                <div class="profile">
                    <h4><?= esc($username) ?></h4>
                    <span class="badge">Customer</span>
                </div>

                <div class="sidebar-hide-wrap">
                    <button type="button" class="sidebar-toggle-btn" id="sidebar-hide-btn">Hide Menu</button>
                </div>

                <ul class="menu">
                    <li><a href="#" class="active" data-section="customer-overview">Dashboard Overview</a></li>
                    <li><a href="#" data-section="my-orders">My Orders</a></li>
                    <li><a href="#" data-section="saved-designs">My Designs</a></li>
                    <li><a href="#" data-section="account-settings">Settings</a></li>
                </ul>
            </aside>

            <button type="button" class="sidebar-toggle-btn sidebar-show-btn" id="sidebar-show-btn">Show Menu</button>

            <section class="content">
                <div id="customer-overview" class="content-section active">
                    <div class="stats">
                        <article class="stat"><h5>Active Orders</h5><strong><?= $orderCount ?></strong></article>
                        <article class="stat"><h5>Saved Designs</h5><strong><?= $designCount ?></strong></article>
                        <article class="stat"><h5>Notifications</h5><strong><?= $notifCount ?></strong></article>
                    </div>
                    <div class="panels">
                        <article class="panel">
                            <h3>Current Orders</h3>
                            <div class="recent-orders-shell">
                                <div class="table-head">
                                    <span>Order ID</span><span>Status</span><span>Total</span><span>Date</span>
                                </div>
                                <div class="table-body">No orders found.</div>
                            </div>
                        </article>
                        <article class="panel">
                            <h3>Quick Actions</h3>
                            <p style="font-size: 0.9rem; color: #666;">Need a new print? Start a custom design now.</p>
                            <a href="<?= base_url('products') ?>" class="sidebar-toggle-btn" style="text-decoration:none; width:100%; margin-top:10px;">Create New Design</a>
                        </article>
                    </div>
                </div>

                <div id="my-orders" class="content-section"><h2>My Order History</h2></div>
                <div id="saved-designs" class="content-section"><h2>My Cloudinary Gallery</h2></div>
                <div id="account-settings" class="content-section"><h2>Account Settings</h2></div>

            </section>
        </div>
    </main>

    <?= view('include/foot_view'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const links = document.querySelectorAll('.menu a[data-section]');
            const sections = document.querySelectorAll('.content .content-section');
            const dashboardGrid = document.querySelector('.dashboard-grid');

            // SECTION SWITCHING
            links.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const sectionId = link.dataset.section;
                    sections.forEach(s => s.classList.toggle('active', s.id === sectionId));
                    links.forEach(l => l.classList.toggle('active', l === link));
                });
            });

            // SIDEBAR TOGGLE
            const applySidebar = (isHidden) => {
                dashboardGrid.classList.toggle('sidebar-hidden', isHidden);
                document.querySelector('#sidebar-show-btn').style.display = isHidden ? 'inline-flex' : 'none';
                localStorage.setItem('customer_sidebar_state', isHidden ? 'hidden' : 'visible');
            };

            // On Page Load:
            if (localStorage.getItem('customer_sidebar_state') === 'hidden') applySidebar(true);

            document.querySelector('#sidebar-hide-btn').onclick = () => applySidebar(true);
            document.querySelector('#sidebar-show-btn').onclick = () => applySidebar(false);
        });
    </script>
</body>
</html>