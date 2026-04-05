<style>
    .topbar {
        position: sticky;
        top: 0;
        z-index: 50;
        background: var(--brand-gold);
        border-bottom: 1px solid rgba(16, 34, 79, 0.15);
        box-shadow: 0 6px 18px rgba(16, 34, 79, 0.15);
    }

    .topbar-inner {
        min-height: 78px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 800;
        font-size: 1.25rem;
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
        font-weight: 800;
        font-size: 1.05rem;
    }

    .main-nav {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: clamp(16px, 1.9vw, 30px);
        font-size: 0.88rem;
        color: #162757;
        flex: 1.15;
    }

    .main-nav a {
        position: relative;
        font-weight: 600;
        opacity: 0.9;
        padding: 8px 0;
    }

    .main-nav a.active::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 3px;
        background: #112255;
        border-radius: 99px;
    }

    .user-menu {
        position: relative;
        margin-left: auto;
    }

    .user-chip-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 13px;
        border-radius: 12px;
        border: 2px solid #122459;
        background: rgba(255, 255, 255, 0.3);
        font-size: 0.78rem;
        font-weight: 600;
        color: #132559;
        cursor: pointer;
        font-family: inherit;
        line-height: 1;
    }

    .user-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        min-width: 180px;
        border: 1px solid #d7deee;
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 12px 22px rgba(16, 34, 79, 0.16);
        overflow: hidden;
        display: none;
        z-index: 80;
    }

    .user-menu:hover .user-dropdown,
    .user-menu:focus-within .user-dropdown {
        display: block;
    }

    .user-dropdown a {
        display: block;
        width: 100%;
        text-align: left;
        padding: 10px 12px;
        border: 0;
        background: #ffffff;
        color: #273257;
        font: inherit;
        font-size: 0.86rem;
        font-weight: 600;
        cursor: pointer;
    }

    .user-dropdown a + a {
        border-top: 1px solid #edf1f8;
    }

    .user-dropdown a:hover {
        background: #f5f7fb;
    }

    @media (max-width: 780px) {
        .topbar-inner {
            flex-wrap: wrap;
            justify-content: center;
            min-height: auto;
            padding: 14px 0;
        }

        .logo,
        .user-menu {
            flex: 0 0 auto;
            margin-left: 0;
        }

        .main-nav {
            width: 100%;
            justify-content: center;
            flex-wrap: wrap;
            gap: 18px;
            font-size: 0.92rem;
        }
    }
</style>

<header class="topbar">
    <div class="container topbar-inner">
        <a class="logo" href="<?= base_url(); ?>">
            <span class="logo-mark">P</span>
            <span>Printopia</span>
        </a>

        <nav class="main-nav" aria-label="Main navigation">
            <a href="<?= base_url(); ?>" class="<?= (($activePage ?? '') === 'home') ? 'active' : ''; ?>">Home</a>
            <a href="<?= base_url('products'); ?>" class="<?= (($activePage ?? '') === 'products') ? 'active' : ''; ?>">Products</a>
            <a href="<?= base_url('how-it-works'); ?>" class="<?= (($activePage ?? '') === 'how-it-works') ? 'active' : ''; ?>">How it works</a>
            <a href="<?= base_url('contact'); ?>" class="<?= (($activePage ?? '') === 'contact') ? 'active' : ''; ?>">Contact Us</a>
        </nav>

        <div class="user-menu" aria-label="Role menu">
            <button type="button" class="user-chip-btn" aria-haspopup="true" aria-expanded="false">
                <span>◎</span>
                <span><?= esc($userLabel ?? 'Sample User'); ?></span>
                <span>▾</span>
            </button>
            <div class="user-dropdown" role="menu" aria-label="Select role">
                <a class="user-role" role="menuitem" href="<?= base_url(); ?>">User</a>
                <a class="user-role" role="menuitem" href="<?= base_url('admin'); ?>">Admin</a>
                <a class="user-role" role="menuitem" href="<?= base_url('employee'); ?>">Employee</a>
            </div>
        </div>
    </div>
</header>