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