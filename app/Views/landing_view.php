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
            --brand-gold-deep: #d79511;
            --brand-navy: #10224f;
            --text-dark: #202327;
            --text-muted: #5a6072;
            --surface: #f5f6f8;
            --white: #ffffff;
            --danger: #b22619;
            --shadow-card: 0 12px 24px rgba(16, 34, 79, 0.14);
            --shadow-soft: 0 8px 18px rgba(16, 34, 79, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        html {
            font-size: 16px;
        }

        body {
            margin: 0;
            font-family: "Sora", sans-serif;
            color: var(--text-dark);
            background: linear-gradient(180deg, #f7f8fb 0%, #eef1f8 100%);
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1 0 auto;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .container {
            width: min(1680px, 97vw);
            margin: 0 auto;
        }

        .topbar .container,
        .site-footer .container {
            width: 100%;
            max-width: none;
            padding-left: clamp(20px, 4vw, 84px);
            padding-right: clamp(20px, 4vw, 84px);
        }

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

        .user-role {
            width: 100%;
            border: 0;
            border-bottom: 1px solid #eef2f8;
            background: #ffffff;
            color: #192f68;
            text-align: left;
            padding: 10px 12px;
            font: inherit;
            font-size: 0.84rem;
            font-weight: 600;
            cursor: pointer;
        }

        .user-role:last-child {
            border-bottom: 0;
        }

        .user-role:hover {
            background: #f5f8ff;
        }

        .hero {
            position: relative;
            min-height: clamp(440px, calc(100vh - 78px), 760px);
            display: grid;
            place-items: center;
            overflow: hidden;
            background:
                linear-gradient(140deg, rgba(16, 34, 79, 0.7), rgba(16, 34, 79, 0.35)),
                url("https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=1600&q=80") center/cover no-repeat;
        }

        .hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.18), transparent 48%);
        }

        .hero-card {
            position: relative;
            width: min(980px, 92vw);
            background: rgba(20, 36, 72, 0.55);
            backdrop-filter: blur(3px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 14px;
            color: var(--white);
            text-align: center;
            padding: 52px 40px;
            animation: rise-in 0.8s ease-out both;
        }

        .hero h1 {
            margin: 0;
            font-size: clamp(2.4rem, 5vw, 3.8rem);
            line-height: 1.2;
            letter-spacing: 0.2px;
        }

        .hero p {
            margin: 16px auto 28px;
            max-width: 700px;
            color: rgba(255, 255, 255, 0.92);
            font-size: 1.06rem;
            line-height: 1.55;
        }

        .hero-cta {
            display: inline-block;
            padding: 16px 34px;
            border-radius: 9px;
            font-size: 0.97rem;
            font-weight: 700;
            letter-spacing: 0.2px;
            background: linear-gradient(180deg, #bf2e1e 0%, #9c1e13 100%);
            color: var(--white);
            box-shadow: 0 10px 18px rgba(140, 23, 13, 0.4);
            transition: transform 0.22s ease, box-shadow 0.22s ease;
        }

        .hero-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 24px rgba(140, 23, 13, 0.5);
        }

        .section {
            padding: clamp(56px, 7vw, 92px) 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 16px;
            color: var(--brand-navy);
            font-size: clamp(2.1rem, 4vw, 3.2rem);
            line-height: 1.12;
        }

        .section-subtitle {
            margin: 0 auto;
            max-width: 920px;
            text-align: center;
            color: var(--danger);
            font-size: 1.04rem;
            font-weight: 600;
        }

        .features-grid {
            margin-top: 36px;
            display: grid;
            grid-template-columns: repeat(3, minmax(320px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: var(--shadow-card);
            border: 1px solid #e9edf7;
            min-height: 280px;
            padding: 36px 30px;
            text-align: center;
            animation: rise-in 0.85s ease both;
        }

        .feature-icon {
            font-size: 2.35rem;
            line-height: 1;
            margin-bottom: 16px;
        }

        .feature-card h3 {
            margin: 0 0 12px;
            color: #b32319;
            font-size: 1.34rem;
        }

        .feature-card p {
            margin: 0;
            color: #3f4454;
            font-size: 0.98rem;
            line-height: 1.65;
        }

        .steps-grid {
            margin-top: 34px;
            display: grid;
            grid-template-columns: repeat(5, minmax(220px, 1fr));
            gap: 18px;
        }

        .step-card {
            background: var(--white);
            border-radius: 14px;
            box-shadow: var(--shadow-soft);
            border: 1px solid #e6ebf6;
            min-height: 230px;
            padding: 26px 18px;
            text-align: center;
            animation: rise-in 0.95s ease both;
        }

        .step-icon {
            display: inline-grid;
            place-items: center;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1px solid #d5dbeb;
            margin-bottom: 14px;
            color: #1f346e;
            font-size: 1.1rem;
        }

        .step-card h4 {
            margin: 0 0 10px;
            font-size: 1.06rem;
            color: #b32319;
        }

        .step-card p {
            margin: 0;
            font-size: 0.91rem;
            line-height: 1.55;
            color: #4d5366;
        }

        .cta-wrap {
            margin-top: 42px;
            display: grid;
            place-items: center;
        }

        .cta-panel {
            width: min(920px, 96vw);
            border-radius: 16px;
            background: var(--white);
            border: 1px solid #e5eaf5;
            box-shadow: var(--shadow-card);
            padding: 48px 36px;
            text-align: center;
        }

        .cta-panel h3 {
            margin: 0;
            font-size: clamp(1.6rem, 4vw, 2.4rem);
            color: #b32319;
        }

        .cta-panel p {
            margin: 12px auto 24px;
            max-width: 760px;
            font-size: 1rem;
            color: #4c5162;
        }

        .cta-button {
            display: inline-block;
            min-width: 300px;
            padding: 16px 30px;
            border-radius: 9px;
            background: linear-gradient(180deg, #bf2e1e 0%, #9c1e13 100%);
            color: var(--white);
            font-weight: 700;
            font-size: 0.98rem;
            box-shadow: 0 10px 16px rgba(146, 23, 12, 0.36);
            transition: transform 0.2s ease;
        }

        .cta-button:hover {
            transform: translateY(-2px);
        }

        .floating-actions {
            position: fixed;
            right: 18px;
            bottom: 120px;
            display: grid;
            gap: 11px;
            z-index: 60;
        }

        .floating-actions a {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: var(--brand-gold);
            border: 2px solid #1b2e63;
            display: grid;
            place-items: center;
            color: #14285c;
            font-size: 1.35rem;
            box-shadow: 0 10px 16px rgba(16, 34, 79, 0.2);
        }

        .site-footer {
            margin-top: 48px;
            background: var(--brand-gold);
            border-top: 1px solid #cc9516;
            color: #172a5d;
            padding: 44px 0 20px;
            margin-top: auto;
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
            font-weight: 800;
            margin-bottom: 14px;
            font-size: 1.3rem;
        }

        .site-footer h5 {
            margin: 0 0 14px;
            font-size: 1.12rem;
        }

        .site-footer p,
        .site-footer li {
            margin: 0;
            font-size: 0.95rem;
            line-height: 1.7;
            color: rgba(15, 33, 78, 0.95);
        }

        .site-footer ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 8px;
        }

        .footer-bottom {
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid rgba(16, 34, 79, 0.35);
            font-size: 0.9rem;
            font-weight: 600;
            color: #0f2358;
            text-align: center;
        }

        @keyframes rise-in {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 1280px) {
            html {
                font-size: 16px;
            }

            .topbar-inner {
                min-height: 80px;
            }

            .steps-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 1080px) {
            .main-nav {
                font-size: 0.87rem;
                gap: 16px;
            }

            .features-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .steps-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .footer-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 780px) {
            .container {
                width: min(1400px, 94vw);
            }

            .topbar .container,
            .site-footer .container {
                padding-left: 14px;
                padding-right: 14px;
            }

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

            .hero {
                min-height: 420px;
            }

            .hero-card {
                width: 94vw;
                padding: 34px 22px;
            }

            .hero p {
                font-size: 1rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .steps-grid {
                grid-template-columns: 1fr;
            }

            .feature-card,
            .step-card {
                min-height: auto;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }

            .floating-actions {
                right: 10px;
                bottom: 84px;
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
                <a href="<?= base_url(); ?>" class="active">Home</a>
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

    <section class="hero" id="main-content">
        <div class="hero-card">
            <h1>Custom Design Prints</h1>
            <p>Design your custom print products and place orders in minutes with a production-ready workflow.</p>
            <a href="<?= base_url('custom-order'); ?>" class="hero-cta">Start your order now</a>
        </div>
    </section>

    <main>
        <section class="section">
            <div class="container">
                <h2 class="section-title">Why Choose Us</h2>
                <p class="section-subtitle">Complete custom design prints from orders to 3D visuals with professional quality guaranteed.</p>

                <div class="features-grid">
                    <article class="feature-card">
                        <div class="feature-icon">🎨</div>
                        <h3>Easy Design Tools</h3>
                        <p>Create beautiful layouts in minutes using our intuitive, beginner-friendly interface and drag-and-drop features.</p>
                    </article>
                    <article class="feature-card">
                        <div class="feature-icon">✓</div>
                        <h3>Premium Quality Printing</h3>
                        <p>Experience vibrant colors and sharp details with our high-definition, professional-grade production process.</p>
                    </article>
                    <article class="feature-card">
                        <div class="feature-icon">3D</div>
                        <h3>Real Time 3D Preview</h3>
                        <p>Visualize your creation from every angle with an interactive, instant digital render before you commit to final print.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="section" id="workflow">
            <div class="container">
                <h2 class="section-title">How it works</h2>
                <p class="section-subtitle">From design to orders, experience our simple steps in custom print process.</p>

                <div class="steps-grid">
                    <article class="step-card">
                        <div class="step-icon">↖</div>
                        <h4>Select</h4>
                        <p>Browse our extensive catalog to find the perfect product to start your custom canvas project.</p>
                    </article>
                    <article class="step-card">
                        <div class="step-icon">◉</div>
                        <h4>Design</h4>
                        <p>Bring your vision to life using our versatile suite of creative tools and customizable templates.</p>
                    </article>
                    <article class="step-card">
                        <div class="step-icon">◻</div>
                        <h4>Order</h4>
                        <p>Complete your purchase with a streamlined checkout process designed for speed and security.</p>
                    </article>
                    <article class="step-card">
                        <div class="step-icon">⌁</div>
                        <h4>Negotiate price</h4>
                        <p>Request a custom quote for bulk orders to ensure you get the best possible value for your budget.</p>
                    </article>
                    <article class="step-card">
                        <div class="step-icon">☰</div>
                        <h4>Save Designs</h4>
                        <p>Securely store your work-in-progress or finished masterpieces to your account for easy editing and reordering.</p>
                    </article>
                </div>

                <div class="cta-wrap">
                    <div class="cta-panel">
                        <h3>Ready to Order?</h3>
                        <p>Create stunning layouts in minutes using our intuitive, beginner-friendly interface and drag-and-drop features.</p>
                        <a href="<?= base_url('custom-order'); ?>" class="cta-button">Place your order now →</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <aside class="floating-actions" aria-label="Quick actions">
        <a href="tel:+639224756841" aria-label="Call">☎</a>
        <a href="<?= base_url('contact'); ?>" aria-label="Support">◔</a>
        <a href="<?= base_url('contact'); ?>" aria-label="Chat">💬</a>
    </aside>

    <footer class="site-footer" id="contact">
        <div class="container">
            <div class="footer-grid">
                <section>
                    <div class="footer-logo">
                        <span class="logo-mark">P</span>
                        <span>Printopia</span>
                    </div>
                    <p>Your premier partner for custom print solutions. Our customer portal gives real-time visibility to production workflow and seamless order management.</p>
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
                        <li>Timely delivery</li>
                    </ul>
                </section>

                <section>
                    <h5>Contact Information</h5>
                    <ul>
                        <li>0623-7676881</li>
                        <li>Executive35@printcl.com</li>
                        <li>Tanauan, Batangas</li>
                    </ul>
                </section>
            </div>

            <div class="footer-bottom">© 2026 Printopia. All rights reserved.</div>
        </div>
    </footer>
    <script src="<?= base_url('hci-assist.js'); ?>"></script>
</body>
</html>
