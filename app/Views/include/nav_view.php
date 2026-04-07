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
    /* logo image sizing */
    .logo-img {
        height: 30px;       /* smaller height */
        width: auto;        /* maintain aspect ratio */
    }

    /* optional: adjust text size next to logo */
    .logo-text {
        font-size: 1.1rem;  /* adjust as needed */
    }`

</style>

<header class="topbar">
    <div class="container topbar-inner d-flex align-items-center justify-content-between">
        
        <a class="logo" href="<?= base_url(); ?>">
            <img src="<?= base_url('/assets/Logo.png'); ?>" alt="Printopia Logo" class="logo-img me-2">
            <span>Printopia</span>
        </a>

        <nav class="main-nav" aria-label="Main navigation">
            <a href="<?= base_url(); ?>" class="<?= (($activePage ?? '') === 'home') ? 'active' : ''; ?>">Home</a>
            <a href="<?= base_url('products'); ?>" class="<?= (($activePage ?? '') === 'products') ? 'active' : ''; ?>">Products</a>
            <a href="<?= base_url('how-it-works'); ?>" class="<?= (($activePage ?? '') === 'how-it-works') ? 'active' : ''; ?>">How it works</a>
            <a href="<?= base_url('contact'); ?>" class="<?= (($activePage ?? '') === 'contact') ? 'active' : ''; ?>">Contact Us</a>
        </nav>

        <!-- NEW AUTH BUTTONS -->
        <div class="auth-actions d-flex align-items-center gap-2">
            <a href="<?= base_url('login'); ?>" class="btn btn-outline-primary btn-sm">
                Login
            </a>
            <a href="<?= base_url('signup'); ?>" class="btn btn-primary btn-sm fw-bold">
                Sign Up
            </a>
        </div>

    </div>
</header>