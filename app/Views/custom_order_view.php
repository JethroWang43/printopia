<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Order - Printopia</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --brand-gold: #e7a821;
            --brand-navy: #10224f;
            --white: #ffffff;
            --text: #1f2430;
            --muted: #596074;
            --surface: #f2f4f9;
            --shadow: 0 10px 30px rgba(16, 34, 79, 0.12);
            --line: #e0e8f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Sora", sans-serif;
            color: var(--text);
            background: linear-gradient(180deg, #f7f8fb 0%, #eef1f8 100%);
            min-height: 100vh;
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
            max-width: 1680px;
            margin: 0 auto;
            padding: 0 clamp(20px, 4vw, 84px);
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
            color: #162757;
            text-decoration: none;
            transition: opacity 0.2s ease;
        }

        .main-nav a:hover {
            opacity: 1;
        }

        .main-nav a.active {
            opacity: 1;
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

        .main-content {
            max-width: 100%;
            margin: 0 auto;
            padding: 40px clamp(20px, 4vw, 60px);
        }

        .page-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .page-header h1 {
            font-size: clamp(1.5rem, 4vw, 2.2rem);
            font-weight: 800;
            margin-bottom: 5px;
            color: var(--brand-navy);
        }

        .page-header p {
            font-size: 0.95rem;
            color: var(--muted);
        }

        .form-card {
            background: var(--white);
            border-radius: 16px;
            padding: 30px;
            box-shadow: var(--shadow);
            max-width: 100%;
            margin: 0 auto;
        }

        .form-sections-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .form-column {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .form-section {
            margin-bottom: 0;
        }

        .form-section:last-child {
            margin-bottom: 0;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--brand-navy);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @media (max-width: 1200px) {
            .form-sections-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            .form-sections-grid {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            margin-bottom: 14px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text);
            margin-bottom: 6px;
        }

        .form-control,
        .form-select {
            border: 2px solid var(--line);
            border-radius: 8px;
            padding: 10px 12px;
            font-family: inherit;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--brand-gold);
            box-shadow: 0 0 0 3px rgba(231, 168, 33, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--muted);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 14px;
        }

        @media (max-width: 1200px) {
            .form-sections-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            .form-sections-grid {
                grid-template-columns: 1fr;
            }
        }

        .form-check {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 12px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid var(--line);
            border-radius: 4px;
            cursor: pointer;
            accent-color: var(--brand-gold);
        }

        .form-check label {
            margin-bottom: 0;
            font-weight: 500;
            cursor: pointer;
        }

        .upload-area {
            border: 2px dashed var(--line);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: rgba(231, 168, 33, 0.05);
        }

        .upload-area:hover {
            border-color: var(--brand-gold);
            background: rgba(231, 168, 33, 0.1);
        }

        .upload-area i {
            font-size: 2rem;
            color: var(--brand-gold);
            display: block;
            margin-bottom: 10px;
        }

        .upload-area p {
            margin: 0;
            color: var(--text);
            font-weight: 600;
        }

        .upload-area .text-muted {
            font-size: 0.9rem;
            color: var(--muted) !important;
            display: block;
            margin-top: 5px;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 40px;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--brand-navy) 0%, #1a3a7f 100%);
            color: var(--white);
            border: none;
            padding: 14px 40px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(16, 34, 79, 0.25);
            color: var(--white);
            text-decoration: none;
        }

        .btn-reset {
            background: var(--surface);
            color: var(--text);
            border: 2px solid var(--line);
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-reset:hover {
            background: var(--line);
        }

        .icon-section {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--brand-navy) 0%, #1a3a7f 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 700;
        }

        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .error-message {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }
    </style>
