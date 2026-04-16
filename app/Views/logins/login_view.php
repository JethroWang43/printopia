<main class="login-page">
  <section class="login-shell" aria-label="Login page">
    <aside class="pitch-panel">
      <p class="pill-label">Printopia Studio</p>
      <h1 class="brand-title">Bring your next custom design to life.</h1>
      <p class="brand-subtitle">From concept to crafted output, your ideas stay in expert hands.</p>

      <ul class="feature-list" aria-label="Printopia highlights">
        <li><span class="check-icon" aria-hidden="true">&#10003;</span> Custom 3D model design</li>
        <li><span class="check-icon" aria-hidden="true">&#10003;</span> One-on-one design consultation</li>
        <li><span class="check-icon" aria-hidden="true">&#10003;</span> Production quality assurance</li>
        <li><span class="check-icon" aria-hidden="true">&#10003;</span> Secure and trusted ordering</li>
      </ul>
    </aside>

    <section class="form-panel">
      <div class="form-card">
        <header class="form-header">
          <p class="eyebrow">Welcome back</p>
          <h2>Sign in to continue</h2>
          <p class="support-text">Access your dashboard, orders, and design history.</p>
        </header>

        <form id="loginForm" class="login-form">
          <div class="field-group">
            <label for="email" class="custom-label">Email address</label>
            <div class="input-shell">
              <span class="input-icon" aria-hidden="true">@</span>
              <input id="email" type="email" name="email" class="form-control" placeholder="name@example.com" required>
            </div>
          </div>

          <div class="field-group">
            <label for="password-field" class="custom-label">Password</label>
            <div class="input-shell password-group">
              <span class="input-icon" aria-hidden="true">*</span>
              <input type="password" name="password" class="form-control" placeholder="Enter your password" required id="password-field">
              <button
                type="button"
                class="toggle-password"
                data-toggle-password="password-field"
                onclick="togglePassword('password-field')"
                aria-label="Show password"
              >Show</button>
            </div>
          </div>

          <div class="meta-row">
            <label class="remember-row" for="rememberMe">
              <input type="checkbox" id="rememberMe">
              <span>Remember me</span>
            </label>
            <a href="#" class="forgot-link">Forgot password?</a>
          </div>

          <button type="submit" class="btn-signin">Sign in to Printopia</button>
        </form>

        <p class="signup-text">
          Don't have an account?
          <a href="<?= base_url('/signup'); ?>">Create one now</a>
        </p>
      </div>
    </section>
  </section>
</main>

<script src="<?= base_url('/public/js/auth.js') ?>"></script>