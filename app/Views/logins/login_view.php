<main class="login-page">
  <div class="container-fluid p-0">
    <div class="row g-0 min-vh-100 flex-column-reverse flex-lg-row-reverse">

      <div class="col-lg-6 d-flex align-items-center justify-content-center branding-section">
        <div class="branding-content px-5">
          <h1 class="brand-title">Printopia</h1>
          <p class="brand-subtitle">Start creating unique<br>Designs Today</p>
          <ul class="feature-list list-unstyled">
            <li><span class="check-icon">✔</span> Custom 3D Model Design</li>
            <li><span class="check-icon">✔</span> Design Consultation</li>
            <li><span class="check-icon">✔</span> Quality Assurance</li>
            <li><span class="check-icon">✔</span> Trusted Order</li>
          </ul>
        </div>
      </div>

      <div class="col-lg-6 d-flex align-items-center justify-content-center bg-white">
        <div class="form-wrapper p-5 w-100">
          <header class="form-header mb-4">
            <h2 class="welcome-text">Welcome Back</h2>
            <p class="text-muted">Sign In to your account to continue</p>
          </header>

          <form id="loginForm">
            <div class="mb-3">
              <label class="form-label custom-label">Email Address <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
              </div>
            </div>

            <div class="mb-4">
                <label class="form-label custom-label">Password *</label>
                <div class="input-group password-group">
                    <span class="input-group-text">🔒</span>
                    <input type="password" name="password" class="form-control border-end-0" placeholder="Enter your password" required id="password-field">
                    <span class="input-group-text bg-white border-start-0 toggle-password" onclick="togglePassword()">👁</span>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 small">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="rememberMe">
                <label class="form-check-label fw-bold" for="rememberMe">Remember me</label>
              </div>
              <a href="#" class="text-decoration-none fw-bold forgot-link">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-signin w-100 py-2">
              Sign In &rarr;
            </button>
          </form>

          <p class="mt-4 text-center signup-text">
            Don't have an account? 
            <a href="<?= base_url('signup'); ?>" class="fw-bold text-decoration-none">Sign Up here</a>
          </p>
        </div>
      </div>

    </div>
  </div>
  <div class="bottom-accent"></div>
</main>

<!-- Include external JS -->
<script src="<?= base_url('js/auth.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('css/auth.css') ?>">