</head>
<body>
    <?= view('include/nav_view', ['activePage' => '']); ?>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <div class="page-header">
            <h1>Custom Order</h1>
            <p>Dream it, Print it, Tell Us About Your Custom Projects</p>
        </div>

        <div class="form-card">
            <div class="success-message" id="successMsg">
                ✓ Your custom order has been submitted successfully!
            </div>
            <div class="error-message" id="errorMsg">
                ✗ Please fill in all required fields.
            </div>

            <form id="customOrderForm">
                <div class="form-sections-grid">
                    <!-- LEFT COLUMN -->
                    <div class="form-column">
                        <!-- Project Information Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <div class="icon-section">
                                    <i class="bi bi-briefcase"></i>
                                </div>
                                Project Information
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="projectTitle">Project Title *</label>
                                    <input type="text" class="form-control" id="projectTitle" placeholder="Give your project a name" required>
                                </div>
                                <div class="form-group">
                                    <label for="projectType">Project Type *</label>
                                    <input type="text" class="form-control" id="projectType" placeholder="e.g., Branding, Packaging" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="materialType">Type of Material *</label>
                                <select class="form-select" id="materialType" required>
                                    <option value="">Select Paper Type</option>
                                    <option value="standard-paper">Standard Paper</option>
                                    <option value="glossy-paper">Glossy Paper</option>
                                    <option value="matte-paper">Matte Paper</option>
                                    <option value="cardstock">Cardstock</option>
                                    <option value="premium-paper">Premium Finishes</option>
                                    <option value="a4-paper">A4 Paper</option>
                                    <option value="letter-paper">Letter Paper</option>
                                    <option value="longbond">Longbond</option>
                                </select>
                            </div>
                        </div>

                        <!-- Technical Details Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <div class="icon-section">
                                    <i class="bi bi-pencil-square"></i>
                                </div>
                                Technical Details
                            </div>
                            <div class="form-group">
                                <label for="description">Detailed Description *</label>
                                <textarea class="form-control" id="description" placeholder="Describe your project in detail, dimensions, colors, specifications, etc." required></textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="quantity">Quantity *</label>
                                    <input type="number" class="form-control" id="quantity" placeholder="How many units?" min="1" required>
                                </div>
                                <div class="form-group">
                                    <label for="deadline">Estimated Deadline *</label>
                                    <input type="date" class="form-control" id="deadline" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="form-column">
                        <!-- File Upload Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <div class="icon-section">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                </div>
                                Upload Files
                            </div>
                            <div class="form-group">
                                <label>Design Files or Reference Images</label>
                                <div class="upload-area" id="uploadArea">
                                    <i class="bi bi-file-arrow-up"></i>
                                    <p>Click to upload or drag and drop</p>
                                    <span class="text-muted">PNG, JPG, PDF, AI, PSD up to 50MB</span>
                                    <input type="file" id="fileInput" multiple style="display: none;">
                                </div>
                            </div>
                        </div>

                        <!-- Delivery & Payment Section -->
                        <div class="form-section">
                            <div class="section-title">
                                <div class="icon-section">
                                    <i class="bi bi-truck"></i>
                                </div>
                                Delivery & Payment
                            </div>
                            
                            <div class="form-group">
                                <label>Delivery Method *</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="delivery" id="delivery_pickup" value="pickup" required>
                                    <label class="form-check-label" for="delivery_pickup">Pick up</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="delivery" id="delivery_deliver" value="deliver">
                                    <label class="form-check-label" for="delivery_deliver">Deliver</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Payment Method *</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment" id="payment_cash" value="cash" required>
                                    <label class="form-check-label" for="payment_cash">Cash</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment" id="payment_gcash" value="gcash">
                                    <label class="form-check-label" for="payment_gcash">Gcash</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-check-lg"></i> Submit Order
                    </button>
                    <button type="reset" class="btn-reset">Clear Form</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('hci-assist.js'); ?>"></script>
    <script>
        // File upload handling
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');

        uploadArea.addEventListener('click', () => fileInput.click());

        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.style.backgroundColor = 'rgba(231, 168, 33, 0.15)';
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.style.backgroundColor = 'rgba(231, 168, 33, 0.05)';
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.style.backgroundColor = 'rgba(231, 168, 33, 0.05)';
            fileInput.files = e.dataTransfer.files;
        });

        // Form submission
        const form = document.getElementById('customOrderForm');
        const successMsg = document.getElementById('successMsg');
        const errorMsg = document.getElementById('errorMsg');

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Simple validation
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (field.type === 'radio') {
                    const radioGroup = form.querySelector(`input[name="${field.name}"]:checked`);
                    if (!radioGroup) isValid = false;
                } else if (!field.value.trim()) {
                    isValid = false;
                }
            });

            if (!isValid) {
                errorMsg.style.display = 'block';
                successMsg.style.display = 'none';
                return;
            }

            // Show success message
            successMsg.style.display = 'block';
            errorMsg.style.display = 'none';
            
            // Reset form after 2 seconds
            setTimeout(() => {
                form.reset();
                successMsg.style.display = 'none';
            }, 2000);
        });
    </script>
</body>
</html>


