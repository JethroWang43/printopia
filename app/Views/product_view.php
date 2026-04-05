<?= view('include/head_view', ['title' => $title]); ?>

    <style>
        .grid-products {
            box-shadow: 0 12px 22px rgba(16, 34, 79, 0.16);
            overflow: hidden;
            display: none;
            z-index: 80;
        }

        .user-menu:hover .user-dropdown,
        .user-menu:focus-within .user-dropdown {
            display: block;
        }

        .products-page {
            padding: clamp(54px, 7vw, 96px) 0 clamp(62px, 8vw, 108px);
            flex: 1 0 auto;
        }

        .section-title {
            text-align: center;
            margin: 0 0 12px;
            color: var(--brand-navy);
            font-size: clamp(2.1rem, 3.8vw, 3.1rem);
        }

        .section-subtitle {
            margin: 0 auto;
            text-align: center;
            max-width: 840px;
            color: var(--danger);
            font-size: 1.03rem;
            font-weight: 600;
        }

        .product-toolbar {
            margin: 28px auto 0;
            width: min(760px, 100%);
        }

        .search-label {
            display: block;
            margin-bottom: 10px;
            font-size: 0.9rem;
            font-weight: 700;
            color: #1f346e;
        }

        .search-row {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .search-input {
            flex: 1;
            min-height: 48px;
            border-radius: 12px;
            border: 1px solid #ced8ec;
            background: #ffffff;
            padding: 0 14px;
            font: inherit;
            color: #12255a;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .search-input:focus {
            border-color: #183271;
            box-shadow: 0 0 0 3px rgba(24, 50, 113, 0.15);
        }

        .search-clear {
            min-height: 48px;
            border: 1px solid #cfd8ea;
            background: #ffffff;
            color: #1b3068;
            border-radius: 12px;
            padding: 0 16px;
            font: inherit;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
        }

        .search-meta {
            margin: 10px 0 0;
            color: #56617b;
            font-size: 0.88rem;
            font-weight: 600;
        }

        .is-hidden {
            display: none !important;
        }

        .search-empty {
            margin-top: 22px;
            padding: 18px;
            border-radius: 12px;
            border: 1px dashed #c2cde4;
            background: #f7f9ff;
            color: #2a3d73;
            text-align: center;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .products-grid {
            margin-top: 36px;
            display: grid;
            grid-template-columns: repeat(3, minmax(300px, 1fr));
            gap: 24px;
        }

        .product-card {
            background: var(--white);
            border-radius: 18px;
            border: 1px solid #dfe6f3;
            box-shadow: var(--shadow-soft);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-hover);
        }

        .product-media {
            height: 240px;
            display: grid;
            place-items: center;
            background: var(--surface);
            position: relative;
            isolation: isolate;
            border-bottom: 1px solid #e6ecf7;
        }

        .media-fallback {
            width: 100%;
            height: 100%;
            display: block;
            position: relative;
            z-index: 1;
        }

        .media-fallback::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(16, 34, 79, 0.16), rgba(16, 34, 79, 0));
        }

        .media-fallback span {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            font-size: 4.4rem;
            color: rgba(18, 38, 91, 0.92);
            z-index: 1;
        }

        .media-paper {
            background: linear-gradient(135deg, #eef3ff 0%, #d8e1f8 45%, #c4d2f1 100%);
        }

        .media-custom {
            background: linear-gradient(145deg, #f0f4ff 0%, #dce6fb 50%, #c8d7f5 100%);
        }

        .media-shirt {
            background: linear-gradient(145deg, #f6f8fc 0%, #e8edf6 50%, #d6e0ef 100%);
        }

        .media-mug {
            background: linear-gradient(145deg, #f8f8f8 0%, #e9edf4 52%, #dce3ef 100%);
        }

        .media-plaque {
            background: linear-gradient(145deg, #e7eef7 0%, #d3e2f3 48%, #c2d8eb 100%);
        }

        .media-tote {
            background: linear-gradient(145deg, #fff1e6 0%, #ffd2af 54%, #f3a26b 100%);
        }

        .media-poster {
            background: linear-gradient(145deg, #edf0f8 0%, #d4dcf0 50%, #c4d2eb 100%);
        }

        .media-label {
            position: absolute;
            left: 14px;
            bottom: 12px;
            z-index: 2;
            background: rgba(16, 34, 79, 0.8);
            color: #f8fbff;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.2px;
            padding: 7px 10px;
            border-radius: 999px;
        }

        .product-body {
            padding: 18px;
        }

        .product-title {
            margin: 0 0 8px;
            font-size: 1.16rem;
            color: #181d2a;
        }

        .product-desc {
            margin: 0;
            color: var(--muted);
            font-size: 0.93rem;
            line-height: 1.62;
            min-height: 48px;
        }

        .product-footer {
            margin-top: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .product-price {
            font-size: 1.22rem;
            font-weight: 800;
            color: var(--danger);
        }

        .product-btn {
            display: inline-block;
            white-space: nowrap;
            border-radius: 10px;
            padding: 11px 18px;
            font-size: 0.86rem;
            font-weight: 700;
            color: var(--white);
            background: linear-gradient(180deg, #bf2e1e 0%, #9c1e13 100%);
            box-shadow: 0 10px 14px rgba(156, 30, 19, 0.24);
            transition: transform 0.2s ease;
        }

        .product-btn:hover {
            transform: translateY(-2px);
        }

        .site-footer {
            background: var(--brand-gold);
            border-top: 1px solid #cc9516;
            color: #172a5d;
            padding: 40px 0 18px;
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
            .products-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 1080px) {
            .footer-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 780px) {
            .topbar .container,
            .site-footer .container {
                padding-left: 14px;
                padding-right: 14px;
            }

            .topbar-inner {
                min-height: auto;
                flex-wrap: wrap;
                justify-content: center;
                padding: 14px 0;
            }

            .logo,
            .user-menu {
                flex: 0 0 auto;
                margin-left: 0;
            }

            .main-nav {
                width: 100%;
                flex-wrap: wrap;
                gap: 16px;
                font-size: 0.92rem;
            }

            .products-grid {
                grid-template-columns: 1fr;
            }

            .search-row {
                flex-direction: column;
            }

            .search-clear {
                width: 100%;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?= view('include/nav_view', ['activePage' => 'products']); ?>

    <main class="products-page" id="main-content">
        <div class="container">
            <h1 class="section-title">Our Products</h1>
            <p class="section-subtitle">Choose from our wide selection of customizable merchandise.</p>

            <section class="product-toolbar" aria-label="Product search">
                <label class="search-label" for="productSearch">Find a product</label>
                <div class="search-row">
                    <input
                        type="search"
                        id="productSearch"
                        class="search-input"
                        placeholder="Search by product name, tag, or keyword"
                        autocomplete="off"
                    >
                    <button type="button" class="search-clear" id="clearSearch">Clear</button>
                </div>
                <p class="search-meta" id="searchMeta">Showing 7 products</p>
            </section>

            <section class="products-grid" aria-label="Product list" id="productsGrid">
                <article class="product-card" data-search="custom prints custom request design upload personalized">
                    <div class="product-media">
                        <div class="media-fallback media-custom"><span>🎨</span></div>
                        <span class="media-label">Custom Request</span>
                    </div>
                    <div class="product-body">
                        <h2 class="product-title">Custom Prints</h2>
                        <p class="product-desc">Have a special idea? Send your design and we will create a fully custom print for your request.</p>
                        <div class="product-footer">
                            <span class="product-price">From 11.99</span>
                            <a href="<?= base_url('custom-order'); ?>" class="product-btn">Submit your design</a>
                        </div>
                    </div>
                </article>

                <article class="product-card" data-search="paper prints upload paper layout size output">
                    <div class="product-media">
                        <div class="media-fallback media-paper"><span>🧾</span></div>
                        <span class="media-label">Paper Print Upload</span>
                    </div>
                    <div class="product-body">
                        <h2 class="product-title">Paper Prints</h2>
                        <p class="product-desc">Upload your own layout and choose from Standard, Glossy, Matte, Cardstock, Premium finishes, A4, Letter, or Longbond paper.</p>
                        <div class="product-footer">
                            <span class="product-price">From 9.99</span>
                            <a href="<?= base_url('custom-order'); ?>" class="product-btn">Submit your design</a>
                        </div>
                    </div>
                </article>

                <article class="product-card" data-search="shirt t-shirt clothing apparel best seller cotton">
                    <div class="product-media">
                        <div class="media-fallback media-shirt"><span>👕</span></div>
                        <span class="media-label">Best Seller</span>
                    </div>
                    <div class="product-body">
                        <h2 class="product-title">Custom T-Shirt</h2>
                        <p class="product-desc">Premium cotton t-shirt with your custom design.</p>
                        <div class="product-footer">
                            <span class="product-price">19.99</span>
                            <a href="<?= base_url('custom-order'); ?>" class="product-btn">Customize</a>
                        </div>
                    </div>
                </article>

                <article class="product-card" data-search="mug cup drink gift personalized popular">
                    <div class="product-media">
                        <div class="media-fallback media-mug"><span>☕</span></div>
                        <span class="media-label">Popular Gift</span>
                    </div>
                    <div class="product-body">
                        <h2 class="product-title">Custom Mug</h2>
                        <p class="product-desc">Ceramic mug with personalized graphics.</p>
                        <div class="product-footer">
                            <span class="product-price">14.99</span>
                            <a href="<?= base_url('custom-order'); ?>" class="product-btn">Customize</a>
                        </div>
                    </div>
                </article>

                <article class="product-card" data-search="plaque award recognition premium">
                    <div class="product-media">
                        <div class="media-fallback media-plaque"><span>🏆</span></div>
                        <span class="media-label">Award Collection</span>
                    </div>
                    <div class="product-body">
                        <h2 class="product-title">Custom Plaque</h2>
                        <p class="product-desc">Elegant and professional plaque design for awards and recognition.</p>
                        <div class="product-footer">
                            <span class="product-price">39.99</span>
                            <a href="<?= base_url('custom-order'); ?>" class="product-btn">Customize</a>
                        </div>
                    </div>
                </article>

                <article class="product-card" data-search="tote bag canvas daily essentials reusable">
                    <div class="product-media">
                        <div class="media-fallback media-tote"><span>👜</span></div>
                        <span class="media-label">Daily Essentials</span>
                    </div>
                    <div class="product-body">
                        <h2 class="product-title">Tote Bag</h2>
                        <p class="product-desc">Durable canvas tote bag for everyday use.</p>
                        <div class="product-footer">
                            <span class="product-price">24.99</span>
                            <a href="<?= base_url('custom-order'); ?>" class="product-btn">Customize</a>
                        </div>
                    </div>
                </article>

                <article class="product-card" data-search="poster wall decor print art frame">
                    <div class="product-media">
                        <div class="media-fallback media-poster"><span>🖼</span></div>
                        <span class="media-label">Wall Ready</span>
                    </div>
                    <div class="product-body">
                        <h2 class="product-title">Custom Poster</h2>
                        <p class="product-desc">High-quality poster print in various sizes.</p>
                        <div class="product-footer">
                            <span class="product-price">12.99</span>
                            <a href="<?= base_url('custom-order'); ?>" class="product-btn">Customize</a>
                        </div>
                    </div>
                </article>
            </section>

            <p id="searchEmpty" class="search-empty is-hidden">No products matched your search. Try a different keyword.</p>
        </div>
    </main>

    <?= view('include/foot_view'); ?>

    <script src="<?= base_url('hci-assist.js'); ?>"></script>
    <script>
        (function () {
            const input = document.getElementById('productSearch');
            const clearButton = document.getElementById('clearSearch');
            const cards = Array.from(document.querySelectorAll('.product-card'));
            const meta = document.getElementById('searchMeta');
            const empty = document.getElementById('searchEmpty');
            const total = cards.length;

            function updateSearch() {
                const term = input.value.trim().toLowerCase();
                let visible = 0;

                cards.forEach((card) => {
                    const title = (card.querySelector('.product-title')?.textContent || '').toLowerCase();
                    const desc = (card.querySelector('.product-desc')?.textContent || '').toLowerCase();
                    const tag = (card.querySelector('.media-label')?.textContent || '').toLowerCase();
                    const extra = (card.getAttribute('data-search') || '').toLowerCase();
                    const haystack = [title, desc, tag, extra].join(' ');
                    const matched = term === '' || haystack.includes(term);

                    card.classList.toggle('is-hidden', !matched);
                    if (matched) {
                        visible += 1;
                    }
                });

                meta.textContent = `Showing ${visible} of ${total} products`;
                empty.classList.toggle('is-hidden', visible !== 0);
            }

            input.addEventListener('input', updateSearch);
            clearButton.addEventListener('click', function () {
                input.value = '';
                input.focus();
                updateSearch();
            });
        })();
    </script>
</body>
</html>


