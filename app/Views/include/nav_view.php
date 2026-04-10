<style>
    :root {
        --brand-bg: #ECA72C;
        --brand-red: #92140C;
        --brand-dark: #0d1f49;
    }

    .topbar {
        position: sticky;
        top: 0;
        z-index: 1000;
        background: var(--brand-bg);
        border-bottom: 2px solid rgba(146, 20, 12, 0.1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .topbar-inner {
        min-height: 70px;
        display: grid;
        grid-template-columns: 1fr auto 1fr; 
        align-items: center;
        padding: 0 24px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .logo {
        display: flex;
        align-items: center;
        text-decoration: none;
        justify-self: start;
    }
    .logo-img { height: 40px; width: auto; }

    /* Desktop Navigation */
    .main-nav {
        display: flex;
        gap: clamp(15px, 2.5vw, 35px);
        justify-self: center;
    }
    .main-nav a {
        text-decoration: none;
        color: var(--brand-dark);
        font-weight: 700;
        font-size: 0.95rem;
        padding: 8px 0;
        position: relative;
    }
    .main-nav a.active::after {
        content: "";
        position: absolute;
        bottom: 0; left: 0; width: 100%; height: 3px;
        background: var(--brand-red);
        border-radius: 2px;
    }

    .actions {
        justify-self: end;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    /* Mobile Toggle Button */
    .menu-toggle {
        display: none;
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--brand-dark);
        cursor: pointer;
        padding: 5px;
    }

    .btn-login {
        text-decoration: none;
        background: var(--brand-red);
        color: white;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 800;
        font-size: 0.85rem;
        box-shadow: 0 4px 0px #6d0e09;
        transition: all 0.1s;
    }

    /* Responsive Logic */
    @media (max-width: 992px) {
        .topbar-inner {
            grid-template-columns: 1fr auto; /* Hide middle column space */
        }

        .menu-toggle {
            display: block;
            order: -1; /* Puts hamburger on far left */
            margin-right: 15px;
        }

        .logo {
            justify-self: start;
        }

        /* Mobile Slide-down Menu */
        .main-nav {
            display: none; /* Hidden by default */
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: var(--brand-bg);
            flex-direction: column;
            gap: 0;
            padding: 10px 0;
            border-top: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 10px 15px rgba(0,0,0,0.1);
        }

        .main-nav.is-active {
            display: flex;
        }

        .main-nav a {
            padding: 15px 24px;
            width: 100%;
            border-bottom: 1px solid rgba(0,0,0,0.03);
        }
        
        .main-nav a.active::after {
            left: 0; width: 4px; height: 100%; top: 0; /* Side indicator on mobile */
        }
    }

    /* Login/User chip styles remain the same as your previous code */
    .user-chip { display: flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.25); border: 2px solid var(--brand-red); padding: 5px 12px; border-radius: 10px; cursor: pointer; }
    .user-icon { background: var(--brand-red); color: white; width: 24px; height: 24px; border-radius: 5px; display: grid; place-items: center; font-weight: 800; font-size: 0.8rem; }
</style>

<header class="topbar">
    <div class="topbar-inner">
        
        <div style="display: flex; align-items: center;">
            <button class="menu-toggle" id="mobileMenuBtn" aria-label="Toggle Menu">☰</button>
            <a class="logo" href="<?= base_url(); ?>">
                <img src="<?= base_url('/assets/Logo.png'); ?>" alt="Printopia Logo" class="logo-img">
            </a>
        </div>

        <nav class="main-nav" id="navLinks">
            <a href="<?= base_url(); ?>" class="<?= (($activePage ?? '') === 'home') ? 'active' : ''; ?>">Home</a>
            <a href="<?= base_url('products'); ?>" class="<?= (($activePage ?? '') === 'products') ? 'active' : ''; ?>">Products</a>
            <a href="<?= base_url('how-it-works'); ?>" class="<?= (($activePage ?? '') === 'how-it-works') ? 'active' : ''; ?>">How it works</a>
            <a href="<?= base_url('contact'); ?>" class="<?= (($activePage ?? '') === 'contact') ? 'active' : ''; ?>">Contact Us</a>
        </nav>

        <div class="actions">
            <?php if (session()->get('user_name')): ?>
                <div class="user-menu-container" style="position: relative;" id="userMenu">
                    <div class="user-chip">
                        <div class="user-icon"><?= strtoupper(substr(session()->get('user_name'), 0, 1)) ?></div>
                        <span style="font-weight: 800; color: var(--brand-dark); font-size: 0.9rem;"><?= esc(session()->get('user_name')) ?></span>
                    </div>
                    <div class="dropdown-menu-custom" style="position: absolute; right: 0; background: white; min-width: 160px; box-shadow: 0 10px 20px rgba(0,0,0,0.1); border-radius: 8px; display: none; overflow: hidden; z-index: 100;">
                        <a href="<?= base_url('customer') ?>" style="display: block; padding: 12px; color: var(--brand-dark); text-decoration: none; font-weight: 700;">Dashboard</a>
                        <a href="<?= base_url('logout') ?>" style="display: block; padding: 12px; color: var(--brand-red); text-decoration: none; font-weight: 700; border-top: 1px solid #eee;">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= base_url('login'); ?>" class="btn-login">LOGIN</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<script>
    // Mobile Menu Toggle
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const navLinks = document.getElementById('navLinks');

    mobileBtn.addEventListener('click', () => {
        navLinks.classList.toggle('is-active');
        mobileBtn.textContent = navLinks.classList.contains('is-active') ? '✕' : '☰';
    });

    // Desktop Dropdown
    const userMenu = document.getElementById('userMenu');
    if(userMenu) {
        userMenu.onmouseover = () => userMenu.querySelector('.dropdown-menu-custom').style.display = 'block';
        userMenu.onmouseout = () => userMenu.querySelector('.dropdown-menu-custom').style.display = 'none';
    }
</script>