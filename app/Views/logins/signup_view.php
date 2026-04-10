<main class="register-page">
  <div class="container-fluid p-0">
    <div class="row g-0 min-vh-100">
      <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center branding-section">
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
            <h2 class="fw-bold h1">Create Account</h2>
            <p class="text-muted">Let's create amazing designs</p>
          </header>

          <form id="signupForm">
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label custom-label">First Name *</label>
                <div class="input-group">
                  <span class="input-group-text">👤</span>
                  <input type="text" name="first_name" class="form-control" placeholder="Enter first name" required>
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label custom-label">Middle Name</label>
                <div class="input-group">
                  <span class="input-group-text">👤</span>
                  <input type="text" name="middle_name" class="form-control" placeholder="Optional">
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label custom-label">Last Name *</label>
                <div class="input-group">
                  <span class="input-group-text">👤</span>
                  <input type="text" name="last_name" class="form-control" placeholder="Enter last name" required>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label custom-label">Email Address *</label>
              <div class="input-group">
                <span class="input-group-text">✉</span>
                <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label custom-label">Phone Number *</label>
              <div class="input-group">
                <span class="input-group-text">📞</span>
                <input type="tel" name="phone_number" class="form-control" placeholder="Enter your phone number" required>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label custom-label">Password *</label>
              <div class="input-group password-group">
                <span class="input-group-text">🔒</span>
                <input type="password" name="password" class="form-control border-end-0" placeholder="Create a password" required id="password-field">
                <span class="input-group-text bg-white border-start-0 toggle-password" onclick="togglePassword('password-field')">👁</span>
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label custom-label">Confirm Password *</label>
              <div class="input-group password-group">
                <span class="input-group-text">🔒</span>
                <input type="password" name="confirm_password" class="form-control border-end-0" placeholder="Confirm your password" required id="confirm-password-field">
                <span class="input-group-text bg-white border-start-0 toggle-password" onclick="togglePassword('confirm-password-field')">👁</span>
              </div>
            </div>

            <button type="submit" class="btn btn-signup-submit w-100 py-2 fw-bold">
              Create Account →
            </button>
          </form>

        </div>
      </div>
    </div>
  </div>
</main>

<!-- Include external JS -->
<script src="<?= base_url('js/auth.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('css/auth.css') ?>">