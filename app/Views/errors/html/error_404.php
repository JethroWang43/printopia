<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc(lang('Errors.pageNotFound')) ?> | Printopia</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --ink: #1e2747;
            --muted: #65708a;
            --line: rgba(40, 58, 104, 0.12);
            --paper: rgba(255, 255, 255, 0.84);
            --paper-strong: #ffffff;
            --accent: #d89a14;
            --accent-2: #ff7d59;
            --accent-3: #48b7a6;
            --shadow: 0 24px 60px rgba(31, 44, 86, 0.14);
            --shadow-soft: 0 10px 24px rgba(31, 44, 86, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            min-height: 100%;
        }

        body {
            margin: 0;
            font-family: 'Sora', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 10% 15%, rgba(216, 154, 20, 0.14), transparent 24%),
                radial-gradient(circle at 86% 18%, rgba(72, 183, 166, 0.14), transparent 22%),
                radial-gradient(circle at 80% 84%, rgba(255, 125, 89, 0.12), transparent 20%),
                linear-gradient(180deg, #f8f9fd 0%, #eef2f9 100%);
            overflow: hidden;
        }

        .scene {
            min-height: 100vh;
            position: relative;
            display: grid;
            place-items: center;
            padding: 28px;
        }

        .glow {
            position: absolute;
            border-radius: 999px;
            filter: blur(8px);
            opacity: 0.9;
            animation: float 12s ease-in-out infinite;
        }

        .glow.one {
            width: 220px;
            height: 220px;
            left: -40px;
            top: 80px;
            background: radial-gradient(circle, rgba(216, 154, 20, 0.26) 0%, rgba(216, 154, 20, 0) 70%);
        }

        .glow.two {
            width: 280px;
            height: 280px;
            right: -50px;
            bottom: 30px;
            background: radial-gradient(circle, rgba(72, 183, 166, 0.24) 0%, rgba(72, 183, 166, 0) 72%);
            animation-delay: -3s;
        }

        .card {
            width: min(1180px, 100%);
            background: var(--paper);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 32px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(18px);
            overflow: hidden;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0) 42%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.28), rgba(255, 255, 255, 0));
            pointer-events: none;
        }

        .grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            min-height: 640px;
            position: relative;
            z-index: 1;
        }

        .panel {
            padding: clamp(28px, 4vw, 52px);
        }

        .panel.hero {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 24px;
            position: relative;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            width: fit-content;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(16, 34, 79, 0.06);
            color: #33406a;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-size: 0.72rem;
        }

        .eyebrow span {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--accent);
            box-shadow: 0 0 0 6px rgba(216, 154, 20, 0.15);
        }

        .code-wrap {
            display: flex;
            align-items: baseline;
            gap: 18px;
            flex-wrap: wrap;
        }

        .code {
            font-size: clamp(4.5rem, 11vw, 8.75rem);
            line-height: 0.9;
            margin: 0;
            letter-spacing: -0.06em;
            color: var(--ink);
            text-shadow: 0 10px 30px rgba(20, 30, 60, 0.08);
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            border-radius: 999px;
            background: linear-gradient(180deg, rgba(216, 154, 20, 0.16), rgba(216, 154, 20, 0.08));
            color: #8a620f;
            font-weight: 700;
            box-shadow: var(--shadow-soft);
        }

        .tag::before {
            content: '✦';
            color: var(--accent);
            font-size: 1rem;
        }

        h1 {
            margin: 0;
            font-size: clamp(2rem, 4vw, 3.55rem);
            line-height: 1.02;
            letter-spacing: -0.04em;
        }

        .lede {
            max-width: 56ch;
            margin: 0;
            color: var(--muted);
            font-size: 1.02rem;
            line-height: 1.75;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 8px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border-radius: 16px;
            padding: 14px 18px;
            font-weight: 700;
            font-size: 0.95rem;
            text-decoration: none;
            border: 1px solid transparent;
            transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease, border-color 0.18s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn.primary {
            background: linear-gradient(180deg, var(--accent) 0%, var(--accent-2) 100%);
            color: #fff;
            box-shadow: 0 16px 30px rgba(216, 154, 20, 0.24);
        }

        .btn.secondary {
            background: rgba(255, 255, 255, 0.78);
            color: var(--ink);
            border-color: rgba(16, 34, 79, 0.12);
        }

        .btn.ghost {
            background: rgba(16, 34, 79, 0.05);
            color: var(--ink);
            border-color: rgba(16, 34, 79, 0.08);
        }

        .stack {
            display: grid;
            gap: 14px;
        }

        .callout {
            padding: 18px 18px 18px 20px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(16, 34, 79, 0.08);
            box-shadow: var(--shadow-soft);
        }

        .callout strong {
            display: block;
            margin-bottom: 6px;
            font-size: 0.96rem;
        }

        .callout p {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
            font-size: 0.92rem;
        }

        .panel.art {
            display: grid;
            align-content: center;
            gap: 16px;
            background:
                linear-gradient(180deg, rgba(16, 34, 79, 0.05), rgba(16, 34, 79, 0.015)),
                linear-gradient(135deg, rgba(255, 255, 255, 0.44), rgba(255, 255, 255, 0.18));
            border-left: 1px solid rgba(16, 34, 79, 0.08);
        }

        .orbital {
            position: relative;
            width: min(100%, 440px);
            aspect-ratio: 1;
            margin: 0 auto;
            display: grid;
            place-items: center;
        }

        .ring {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 1px dashed rgba(16, 34, 79, 0.12);
            animation: spin 24s linear infinite;
        }

        .ring:nth-child(2) {
            inset: 38px;
            animation-direction: reverse;
            animation-duration: 18s;
        }

        .ring:nth-child(3) {
            inset: 78px;
            animation-duration: 14s;
            border-style: solid;
            border-color: rgba(216, 154, 20, 0.16);
        }

        .error-badge {
            display: grid;
            place-items: center;
            width: min(100%, 240px);
            aspect-ratio: 1;
            border-radius: 50%;
            background: linear-gradient(180deg, #ffffff 0%, #f4f7fd 100%);
            box-shadow: 0 20px 40px rgba(20, 30, 60, 0.1);
            border: 1px solid rgba(16, 34, 79, 0.08);
            position: relative;
        }

        .error-badge::before {
            content: '404';
            font-size: clamp(3rem, 6vw, 4.5rem);
            font-weight: 800;
            letter-spacing: -0.08em;
            color: #243055;
        }

        .error-badge::after {
            content: 'Printopia';
            position: absolute;
            bottom: 34px;
            font-size: 0.8rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--accent);
            font-weight: 700;
        }

        .detail-card {
            width: min(100%, 440px);
            margin: 0 auto;
            padding: 20px 22px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.74);
            border: 1px solid rgba(16, 34, 79, 0.08);
            box-shadow: var(--shadow-soft);
        }

        .detail-card p {
            margin: 0;
            color: var(--muted);
            line-height: 1.7;
            font-size: 0.94rem;
        }

        .detail-card code {
            display: inline-block;
            margin-top: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(16, 34, 79, 0.05);
            color: #30406a;
            font-family: inherit;
            font-weight: 700;
            font-size: 0.82rem;
        }

        .hint {
            margin-top: 6px;
            color: var(--muted);
            font-size: 0.85rem;
        }

        .footer-note {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 6px;
            color: #71809c;
            font-size: 0.82rem;
        }

        .footer-note span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--accent-3);
        }

        @keyframes float {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50% { transform: translate3d(0, -14px, 0) scale(1.03); }
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 900px) {
            body {
                overflow: auto;
            }

            .scene {
                padding: 18px;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .panel.art {
                border-left: 0;
                border-top: 1px solid rgba(16, 34, 79, 0.08);
            }

            .code-wrap {
                gap: 12px;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <main class="scene">
        <div class="glow one" aria-hidden="true"></div>
        <div class="glow two" aria-hidden="true"></div>

        <section class="card" aria-labelledby="errorTitle">
            <div class="grid">
                <div class="panel hero">
                    <div class="eyebrow"><span></span> Page Not Found</div>

                    <div class="code-wrap">
                        <h1 class="code">404</h1>
                        <div class="tag">Lost in the catalog</div>
                    </div>

                    <h1 id="errorTitle">That page ran off the press.</h1>

                    <p class="lede">
                        The link you followed does not point to a valid route. Try heading back to the dashboard, or return home and continue from there.
                    </p>

                    <div class="actions">
                        <a class="btn primary" href="<?= base_url('/'); ?>">Go Home</a>
                        <a class="btn secondary" href="<?= base_url('dashboard'); ?>">Open Dashboard</a>
                        <button class="btn ghost" type="button" onclick="history.back()">Go Back</button>
                    </div>

                    <div class="stack">
                        <div class="callout">
                            <strong>Need a quick reset?</strong>
                            <p>Use the dashboard if you were trying to reach a section inside the app, or go home to start again.</p>
                        </div>

                        <div class="footer-note">
                            <span></span>
                            <div>Printopia keeps the workflow moving, even when a route is missing.</div>
                        </div>
                    </div>
                </div>

                <div class="panel art" aria-hidden="true">
                    <div class="orbital">
                        <div class="ring"></div>
                        <div class="ring"></div>
                        <div class="ring"></div>
                        <div class="error-badge"></div>
                    </div>

                    <div class="detail-card">
                        <p>
                            <?php if (ENVIRONMENT !== 'production') : ?>
                                <?= nl2br(esc($message)) ?>
                            <?php else : ?>
                                Sorry, we can’t find that page right now.
                            <?php endif; ?>
                        </p>
                        <code><?= esc(current_url()) ?></code>
                        <div class="hint">If this keeps happening, check the URL for a typo.</div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
