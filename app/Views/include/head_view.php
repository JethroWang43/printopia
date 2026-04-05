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
    </style>
</head>
<body>