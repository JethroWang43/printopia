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
            --surface-white: #ffffff;
            --text: #1f2430;
            --muted: #596074;
            --line: #dbe3ef;
            --shadow-soft: 0 10px 22px rgba(16, 34, 79, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Sora", sans-serif;
            color: var(--text);
            background: linear-gradient(180deg, #f7f8fb 0%, #edf1f8 100%);
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
            width: min(1640px, 97vw);
            margin: 0 auto;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 35;
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

        .hero {
            padding: clamp(44px, 6vw, 78px) 0 26px;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 24px;
            align-items: stretch;
        }

        .hero-panel {
            background: linear-gradient(130deg, #0f244f 0%, #1f3b79 58%, #e7a821 190%);
            color: #f8fbff;
            border-radius: 20px;
            padding: clamp(26px, 4vw, 46px);
            box-shadow: var(--shadow-soft);
            position: relative;
            overflow: hidden;
        }

        .hero-panel::after {
            content: "";
            position: absolute;
            right: -30px;
            bottom: -40px;
            width: 210px;
            height: 210px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.25), rgba(255, 255, 255, 0));
        }

        .hero-panel h1 {
            margin: 0;
            font-size: clamp(2rem, 3.8vw, 3.5rem);
            line-height: 1.06;
        }

        .hero-panel p {
            margin: 14px 0 0;
            max-width: 620px;
            color: rgba(245, 249, 255, 0.92);
            font-size: 1.02rem;
            line-height: 1.65;
        }

        .contact-cards {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .card {
            background: var(--surface-white);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 18px 18px;
            box-shadow: 0 8px 18px rgba(16, 34, 79, 0.08);
        }

        .card h3 {
            margin: 0 0 4px;
            color: var(--brand-navy);
            font-size: 1.1rem;
        }

        .card p {
            margin: 0;
            color: var(--muted);
            font-size: 0.93rem;
            line-height: 1.55;
        }

        .content {
            padding: 8px 0 56px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 24px;
            align-items: start;
        }

        .form-panel,
        .info-panel {
            background: var(--surface-white);
            border: 1px solid var(--line);
            border-radius: 20px;
            box-shadow: var(--shadow-soft);
            padding: clamp(18px, 3vw, 28px);
        }

        .panel-title {
            margin: 0;
            color: var(--brand-navy);
            font-size: clamp(1.45rem, 3vw, 2.1rem);
        }

        .panel-subtitle {
            margin: 8px 0 20px;
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .field-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 12px;
        }

        .field {
            display: grid;
            gap: 6px;
        }

        .field label {
            font-size: 0.82rem;
            font-weight: 700;
            color: #334066;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .field input,
        .field textarea,
        .field select {
            width: 100%;
            border: 1px solid #cfd8ec;
            border-radius: 10px;
            padding: 12px 12px;
            font: inherit;
            color: var(--text);
            background: #f9fbff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .field input:focus,
        .field textarea:focus,
        .field select:focus {
            outline: none;
            border-color: #5c7ac1;
            box-shadow: 0 0 0 3px rgba(92, 122, 193, 0.16);
        }

        .field textarea {
            resize: vertical;
            min-height: 132px;
        }

        .submit-wrap {
            margin-top: 14px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn {
            border: none;
            border-radius: 9px;
            background: linear-gradient(180deg, #bf2e1e 0%, #9c1e13 100%);
            color: #fff;
            padding: 12px 18px;
            font-weight: 700;
            font-size: 0.92rem;
            cursor: pointer;
            box-shadow: 0 8px 16px rgba(146, 23, 12, 0.32);
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .muted-note {
            color: #6f778a;
            font-size: 0.83rem;
        }

        .info-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 14px;
        }

        .info-item {
            border: 1px solid #dce3f1;
            background: #f6f8fe;
            border-radius: 12px;
            padding: 14px;
        }

        .info-item h4 {
            margin: 0 0 4px;
            color: var(--brand-navy);
            font-size: 0.98rem;
        }

        .info-item p {
            margin: 0;
            color: #4f5770;
            font-size: 0.9rem;
            line-height: 1.55;
        }

        .map-block {
            margin-top: 16px;
            border-radius: 14px;
            border: 1px solid #d7deee;
            overflow: hidden;
            background: linear-gradient(135deg, #f4f7ff 0%, #e8eefb 55%, #f8fbff 100%);
            min-height: 180px;
            display: grid;
            place-items: center;
            color: #32416a;
            font-weight: 700;
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

        @media (max-width: 1080px) {
            .hero-grid,
            .content-grid {
                grid-template-columns: 1fr;
            }

            .field-row {
                grid-template-columns: 1fr;
            }
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

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <?= view('include/nav_view', ['activePage' => 'contact']); ?>

    <main id="main-content">
        <section class="hero">
            <div class="container hero-grid">
                <div class="hero-panel">
                    <h1>Let us build your next print.</h1>
                    <p>Share your project goals and timeline. Our team can help from concept review to final production with quality checks every step of the way.</p>
                </div>

                <div class="contact-cards">
                    <article class="card">
                        <h3>Phone</h3>
                        <p>0922-4756841</p>
                    </article>
                    <article class="card">
                        <h3>Email</h3>
                        <p>esensoweta61@gmail.com</p>
                    </article>
                    <article class="card">
                        <h3>Studio</h3>
                        <p>Tanauan, Batangas<br>Mon-Sat, 9:00 AM to 7:00 PM</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container content-grid">
                <section class="form-panel">
                    <h2 class="panel-title">Project Inquiry</h2>
                    <p class="panel-subtitle">Tell us what you want to print and we will send options, pricing, and estimated turnaround.</p>

                    <form action="#" method="post" novalidate>
                        <div class="field-row">
                            <div class="field">
                                <label for="full_name">Full Name</label>
                                <input id="full_name" name="full_name" type="text" placeholder="Juan Dela Cruz" required autocomplete="name">
                            </div>
                            <div class="field">
                                <label for="email">Email Address</label>
                                <input id="email" name="email" type="email" placeholder="you@email.com" required autocomplete="email">
                            </div>
                        </div>

                        <div class="field-row">
                            <div class="field">
                                <label for="phone">Mobile Number</label>
                                <input id="phone" name="phone" type="text" placeholder="09xx-xxx-xxxx" inputmode="tel" autocomplete="tel" required>
                            </div>
                            <div class="field">
                                <label for="service">Service</label>
                                <select id="service" name="service" required>
                                    <option value="">Select a service</option>
                                    <option value="shirt">Custom T-Shirt</option>
                                    <option value="mug">Custom Mug</option>
                                    <option value="poster">Poster Printing</option>
                                    <option value="bulk">Bulk Orders</option>
                                </select>
                            </div>
                        </div>

                        <div class="field">
                            <label for="message">Project Details</label>
                            <textarea id="message" name="message" placeholder="Describe your design, quantity, preferred material, and target date." required></textarea>
                        </div>

                        <div class="submit-wrap">
                            <button class="btn" type="submit">Send Inquiry</button>
                            <span class="muted-note">Response time is usually within 1 business day.</span>
                        </div>
                    </form>
                </section>

                <aside class="info-panel">
                    <h2 class="panel-title">Contact Details</h2>
                    <p class="panel-subtitle">Reach us directly for urgent requests and production follow-ups.</p>

                    <ul class="info-list">
                        <li class="info-item">
                            <h4>General Support</h4>
                            <p>+63 922 475 6841</p>
                        </li>
                        <li class="info-item">
                            <h4>Sales Email</h4>
                            <p>esensoweta61@gmail.com</p>
                        </li>
                        <li class="info-item">
                            <h4>Address</h4>
                            <p>Tanauan, Batangas, Philippines</p>
                        </li>
                    </ul>

                    <div class="map-block">Map Preview Area</div>
                </aside>
            </div>
        </section>
    </main>

    <?= view('include/foot_view'); ?>
    <script src="<?= base_url('hci-assist.js'); ?>"></script>
</body>
</html>


