<style>
    .topbar .user-dropdown {
        display: none;
        flex-direction: column;
    }

    .topbar .user-menu:hover .user-dropdown,
    .topbar .user-menu:focus-within .user-dropdown {
        display: flex;
    }

    .topbar .user-dropdown .user-role {
        display: block !important;
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

    .topbar .user-dropdown .user-role + .user-role {
        border-top: 1px solid #edf1f8;
    }

    .topbar .user-dropdown .user-role:hover {
        background: #f5f7fb;
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