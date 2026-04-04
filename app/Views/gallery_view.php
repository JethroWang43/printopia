<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Management | Printopia</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
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
            --p-maroon: #800000;
            --shadow: 0 12px 22px rgba(16, 34, 79, 0.15);
        }

        * { box-sizing: border-box; }
        html, body { min-height: 100%; margin: 0; }
        html { font-size: 17px; }
        body { 
            font-family: "Sora", sans-serif; 
            color: var(--ink); 
            background: linear-gradient(180deg, #f2f5fb 0%, #e9eef7 100%);
            display: flex;
            flex-direction: column;
        }
        a { text-decoration: none; color: inherit; }

        .container {
            width: min(1640px, 97vw);
            margin: 0 auto;
        }

        /* --- NAVBAR STYLES --- */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 40;
            background: var(--brand-gold);
            border-bottom: 1px solid rgba(16, 34, 79, 0.15);
            box-shadow: 0 6px 14px rgba(16, 34, 79, 0.15);
        }
        .topbar-inner {
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 0 clamp(20px, 4vw, 84px);
        }
        .logo { display: flex; align-items: center; gap: 10px; font-size: 1.85rem; font-weight: 800; color: #0d1f49; }
        .logo-mark {
            width: 34px; height: 34px; border-radius: 10px; background: #0d1f49;
            color: #f8cd64; display: grid; place-items: center; font-size: 1.05rem; font-weight: 800;
        }
        .main-nav { display: flex; gap: clamp(18px, 2.3vw, 34px); color: #162757; font-size: 0.95rem; }
        .main-nav a { position: relative; padding: 8px 0; font-weight: 700; opacity: 0.92; }
        .main-nav a.active::after {
            content: ""; position: absolute; left: 0; bottom: 0; width: 100%; height: 3px;
            border-radius: 99px; background: #13265b;
        }

        /* --- USER MENU --- */
        .user-menu { position: relative; margin-left: auto; }
        .user-chip-btn {
            display: inline-flex; align-items: center; gap: 10px; border: 2px solid #122459;
            border-radius: 12px; padding: 10px 16px; color: #132559; font-size: 0.88rem;
            font-weight: 600; background: rgba(255, 255, 255, 0.28); cursor: pointer;
        }
        .user-dropdown {
            position: absolute; right: 0; top: calc(100% + 8px); min-width: 160px;
            border-radius: 10px; overflow: hidden; border: 1px solid #dbe2f0;
            background: #ffffff; box-shadow: 0 12px 20px rgba(16, 34, 79, 0.16);
            opacity: 0; visibility: hidden; transform: translateY(-6px); transition: 0.18s ease;
        }
        .user-menu:hover .user-dropdown { opacity: 1; visibility: visible; transform: translateY(0); }
        .user-role {
            display: block; width: 100%; text-align: left; padding: 10px 12px; border: 0;
            border-bottom: 1px solid #edf1f8; background: transparent; color: #273257;
            font-size: 0.86rem; font-weight: 600; cursor: pointer;
        }

        /* --- DASHBOARD GRID & SIDEBAR --- */
        .dashboard { flex: 1 0 auto; padding: 28px 0 34px; }
        .dashboard-grid { display: grid; grid-template-columns: 280px 1fr; gap: 20px; align-items: start; }
        .dashboard-grid.sidebar-hidden { grid-template-columns: 1fr; }
        .dashboard-grid.sidebar-hidden .sidebar { display: none; }

        .sidebar { background: var(--card); border: 1px solid var(--line); border-radius: 12px; box-shadow: var(--shadow); padding: 16px 12px; }
        .profile { border-bottom: 1px solid #edf2f8; padding: 6px 8px 12px; margin-bottom: 10px; }
        .profile h4 { margin: 0 0 6px; color: #1f2f63; }
        .profile .badge { display: inline-block; font-size: 0.72rem; font-weight: 700; color: #fff; background: var(--danger); padding: 3px 9px; border-radius: 999px; }
        
        .menu { list-style: none; padding: 0; display: grid; gap: 4px; }
        .menu li a { display: block; border-radius: 8px; padding: 11px 12px; color: #2f3857; font-weight: 600; font-size: 0.95rem; }
        .menu li a.active, .menu li a:hover { background: #f3f6fc; color: #152a5e; }

        /* --- NEW FILTER BAR STYLES (MATCHING IMAGES) --- */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 15px;
            background: var(--card);
            padding: 15px 25px;
            border-radius: 12px;
            border: 1px solid var(--line);
            margin-bottom: 25px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            align-items: center;
            border: 1.5px solid #2d3e63;
            border-radius: 10px;
            padding: 4px 12px;
            background: #fff;
            height: 45px;
        }

        .filter-group i { color: #2d3e63; margin-right: 10px; }
        .filter-group input, .filter-group select {
            border: none; outline: none; font-family: inherit; font-weight: 600; color: #2d3e63; background: transparent;
        }

        .view-toggle {
            display: flex; border: 1.5px solid #2d3e63; border-radius: 10px; overflow: hidden; height: 45px;
        }

        .toggle-btn {
            padding: 0 15px; display: grid; place-items: center; background: #fff; color: #2d3e63; cursor: pointer; transition: 0.2s;
        }

        .toggle-btn.active { background: var(--p-maroon); color: #fff; }

        .btn-upload-cloudinary {
            background: var(--p-maroon); color: white; border: none; padding: 0 25px; border-radius: 10px;
            font-weight: 700; height: 45px; cursor: pointer; display: flex; align-items: center; gap: 10px;
            margin-left: auto; transition: 0.3s;
        }

        /* --- GALLERY GRID --- */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }

        .gallery-card, .folder-wrapper {
            background: var(--card);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--line);
            transition: all 0.3s ease;
        }
        .gallery-card:hover, .folder-wrapper:hover { transform: translateY(-6px); box-shadow: var(--shadow); }

        .card-img-holder { position: relative; height: 210px; background: #f8f9fb; }
        .card-img-holder img { width: 100%; height: 100%; object-fit: cover; }

        .btn-delete-float {
            position: absolute; top: 12px; right: 12px;
            width: 36px; height: 36px; background: white;
            border-radius: 50%; display: grid; place-items: center;
            color: var(--danger); box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            opacity: 0; transition: 0.2s;
        }
        .gallery-card:hover .btn-delete-float { opacity: 1; }

        .card-info { padding: 18px; }
        .card-info .tag { font-size: 0.7rem; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; }
        .card-info h4 { margin: 6px 0 0; color: var(--brand-navy); font-weight: 700; }

        /* --- PAGINATION --- */
        .gallery-pagination {
            display: flex; justify-content: space-between; align-items: center;
            margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--line);
        }

        .pagination-info { font-size: 0.9rem; color: var(--muted); font-weight: 600; }
        .pagination-controls { display: flex; gap: 8px; }

        .page-btn {
            min-width: 40px; height: 40px; padding: 0 8px; border-radius: 8px; border: 1px solid var(--line);
            background: var(--card); color: var(--brand-navy); font-weight: 700; cursor: pointer; display: grid; place-items: center;
        }

        .page-btn.active { background: var(--brand-gold); border-color: var(--brand-gold); }

        /* --- FOOTER --- */
        .site-footer { margin-top: auto; background: var(--brand-gold); border-top: 1px solid #cc9516; color: #172a5d; padding: 40px 0 18px; }
        .footer-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr 1.2fr; gap: 34px; }
        .footer-logo { display: flex; align-items: center; gap: 12px; font-size: 1.3rem; font-weight: 800; margin-bottom: 12px; }
        .site-footer h5 { margin: 0 0 12px; font-size: 1.08rem; }
        .site-footer ul { list-style: none; padding: 0; display: grid; gap: 7px; }
        .footer-bottom { margin-top: 24px; padding-top: 14px; border-top: 1px solid rgba(16, 34, 79, 0.35); text-align: center; font-weight: 600; }

        .sidebar-toggle-btn { cursor: pointer; border: 1px solid #cfd8ea; border-radius: 10px; padding: 9px 12px; background: #f3f6fc; font-weight: 700; color: #1a2b5f; }
        .sidebar-show-btn { position: fixed; left: 14px; top: 92px; z-index: 50; display: none; box-shadow: 0 10px 18px rgba(16,34,79,0.18); }

        @media (max-width: 900px) {
            .dashboard-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; gap: 20px; }
            .topbar-inner { flex-direction: column; padding: 14px; }
            .filter-bar { flex-direction: column; align-items: stretch; }
            .btn-upload-cloudinary { margin-left: 0; justify-content: center; }
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

            <div class="user-menu">
                <button type="button" class="user-chip-btn">
                    <span>◎</span> <span>Sample User</span> <span>▾</span>
                </button>
                <div class="user-dropdown">
                    <button type="button" class="user-role">User</button>
                    <a class="user-role" href="<?= base_url('admin'); ?>">Admin</a>
                    <a class="user-role" href="<?= base_url('employee'); ?>">Employee</a>
                </div>
            </div>
        </div>
    </header>

    <main class="dashboard">
        <div class="container dashboard-grid" id="dashboardGrid">
            
            <aside class="sidebar">
                <div class="profile">
                    <h4>Admin User</h4>
                    <span class="badge">admin</span>
                </div>

                <div style="margin-top: 15px;">
                    <p style="font-size: 0.75rem; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; padding-left: 12px; margin-bottom: 8px;">Management</p>
                    <ul class="menu">
                        <li><a href="#" data-section="dashboard-overview"> Dashboard Overview</a></li>
                        <li><a href="#" data-section="calendar-management">Calendar & Schedule</a></li>
                        <li><a href="#" class="active" data-section="gallery-management">Gallery Management</a></li>
                        <li><a href="<?= base_url('inventory'); ?>">Order Management</a></li>
                        <li><a href="<?= base_url('orders'); ?>">Inventory Management</a></li>
                        <li><a href="<?= base_url('orders'); ?>">Account Management</a></li>
                        <li><a href="#" data-section="task-management">Task Management</a></li>
                        <li><a href="<?= base_url('orders'); ?>">Discount</a></li>
                        <li><a href="<?= base_url('orders'); ?>">Control Management</a></li>
                    </ul>
                </div>

                <div style="margin-top: 25px;">
                    <p style="font-size: 0.75rem; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; padding-left: 12px; margin-bottom: 8px;">System</p>
                    <ul class="menu">
                        <li><a href="<?= base_url('settings'); ?>">⚙️ Settings</a></li>
                        <li><a href="<?= base_url('notifications'); ?>">🔔 Notifications</a></li>
                        <li style="margin-top: 10px; border-top: 1px solid var(--line); padding-top: 10px;">
                            <a href="<?= base_url(); ?>" style="color: var(--accent);">⬅ Back to Website</a>
                        </li>
                    </ul>
                </div>

                <div style="margin-top: 30px; padding: 0 12px;">
                    <button type="button" class="sidebar-toggle-btn" id="sidebar-hide-btn" style="width: 100%;">
                        Hide Sidebar
                    </button>
                </div>
            </aside>

            <button type="button" class="sidebar-toggle-btn sidebar-show-btn" id="sidebar-show-btn">
                Show Menu
            </button>

            <section class="content">
                
                <div style="margin-bottom: 20px;">
                    <?php if ($view_mode == 'cards'): ?>
                        <a href="<?= base_url('gallery'); ?>" style="color: var(--brand-navy); font-weight: 700; font-size: 0.9rem;">
                            <i class="fas fa-arrow-left"></i> Back to All Customers
                        </a>
                        <h2 style="margin: 5px 0 0; color: var(--brand-navy);">Folder: <?= esc($customer_name); ?></h2>
                    <?php else: ?>
                        <h2 style="margin: 0; color: var(--brand-navy);">Gallery Management</h2>
                    <?php endif; ?>
                </div>

                <div class="filter-bar">
                    <div class="filter-group" style="flex: 1; min-width: 200px;">
                        <i class="fas fa-search"></i>
                        <input type="text" id="gallerySearch" placeholder="Search here" onkeyup="filterGallery()" style="width: 100%;">
                    </div>

                    <div class="filter-group">
                        <i class="fas fa-filter"></i>
                        <select id="filterCategory">
                            <option>All Categories</option>
                            <option>Logo</option>
                            <option>Layout</option>
                            <option>3D Model</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <i class="fas fa-file-alt"></i>
                        <select id="filterFileType">
                            <option>All Files</option>
                            <option>.PNG</option>
                            <option>.JPG</option>
                        </select>
                    </div>

                    <div class="view-toggle">
                        <div class="toggle-btn active"><i class="fas fa-th-large"></i></div>
                        <div class="toggle-btn"><i class="fas fa-list"></i></div>
                    </div>

                    <button id="upload_widget" class="btn-upload-cloudinary">
                        <i class="fas fa-upload"></i> Upload Images
                    </button>
                </div>

                <?php if ($view_mode == 'folders' && !empty($recent_uploads)): ?>
                    <div class="recent-uploads-section" style="margin-bottom: 35px;">
                        <h3 style="color: var(--brand-navy); margin-bottom: 15px; font-size: 1.1rem; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-clock" style="color: var(--brand-gold);"></i> Recent Uploads
                        </h3>
                        
                        <div class="recent-scroll-container" style="display: flex; gap: 18px; overflow-x: auto; padding-bottom: 15px; scrollbar-width: thin;">
                            <?php foreach ($recent_uploads as $recent): ?>
                                <div class="recent-card" style="flex: 0 0 240px; background: var(--card); border-radius: 12px; border: 1px solid var(--line); overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                                    <div style="height: 120px; overflow: hidden; background: #f0f3f8;">
                                        <img src="<?= $recent['image_url']; ?>" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="openModal(this.src)">
                                    </div>
                                    <div style="padding: 10px 12px;">
                                        <p style="margin: 0; font-size: 0.8rem; font-weight: 700; color: var(--brand-navy); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <?= esc($recent['filename']); ?>
                                        </p>
                                        <p style="margin: 3px 0 0; font-size: 0.7rem; color: var(--muted); display: flex; justify-content: space-between;">
                                            <span><i class="fas fa-user"></i> <?= esc($recent['customer_name']); ?></span>
                                            <span><?= date('M d, H:i', strtotime($recent['created_at'])); ?></span>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="gallery-grid" id="galleryGrid">
                    <?php if ($view_mode == 'folders'): ?>
                        <?php foreach ($folders as $f): ?>
                            <a href="<?= base_url('gallery/index/'.urlencode($f['customer_name'])); ?>" 
                               class="folder-wrapper" 
                               data-name="<?= strtolower(esc($f['customer_name'])); ?>"
                               style="text-decoration:none; text-align:center; padding: 20px;">
                                <div class="folder-icon-container" style="position:relative; display:inline-block;">
                                    <i class="fas fa-folder" style="font-size: 100px; color: #e7a821;"></i>
                                    <span style="position:absolute; top:40%; left:50%; transform:translate(-50%, -50%); color:white; font-weight:800; font-size:14px;">
                                        <?= $f['total']; ?>
                                    </span>
                                </div>
                                <h4 style="margin-top:5px; color: var(--brand-navy);"><?= esc($f['customer_name']); ?></h4>
                            </a>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <?php foreach ($images as $img): ?>
                            <div class="gallery-card" data-name="<?= strtolower(esc($img['filename'])); ?>">
                                <div class="card-img-holder">
                                    <img src="<?= $img['image_url']; ?>" onclick="openModal(this.src)" style="cursor:pointer;">
                                    <a href="<?= base_url('gallery/delete/'.$img['id']); ?>" class="btn-delete-float" onclick="return confirm('Delete?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                                <div class="card-info">
                                    <span class="tag"><?= strtoupper($img['format']); ?></span>
                                    <h4><?= esc($img['filename']); ?></h4>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="gallery-pagination">
                    <div class="pagination-info">Showing 0 to 0 of 0 entries</div>
                    <div class="pagination-controls"></div>
                </div>
            </section>
        </div>
    </main>

    <div id="imageModal" 
        style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.85); place-items:center; cursor:zoom-out; backdrop-filter: blur(5px);"
        onclick="closeModal()">
        <span style="position:absolute; top:20px; right:30px; color:#fff; font-size:40px; font-weight:bold; cursor:pointer; z-index:10000;"
            onclick="closeModal()">&times;</span>
        <img id="modalImg" alt="Design View"
            style="margin:auto; display:block; max-width: 90vw; max-height: 85vh; width: auto; height: auto; object-fit: contain; border-radius:12px; box-shadow:0 8px 30px rgba(0,0,0,0.6); border: 4px solid #fff; transition: transform 0.3s ease;"
            onclick="event.stopPropagation();">
    </div>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <section>
                    <div class="footer-logo">
                        <span class="logo-mark">P</span> <span>Printopia</span>
                    </div>
                    <p>Your partner for custom printing solutions.</p>
                </section>
                <section>
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="<?= base_url(); ?>">Home</a></li>
                        <li><a href="<?= base_url('products'); ?>">Products</a></li>
                        <li><a href="<?= base_url('contact'); ?>">Contact Us</a></li>
                    </ul>
                </section>
                <section>
                    <h5>Services</h5>
                    <ul>
                        <li>Custom 3D Printing</li>
                        <li>Design Consultation</li>
                    </ul>
                </section>
                <section>
                    <h5>Contact</h5>
                    <ul>
                        <li>0922-4756841</li>
                        <li>esensoweta61@gmail.com</li>
                    </ul>
                </section>
            </div>
            <div class="footer-bottom">&copy; 2026 Printopia. All rights reserved.</div>
        </div>
    </footer>

    <script src="https://upload-widget.cloudinary.com/global/all.js" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dashboardGrid = document.getElementById('dashboardGrid');
            const sidebarHideBtn = document.getElementById('sidebar-hide-btn');
            const sidebarShowBtn = document.getElementById('sidebar-show-btn');

            // --- Sidebar Toggle ---
            sidebarHideBtn.addEventListener('click', () => {
                dashboardGrid.classList.add('sidebar-hidden');
                sidebarShowBtn.style.display = 'inline-flex';
            });

            sidebarShowBtn.addEventListener('click', () => {
                dashboardGrid.classList.remove('sidebar-hidden');
                sidebarShowBtn.style.display = 'none';
            });

            // --- Pagination & Filter Logic ---
            const viewMode = "<?= $view_mode; ?>";
            const cardsPerPage = 12;
            let currentPage = 1;
            const grid = document.getElementById('galleryGrid');
            const paginationControls = document.querySelector('.pagination-controls');
            const paginationInfo = document.querySelector('.pagination-info');

            window.updateGalleryDisplay = function() {
                const selector = (viewMode === 'folders') ? '.folder-wrapper' : '.gallery-card';
                const allItems = Array.from(grid.querySelectorAll(selector));
                const visibleItems = allItems.filter(item => item.dataset.filtered !== "true");
                
                const totalItems = visibleItems.length;
                const totalPages = Math.ceil(totalItems / cardsPerPage) || 1;

                if (currentPage > totalPages) currentPage = totalPages;
                const start = (currentPage - 1) * cardsPerPage;
                const end = start + cardsPerPage;

                allItems.forEach(item => item.style.display = "none");
                visibleItems.slice(start, end).forEach(item => item.style.display = "block");

                const showingTo = Math.min(end, totalItems);
                paginationInfo.innerText = `Showing ${totalItems === 0 ? 0 : start + 1} to ${showingTo} of ${totalItems} entries`;

                renderControls(totalPages);
            }

            function renderControls(totalPages) {
                paginationControls.innerHTML = '';
                if(totalPages <= 1) return;
                for (let i = 1; i <= totalPages; i++) {
                    const btn = document.createElement('button');
                    btn.className = `page-btn ${i === currentPage ? 'active' : ''}`;
                    btn.innerText = i;
                    btn.onclick = () => { currentPage = i; updateGalleryDisplay(); };
                    paginationControls.appendChild(btn);
                }
            }

            window.filterGallery = function() {
                const filter = document.getElementById('gallerySearch').value.toLowerCase();
                const selector = (viewMode === 'folders') ? '.folder-wrapper' : '.gallery-card';
                grid.querySelectorAll(selector).forEach(item => {
                    const name = item.getAttribute('data-name');
                    item.dataset.filtered = (name && name.includes(filter)) ? "false" : "true";
                });
                currentPage = 1; 
                updateGalleryDisplay();
            };

            updateGalleryDisplay();
        });

        // Cloudinary Widget Initialization
        // 1. Create an array to hold all successful uploads in one session
        let uploadQueue = []; 

        var myWidget = cloudinary.createUploadWidget({
            cloudName: 'dik33xzef', 
            apiKey: '561229386672246',
            uploadPreset: 'ml_default',
            folder: '<?= isset($customer_name) ? esc($customer_name) : "general" ?>'
        }, (error, result) => { 
            // IF A FILE FINISHES: Just push the data to our temporary queue
            if (!error && result && result.event === "success") { 
                uploadQueue.push({
                    image_url: result.info.secure_url,
                    public_id: result.info.public_id,
                    filename: result.info.original_filename,
                    format: result.info.format,
                    customer_name: '<?= isset($customer_name) ? esc($customer_name) : "General" ?>'
                });
                console.log("Added to queue:", result.info.original_filename);
            }

            // IF THE WIDGET CLOSES: Now send everything in the queue to the DB
            if (!error && result && result.event === "close") {
                if (uploadQueue.length > 0) {
                    saveQueueToDatabase(uploadQueue);
                }
            }
        });

        // Helper function to handle the fetch
        function saveQueueToDatabase(dataArray) {
            fetch('<?= base_url("gallery/save_to_db"); ?>', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>' 
                },
                body: JSON.stringify(dataArray) // Sending the whole array now
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    location.reload(); 
                } else {
                    alert("Database save failed: " + data.message);
                }
            })
            .catch(err => console.error("Error saving queue:", err));
        }

        document.getElementById("upload_widget").addEventListener("click", function(){
            myWidget.open();
        }, false);

        function openModal(src) {
            const modal = document.getElementById("imageModal");
            document.getElementById("modalImg").src = src;
            modal.style.display = "grid";
        }

        function closeModal() {
            document.getElementById("imageModal").style.display = "none";
        }
    </script>
</body>
</html>