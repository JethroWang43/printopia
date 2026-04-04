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
            --white: #ffffff;
            --text: #1f2430;
            --muted: #596074;
            --line: #d6deef;
            --surface: #f5f8ff;
            --section-screen-height: min(calc(100svh - 78px), 820px);
            --shadow-soft: 0 12px 28px rgba(16, 34, 79, 0.12);
            --shadow-strong: 0 18px 34px rgba(16, 34, 79, 0.18);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Sora", sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at 18% -10%, rgba(255, 255, 255, 0.82), transparent 42%),
                linear-gradient(180deg, #f7f9ff 0%, #ebf0f9 100%);
            display: flex;
            flex-direction: column;
            line-height: 1.45;
        }

        main {
            flex: 1 0 auto;
            scroll-snap-type: y mandatory;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .container {
            width: min(1320px, 94vw);
            margin: 0 auto;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 30;
            background: var(--brand-gold);
            border-bottom: 1px solid rgba(16, 34, 79, 0.16);
            box-shadow: 0 6px 16px rgba(16, 34, 79, 0.16);
        }

        .topbar .container,
        .site-footer .container {
            width: 100%;
            max-width: none;
            padding-left: clamp(20px, 4vw, 84px);
            padding-right: clamp(20px, 4vw, 84px);
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
            font-size: 1.25rem;
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
            gap: clamp(16px, 1.9vw, 30px);
            flex: 1.15;
            color: #162757;
            font-size: 0.88rem;
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
            gap: 8px;
            margin-left: auto;
            border: 2px solid #122459;
            border-radius: 12px;
            padding: 8px 13px;
            color: #132559;
            font-size: 0.78rem;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.28);
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

        .hero-title {
            text-align: center;
            color: var(--brand-navy);
            margin: 8px 0 26px;
            font-size: clamp(2.1rem, 4vw, 3rem);
            letter-spacing: -0.02em;
        }

        .steps-intro {
            padding: 34px 0 28px;
            background: #ffffff;
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
            min-height: var(--section-screen-height);
            scroll-snap-align: start;
            scroll-snap-stop: always;
            display: flex;
            align-items: center;
        }

        .steps-grid {
            padding: 8px 0 12px;
            display: grid;
            grid-template-columns: repeat(3, minmax(260px, 1fr));
            gap: 20px;
        }

        .step-item {
            text-align: center;
        }

        .step-frame {
            background: var(--white);
            border-radius: 16px;
            border: 1px solid #dce5f2;
            box-shadow: var(--shadow-soft);
            overflow: hidden;
            margin-bottom: 14px;
            transition: transform 0.24s ease, box-shadow 0.24s ease;
        }

        .step-item:hover .step-frame {
            transform: translateY(-4px);
            box-shadow: var(--shadow-strong);
        }

        .step-item:nth-child(2) .step-frame {
            border-color: #d3dcf1;
            background: linear-gradient(180deg, #f6f8fe 0%, #ffffff 100%);
        }

        .step-item:nth-child(3) .step-frame {
            border-color: #d7e3f3;
            background: linear-gradient(180deg, #f6fbff 0%, #ffffff 100%);
        }

        .step-frame img {
            width: 100%;
            height: 198px;
            object-fit: cover;
            display: block;
            background: #d8deec;
        }

        .step-caption {
            padding: 12px 14px;
            text-align: left;
        }

        .step-caption h4 {
            margin: 0 0 6px;
            color: #17295d;
            font-size: 1.02rem;
        }

        .step-caption p {
            margin: 0;
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .step-item h3 {
            margin: 0;
            color: var(--brand-navy);
            font-size: clamp(1.9rem, 3vw, 3rem);
            letter-spacing: -0.02em;
        }

        .design-cta-wrap {
            display: grid;
            place-items: center;
            margin: 14px 0 10px;
        }

        .design-cta {
            display: inline-block;
            min-width: 190px;
            text-align: center;
            padding: 11px 20px;
            background: linear-gradient(180deg, #ca3a2a 0%, #9f2117 100%);
            color: var(--white);
            border-radius: 10px;
            font-size: 1.08rem;
            font-weight: 700;
            box-shadow: 0 12px 22px rgba(146, 23, 12, 0.34);
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .design-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 26px rgba(146, 23, 12, 0.4);
        }

        .detail-row {
            background: var(--surface);
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
            padding: 40px 0;
            min-height: var(--section-screen-height);
            scroll-snap-align: start;
            scroll-snap-stop: always;
            display: flex;
            align-items: center;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 220px 1fr;
            gap: 28px;
            align-items: center;
        }

        .detail-icon {
            width: 220px;
            height: 220px;
            border-radius: 20px;
            background:
                radial-gradient(circle at 25% 20%, rgba(255, 255, 255, 0.2), transparent 52%),
                linear-gradient(160deg, #0f1a38 0%, #1a2d5f 100%);
            color: #f2f5ff;
            display: grid;
            place-items: center;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: 1px;
            box-shadow: 0 16px 28px rgba(16, 34, 79, 0.22);
        }

        .detail-content h3 {
            margin: 0 0 10px;
            color: var(--brand-navy);
            font-size: clamp(2rem, 3.8vw, 3rem);
        }

        .detail-content p {
            margin: 0;
            max-width: 900px;
            color: #3f4862;
            font-size: 1.08rem;
            line-height: 1.6;
        }

        .save-row {
            background: #ffffff;
            padding: 38px 0 50px;
            min-height: var(--section-screen-height);
            scroll-snap-align: start;
            scroll-snap-stop: always;
            display: flex;
            align-items: center;
        }

        .save-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 26px;
            align-items: start;
        }

        .save-copy h3,
        .save-gallery h3 {
            margin: 0 0 12px;
            color: var(--brand-navy);
            font-size: clamp(2rem, 4vw, 2.9rem);
        }

        .save-copy p {
            margin: 0;
            color: #3f4862;
            font-size: 1.08rem;
            line-height: 1.58;
            max-width: 95%;
        }

        .gallery-label {
            color: #4f5871;
            font-size: 0.95rem;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .gallery-cards {
            display: grid;
            grid-template-columns: repeat(2, minmax(160px, 1fr));
            gap: 14px;
        }

        .gallery-card {
            background: var(--white);
            border-radius: 16px;
            border: 1px solid #dfe6f3;
            box-shadow: var(--shadow-soft);
            overflow: hidden;
            transition: transform 0.22s ease, box-shadow 0.22s ease;
        }

        .gallery-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-strong);
        }

        .gallery-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
            background: #d8deec;
        }

        .gallery-card .meta {
            padding: 10px 12px 12px;
        }

        .gallery-card h4 {
            margin: 0 0 4px;
            font-size: 0.96rem;
            color: #1f2640;
        }

        .gallery-card p {
            margin: 0;
            color: #6a7184;
            font-size: 0.82rem;
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

        @media (max-width: 1280px) {
            .container {
                width: min(1320px, 95vw);
            }

            .steps-grid {
                grid-template-columns: 1fr;
            }

            .save-grid {
                grid-template-columns: 1fr;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .detail-icon {
                width: 190px;
                height: 190px;
                font-size: 1.8rem;
            }
        }

        @media (max-width: 900px) {
            main {
                scroll-snap-type: none;
            }

            .steps-intro,
            .detail-row,
            .save-row {
                min-height: auto;
                scroll-snap-align: none;
            }

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

            .design-cta {
                min-width: 170px;
                font-size: 1rem;
                padding: 10px 16px;
            }

            .detail-content p,
            .save-copy p {
                font-size: 1rem;
            }

            .detail-content h3,
            .save-copy h3,
            .save-gallery h3,
            .step-item h3 {
                font-size: 2.1rem;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <?= view('include/nav_view', ['activePage' => 'how-it-works']); ?>

    <main id="main-content">
        <section class="steps-intro">
            <div class="container">
                <h1 class="hero-title">How it Works?</h1>

                <div class="steps-grid">
                    <article class="step-item">
                        <div class="step-frame">
                            <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=800&q=80" alt="Select product">
                            <div class="step-caption">
                                <h4>Custom T-shirt</h4>
                                <p>Choose your base product and design type.</p>
                            </div>
                        </div>
                        <h3>Select</h3>
                    </article>

                    <article class="step-item">
                        <div class="step-frame">
                            <img src="https://images.unsplash.com/photo-1516383607781-913a19294fd1?auto=format&fit=crop&w=800&q=80" alt="Design preview">
                            <div class="step-caption">
                                <h4>3D Preview</h4>
                                <p>Customize your print and preview every detail.</p>
                            </div>
                        </div>
                        <h3>Design</h3>
                    </article>

                    <article class="step-item">
                        <div class="step-frame">
                            <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?auto=format&fit=crop&w=800&q=80" alt="Order tracking">
                            <div class="step-caption">
                                <h4>My Orders</h4>
                                <p>Confirm your order and track delivery status.</p>
                            </div>
                        </div>
                        <h3>Order</h3>
                    </article>
                </div>

                <div class="design-cta-wrap">
                    <a href="<?= base_url('products'); ?>" class="design-cta">Design now</a>
                </div>
            </div>
        </section>

        <section class="detail-row">
            <div class="container detail-grid">
                <div class="detail-icon">FNR</div>
                <div class="detail-content">
                    <h3>Negotiate Price</h3>
                    <p>The journey from a customer vision to a finished product requires clear communication and precision. For bulk orders and custom material requests, the negotiate price phase aligns value, timeline, and production quality.</p>
                </div>
            </div>
        </section>

        <section class="save-row">
            <div class="container save-grid">
                <div class="save-copy">
                    <h3>Save Designs</h3>
                    <p>Inspiration does not always happen on a deadline. Save designs lets you secure progress instantly and continue editing later so your final order stays polished and production-ready.</p>
                </div>

                <div class="save-gallery">
                    <h3>My Designs</h3>
                    <div class="gallery-label">Recents</div>
                    <div class="gallery-cards">
                        <article class="gallery-card">
                            <img src="https://images.unsplash.com/photo-1503341504253-dff4815485f1?auto=format&fit=crop&w=600&q=80" alt="Saved t-shirt design">
                            <div class="meta">
                                <h4>Coffee T-shirt</h4>
                                <p>Last edited 2 hours ago</p>
                            </div>
                        </article>

                        <article class="gallery-card">
                            <img src="https://images.unsplash.com/photo-1577937927133-66ef06acdf18?auto=format&fit=crop&w=600&q=80" alt="Saved mug design">
                            <div class="meta">
                                <h4>Coffee Mug</h4>
                                <p>Last edited yesterday</p>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?= view('include/foot_view'); ?>
    <script src="<?= base_url('hci-assist.js'); ?>"></script>
</body>
</html>


