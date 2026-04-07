<style>
    html,
    body {
        min-height: 100vh;
    }

    body {
        display: flex;
        flex-direction: column;
    }

    .site-footer {
        width: 100%;
        margin-top: auto;
        flex-shrink: 0;
        margin-top: 48px;
        background: var(--brand-gold);
        border-top: 1px solid #cc9516;
        color: #172a5d;
        padding: 44px 0 20px;
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

    @media (max-width: 780px) {
        .footer-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<footer class="site-footer" id="contact">
    <div class="container">
        <div class="footer-grid">
            <section>
                <div class="footer-logo">
                    <span class="logo-mark">P</span>
                    <span>Printopia</span>
                </div>
                <p>Your partner for custom printing solutions with a clean and easy ordering workflow.</p>
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
                    <li>Trusted order handling</li>
                </ul>
            </section>

            <section>
                <h5>Contact Information</h5>
                <ul>
                    <li>0922-4756841</li>
                    <li>esensoweta61@gmail.com</li>
                    <li>Tanauan, Batangas</li>
                </ul>
            </section>
        </div>

        <div class="footer-bottom">&copy; 2026 Printopia. All rights reserved.</div>
    </div>
</footer>
</body>
</html>