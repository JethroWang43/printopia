<main class="signup-page">
  <section class="signup-shell" aria-label="Sign up page">
    <aside class="signup-pitch-panel">
      <p class="signup-pill-label">Printopia Creator Space</p>
      <h1 class="signup-brand-title">Create your account and start building custom designs.</h1>
      <p class="signup-brand-subtitle">Join a workspace focused on speed, precision, and premium print quality.</p>

      <ul class="signup-feature-list" aria-label="Why create an account">
        <li><span class="signup-check-icon" aria-hidden="true">&#10003;</span> Save and track design requests</li>
        <li><span class="signup-check-icon" aria-hidden="true">&#10003;</span> Talk directly with design specialists</li>
        <li><span class="signup-check-icon" aria-hidden="true">&#10003;</span> Review proofs before production</li>
        <li><span class="signup-check-icon" aria-hidden="true">&#10003;</span> Receive real-time order updates</li>
      </ul>
    </aside>

    <section class="signup-form-panel">
      <div class="signup-form-card">
        <header class="signup-form-header">
          <p class="signup-eyebrow">Get started</p>
          <h2>Create your account</h2>
          <p class="signup-support-text">Fill in your details to unlock your Printopia dashboard.</p>
        </header>

        <form id="signupForm" class="signup-form">
          <div class="signup-field-grid">
            <div class="signup-field-group">
              <label for="first_name" class="signup-label">First name</label>
              <div class="signup-input-shell">
                <span class="signup-input-icon" aria-hidden="true">FN</span>
                <input id="first_name" type="text" name="first_name" class="signup-input" placeholder="Enter first name" required>
              </div>
            </div>

            <div class="signup-field-group">
              <label for="middle_name" class="signup-label">Middle name</label>
              <div class="signup-input-shell">
                <span class="signup-input-icon" aria-hidden="true">MN</span>
                <input id="middle_name" type="text" name="middle_name" class="signup-input" placeholder="Optional">
              </div>
            </div>

            <div class="signup-field-group signup-full-width">
              <label for="last_name" class="signup-label">Last name</label>
              <div class="signup-input-shell">
                <span class="signup-input-icon" aria-hidden="true">LN</span>
                <input id="last_name" type="text" name="last_name" class="signup-input" placeholder="Enter last name" required>
              </div>
            </div>

            <div class="signup-field-group signup-full-width">
              <label for="email" class="signup-label">Email address</label>
              <div class="signup-input-shell">
                <span class="signup-input-icon" aria-hidden="true">@</span>
                <input id="email" type="email" name="email" class="signup-input" placeholder="name@example.com" required>
              </div>
            </div>

            <div class="signup-field-group signup-full-width">
              <label for="phone_number" class="signup-label">Phone number</label>
              <div class="signup-input-shell">
                <span class="signup-input-icon" aria-hidden="true">+63</span>
                <input id="phone_number" type="tel" name="phone_number" class="signup-input" placeholder="Enter your phone number" required>
              </div>
            </div>

            <div class="signup-field-group signup-full-width">
              <label for="password-field" class="signup-label">Password</label>
              <div class="signup-input-shell">
                <span class="signup-input-icon" aria-hidden="true">PW</span>
                <input type="password" name="password" class="signup-input" placeholder="Create a password" required id="password-field">
                <button
                  type="button"
                  class="signup-toggle-password"
                  data-toggle-password="password-field"
                  onclick="togglePassword('password-field')"
                  aria-label="Show password"
                >Show</button>
              </div>
            </div>

            <div class="signup-field-group signup-full-width">
              <label for="confirm-password-field" class="signup-label">Confirm password</label>
              <div class="signup-input-shell">
                <span class="signup-input-icon" aria-hidden="true">CP</span>
                <input type="password" name="confirm_password" class="signup-input" placeholder="Confirm your password" required id="confirm-password-field">
                <button
                  type="button"
                  class="signup-toggle-password"
                  data-toggle-password="confirm-password-field"
                  onclick="togglePassword('confirm-password-field')"
                  aria-label="Show password"
                >Show</button>
              </div>
            </div>
          </div>

          <button type="submit" class="signup-submit-btn">Create account</button>
        </form>

        <p class="signup-login-text">
          Already have an account?
          <a href="<?= base_url('/login'); ?>">Sign in here</a>
        </p>
      </div>
    </section>
  </section>
</main>

<script src="<?= base_url('/public/js/auth.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('/public/css/signup.css') ?>">