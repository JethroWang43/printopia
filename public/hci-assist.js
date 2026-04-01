(function () {
    const doc = document;
    const body = doc.body;

    if (!body) {
        return;
    }

    function ensureSkipLink() {
        const main = doc.querySelector('main, .main-content');
        if (!main) {
            return;
        }

        if (!main.id) {
            main.id = 'main-content';
        }

        if (doc.querySelector('.skip-link')) {
            return;
        }

        const style = doc.createElement('style');
        style.textContent = '.skip-link{position:absolute;left:12px;top:-100px;padding:10px 14px;background:#10224f;color:#fff;border-radius:8px;z-index:9999;text-decoration:none;font-weight:700}.skip-link:focus{top:12px}';
        doc.head.appendChild(style);

        const skip = doc.createElement('a');
        skip.className = 'skip-link';
        skip.href = '#main-content';
        skip.textContent = 'Skip to main content';
        body.insertBefore(skip, body.firstChild);
    }

    function makeRoleMenuKeyboardFriendly() {
        const menu = doc.querySelector('.user-menu');
        const button = doc.querySelector('.user-chip-btn');
        const dropdown = doc.querySelector('.user-dropdown');

        if (!menu || !button || !dropdown) {
            return;
        }

        const openMenu = function () {
            menu.classList.add('menu-open');
            button.setAttribute('aria-expanded', 'true');
            dropdown.style.display = 'block';
        };

        const closeMenu = function () {
            menu.classList.remove('menu-open');
            button.setAttribute('aria-expanded', 'false');
            dropdown.style.display = '';
        };

        button.addEventListener('click', function () {
            if (menu.classList.contains('menu-open')) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        doc.addEventListener('click', function (event) {
            if (!menu.contains(event.target)) {
                closeMenu();
            }
        });

        doc.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeMenu();
                button.focus();
            }
        });
    }

    function improveDeadLinks() {
        const contactHref = doc.querySelector('.main-nav a[href*="contact"]')?.getAttribute('href') || 'contact';
        const customOrderHref = doc.querySelector('a[href*="custom-order"]')?.getAttribute('href') || 'custom-order';

        const callLink = doc.querySelector('a[aria-label="Call"][href="#"]');
        if (callLink) {
            callLink.href = 'tel:+639224756841';
            callLink.setAttribute('title', 'Call Printopia');
        }

        const supportLink = doc.querySelector('a[aria-label="Support"][href="#"]');
        if (supportLink) {
            supportLink.href = contactHref;
            supportLink.setAttribute('title', 'Open contact support');
        }

        const chatLink = doc.querySelector('a[aria-label="Chat"][href="#"]');
        if (chatLink) {
            chatLink.href = contactHref;
            chatLink.setAttribute('title', 'Message support team');
        }

        const ctaLinks = doc.querySelectorAll('a.hero-cta[href="#"], a.cta-button[href="#"], a.product-btn[href="#"]');
        ctaLinks.forEach(function (link) {
            link.href = customOrderHref;
        });
    }

    function addFriendlyFormHints() {
        const form = doc.querySelector('form');
        if (!form) {
            return;
        }

        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(function (field) {
            field.setAttribute('aria-required', 'true');
            field.addEventListener('invalid', function () {
                field.setAttribute('aria-invalid', 'true');
            });
            field.addEventListener('input', function () {
                field.setAttribute('aria-invalid', 'false');
            });
        });
    }

    ensureSkipLink();
    makeRoleMenuKeyboardFriendly();
    improveDeadLinks();
    addFriendlyFormHints();
})();
