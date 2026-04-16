<?= view('include/head_view', ['title' => $title]); ?>

    <style>
        :root {
            --section-screen-height: calc(100vh - 70px);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            scroll-snap-type: y mandatory;
        }

        #main-content {
            position: relative;
        }

        .snap-panel {
            position: relative;
            scroll-snap-align: start;
            scroll-snap-stop: always;
            min-height: var(--section-screen-height);
            display: flex;
            align-items: center;
        }

        .hero-title {
            text-align: center;
            color: var(--brand-navy);
            margin: 8px 0 26px;
            font-size: clamp(2.1rem, 4vw, 3rem);
            letter-spacing: -0.02em;
        }

        .hero-subtitle {
            margin: -6px auto 20px;
            text-align: center;
            color: #425173;
            font-size: 1.02rem;
            max-width: 66ch;
            line-height: 1.55;
        }

        .steps-meta {
            display: grid;
            grid-template-columns: repeat(4, minmax(160px, 1fr));
            gap: 12px;
            margin: 0 0 18px;
            opacity: 0;
            transform: translateY(18px);
            transition: opacity 0.65s ease, transform 0.65s ease;
        }

        .section-visible .steps-meta {
            opacity: 1;
            transform: translateY(0);
        }

        .meta-card {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #dce5f2;
            border-radius: 14px;
            padding: 12px 14px;
            box-shadow: var(--shadow-soft);
        }

        .meta-value {
            display: block;
            font-size: 1.08rem;
            font-weight: 800;
            color: #17295d;
            margin-bottom: 2px;
        }

        .meta-label {
            font-size: 0.84rem;
            color: #5a6787;
            letter-spacing: 0.02em;
        }

        .steps-intro {
            padding: 34px 0 28px;
            background:
                radial-gradient(circle at 12% 10%, rgba(236, 167, 44, 0.18) 0%, rgba(236, 167, 44, 0) 32%),
                radial-gradient(circle at 90% 88%, rgba(16, 34, 79, 0.1) 0%, rgba(16, 34, 79, 0) 40%),
                #ffffff;
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
        }

        .steps-grid {
            padding: 8px 0 12px;
            display: grid;
            grid-template-columns: repeat(3, minmax(260px, 1fr));
            gap: 20px;
        }

        .step-item {
            text-align: center;
            opacity: 0;
            transform: translateY(26px) scale(0.98);
            transition: opacity 0.7s ease, transform 0.7s cubic-bezier(.2,.75,.2,1);
        }

        .section-visible .step-item {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .section-visible .step-item:nth-child(2) {
            transition-delay: 0.1s;
        }

        .section-visible .step-item:nth-child(3) {
            transition-delay: 0.2s;
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

        .process-strip {
            margin: 16px auto 10px;
            max-width: 840px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            opacity: 0;
            transform: translateY(18px);
            transition: opacity 0.65s ease, transform 0.65s ease;
            transition-delay: 0.18s;
        }

        .section-visible .process-strip {
            opacity: 1;
            transform: translateY(0);
        }

        .process-node {
            position: relative;
            text-align: center;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #d7e2f2;
            border-radius: 999px;
            padding: 9px 12px;
            font-weight: 700;
            color: #223764;
            font-size: 0.9rem;
            box-shadow: var(--shadow-soft);
        }

        .process-node:not(:last-child)::after {
            content: ">";
            position: absolute;
            right: -11px;
            top: 50%;
            transform: translateY(-50%);
            color: #b53b1e;
            font-weight: 800;
        }

        .design-cta-wrap {
            display: grid;
            place-items: center;
            margin: 14px 0 10px;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
            transition-delay: 0.28s;
        }

        .section-visible .design-cta-wrap {
            opacity: 1;
            transform: translateY(0);
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
            background:
                radial-gradient(circle at 8% 16%, rgba(255, 255, 255, 0.52) 0%, rgba(255, 255, 255, 0) 36%),
                radial-gradient(circle at 90% 84%, rgba(16, 34, 79, 0.1) 0%, rgba(16, 34, 79, 0) 42%),
                var(--surface);
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
            padding: 42px 0;
        }

        .detail-stage {
            background: rgba(255, 255, 255, 0.86);
            border: 1px solid #dce5f2;
            border-radius: 28px;
            box-shadow: var(--shadow-soft);
            padding: 26px 24px;
        }

        .detail-topline {
            width: fit-content;
            margin: 0 0 18px;
            padding: 7px 12px;
            border-radius: 999px;
            border: 1px solid #d9e1f1;
            background: #f6f9ff;
            color: #334a77;
            font-size: 0.78rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 220px minmax(0, 1fr) 280px;
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
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .section-visible .detail-icon {
            opacity: 1;
            transform: translateY(0);
        }

        .detail-content h3 {
            margin: 0 0 10px;
            color: var(--brand-navy);
            font-size: clamp(2rem, 3.8vw, 3rem);
        }

        .detail-content p {
            margin: 0;
            max-width: 62ch;
            color: #3f4862;
            font-size: 1.08rem;
            line-height: 1.6;
        }

        .detail-content {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.75s ease, transform 0.75s ease;
            transition-delay: 0.12s;
        }

        .section-visible .detail-content {
            opacity: 1;
            transform: translateY(0);
        }

        .detail-side {
            display: grid;
            gap: 12px;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.65s ease, transform 0.65s ease;
            transition-delay: 0.2s;
        }

        .section-visible .detail-side {
            opacity: 1;
            transform: translateY(0);
        }

        .detail-chip {
            background: #ffffff;
            border: 1px solid #dbe4f2;
            border-radius: 14px;
            padding: 12px 14px;
            box-shadow: var(--shadow-soft);
        }

        .detail-chip strong {
            color: #1a2e61;
            font-size: 0.94rem;
            display: block;
            margin-bottom: 4px;
        }

        .detail-chip span {
            color: #596887;
            font-size: 0.84rem;
            line-height: 1.45;
        }

        .detail-kpis {
            margin-top: 18px;
            display: grid;
            grid-template-columns: repeat(3, minmax(160px, 1fr));
            gap: 12px;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.7s ease, transform 0.7s ease;
            transition-delay: 0.26s;
        }

        .section-visible .detail-kpis {
            opacity: 1;
            transform: translateY(0);
        }

        .detail-kpi {
            background: #ffffff;
            border: 1px solid #dce4f1;
            border-radius: 14px;
            padding: 11px 13px;
            box-shadow: var(--shadow-soft);
        }

        .detail-kpi strong {
            display: block;
            color: #1c3263;
            font-size: 1.02rem;
            margin-bottom: 2px;
        }

        .detail-kpi span {
            color: #647494;
            font-size: 0.82rem;
            letter-spacing: 0.01em;
        }

        .save-row {
            background: #ffffff;
            padding: 38px 0 50px;
            border-top: 1px solid #e1e7f2;
        }

        .save-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
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

        .save-quick-grid {
            margin-top: 16px;
            display: grid;
            grid-template-columns: repeat(3, minmax(120px, 1fr));
            gap: 12px;
        }

        .quick-stat {
            background: #f8fbff;
            border: 1px solid #dfe8f4;
            border-radius: 14px;
            padding: 12px;
        }

        .quick-stat strong {
            display: block;
            color: #1d3163;
            font-size: 1rem;
            margin-bottom: 3px;
        }

        .quick-stat span {
            color: #647392;
            font-size: 0.8rem;
            letter-spacing: 0.02em;
        }

        .save-checklist {
            margin: 14px 0 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 8px;
        }

        .save-checklist li {
            color: #30446d;
            font-size: 0.92rem;
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .save-checklist li::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #d04624;
            flex-shrink: 0;
        }

        .save-copy,
        .save-gallery {
            opacity: 0;
            transform: translateY(22px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .section-visible .save-copy,
        .section-visible .save-gallery {
            opacity: 1;
            transform: translateY(0);
        }

        .section-visible .save-gallery {
            transition-delay: 0.1s;
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

        .save-gallery-toolbar {
            margin: 0 0 12px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .gallery-pill {
            border: 1px solid #d6e0f1;
            background: #f5f8ff;
            color: #334a78;
            font-size: 0.78rem;
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 700;
            letter-spacing: 0.02em;
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

        .scroll-arrow {
            margin: 8px auto 0;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1px solid #d6dfef;
            background: rgba(255, 255, 255, 0.92);
            color: #2b4475;
            display: grid;
            place-items: center;
            font-size: 1.35rem;
            line-height: 1;
            box-shadow: 0 8px 18px rgba(16, 34, 79, 0.12);
            animation: bounceDown 1.2s ease-in-out infinite;
        }

        @keyframes bounceDown {
            0%,
            100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(6px);
            }
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

            .steps-meta {
                grid-template-columns: repeat(2, minmax(150px, 1fr));
            }

            .process-strip {
                grid-template-columns: 1fr;
                max-width: 360px;
            }

            .process-node:not(:last-child)::after {
                display: none;
            }

            .save-grid {
                grid-template-columns: 1fr;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .detail-kpis {
                grid-template-columns: 1fr;
            }

            .detail-stage {
                padding: 18px 14px;
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

            body {
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

            .steps-meta,
            .process-strip {
                opacity: 1;
                transform: none;
                transition: none;
            }

            .step-item,
            .detail-icon,
            .detail-content,
            .detail-side,
            .detail-kpis,
            .save-copy,
            .save-gallery,
            .design-cta-wrap {
                opacity: 1;
                transform: none;
                transition: none;
            }

            .save-quick-grid {
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
    <?= view('include/nav_view', ['activePage' => 'how-it-works']); ?>

    <main id="main-content">
        <section class="steps-intro snap-panel how-panel">
            <div class="container">
                <h1 class="hero-title">How it Works?</h1>
                <p class="hero-subtitle">From idea to delivery, each stage is structured so you can move faster with confidence and creative control.</p>

                <div class="steps-meta">
                    <article class="meta-card">
                        <span class="meta-value">1,200+</span>
                        <span class="meta-label">Design briefs completed</span>
                    </article>
                    <article class="meta-card">
                        <span class="meta-value">48 hrs</span>
                        <span class="meta-label">Average first mockup</span>
                    </article>
                    <article class="meta-card">
                        <span class="meta-value">98%</span>
                        <span class="meta-label">Approval satisfaction rate</span>
                    </article>
                    <article class="meta-card">
                        <span class="meta-value">Live</span>
                        <span class="meta-label">Order tracking updates</span>
                    </article>
                </div>

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

                <div class="process-strip" aria-label="Process flow">
                    <div class="process-node">Select Product</div>
                    <div class="process-node">Design & Review</div>
                    <div class="process-node">Checkout & Track</div>
                </div>

                <div class="design-cta-wrap">
                    <a href="<?= base_url('products'); ?>" class="design-cta">Design now</a>
                </div>
                <div class="design-cta-wrap">
                    <div class="scroll-arrow" aria-hidden="true">&#8595;</div>
                </div>
            </div>
        </section>

        <section class="detail-row snap-panel how-panel">
            <div class="container">
                <div class="detail-stage">
                    <p class="detail-topline">Strategic pricing phase</p>

                    <div class="detail-grid">
                        <div class="detail-icon">FNR</div>
                        <div class="detail-content">
                            <h3>Negotiate Price</h3>
                            <p>The journey from a customer vision to a finished product requires clear communication and precision. For bulk orders and custom material requests, the negotiate price phase aligns value, timeline, and production quality.</p>
                        </div>
                        <aside class="detail-side" aria-label="Pricing details">
                            <article class="detail-chip">
                                <strong>Bulk tier discounts</strong>
                                <span>Transparent breaks for volume-based production runs.</span>
                            </article>
                            <article class="detail-chip">
                                <strong>Material flexibility</strong>
                                <span>Choose alternatives based on budget and durability targets.</span>
                            </article>
                            <article class="detail-chip">
                                <strong>Timeline balancing</strong>
                                <span>Adjust lead time and complexity to match delivery goals.</span>
                            </article>
                        </aside>
                    </div>

                    <div class="detail-kpis" aria-label="Pricing panel metrics">
                        <article class="detail-kpi">
                            <strong>24h</strong>
                            <span>Typical quote turnaround</span>
                        </article>
                        <article class="detail-kpi">
                            <strong>3 options</strong>
                            <span>Budget, standard, premium tiers</span>
                        </article>
                        <article class="detail-kpi">
                            <strong>Live updates</strong>
                            <span>Delivery timeline sync</span>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section class="save-row snap-panel how-panel">
            <div class="container save-grid">
                <div class="save-copy">
                    <h3>Save Designs</h3>
                    <p>Inspiration does not always happen on a deadline. Save designs lets you secure progress instantly and continue editing later so your final order stays polished and production-ready.</p>

                    <div class="save-quick-grid" aria-label="Save design highlights">
                        <article class="quick-stat">
                            <strong>Auto-save</strong>
                            <span>Every 30 seconds</span>
                        </article>
                        <article class="quick-stat">
                            <strong>Versioning</strong>
                            <span>Track each revision</span>
                        </article>
                        <article class="quick-stat">
                            <strong>Shared review</strong>
                            <span>Team collaboration</span>
                        </article>
                    </div>

                    <ul class="save-checklist">
                        <li>Resume any draft from your dashboard.</li>
                        <li>Duplicate winning layouts for new products.</li>
                        <li>Submit final-ready files in one click.</li>
                    </ul>
                </div>

                <div class="save-gallery">
                    <h3>My Designs</h3>
                    <div class="gallery-label">Recents</div>
                    <div class="save-gallery-toolbar">
                        <span class="gallery-pill">Drafts</span>
                        <span class="gallery-pill">Ready to order</span>
                        <span class="gallery-pill">Favorites</span>
                    </div>
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
    <script>
        const howPanels = document.querySelectorAll('.how-panel');

        const panelObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('section-visible');
                }
            });
        }, {
            threshold: 0.35
        });

        howPanels.forEach((panel) => panelObserver.observe(panel));
    </script>
    <script src="<?= base_url('hci-assist.js'); ?>"></script>
</body>
</html>


