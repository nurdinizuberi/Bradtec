<?php
require_once __DIR__ . '/../api/config.php';
if (is_logged_in()) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>Admin Login — BRADTEC</title>
<link rel="icon" type="image/svg+xml" href="../assets/favicon.svg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="login-body">
  <div class="login-card">
    <div class="login-brand">
      <img src="../assets/images/bradt.png" alt="BRADTEC Logo" class="login-logo">
      <h1>BRADTEC Admin</h1>
      <p>The Epic of Excellence — Content Management</p>
    </div>
    <form id="loginForm">
      <div class="form-group">
        <label for="password">Admin Password</label>
        <div class="password-wrapper">
          <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="Enter admin password">
          <button type="button" class="password-toggle" id="togglePassword" aria-label="Toggle password visibility">
            <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
          </button>
        </div>
      </div>
      <button type="submit" class="btn btn-block" id="loginBtn">Sign In</button>
      <!-- <p class="login-hint">Default password is set in <code>api/config.php</code> — change it before going live.</p> -->
    </form>
  </div>
  <script>
    (function () {
      var form = document.getElementById('loginForm');
      var btn = document.getElementById('loginBtn');
      var toggleBtn = document.getElementById('togglePassword');
      var pwdInput = document.getElementById('password');
      toggleBtn.addEventListener('click', function () {
        var isPassword = pwdInput.type === 'password';
        pwdInput.type = isPassword ? 'text' : 'password';
        toggleBtn.classList.toggle('visible', isPassword);
      });
      function toast(msg, type) {
        var t = document.createElement('div');
        t.className = 'toast' + (type === 'error' ? ' error' : '');
        t.textContent = msg;
        t.style.cssText = 'position:fixed;bottom:24px;right:24px;background:#fff;padding:14px 18px;border-radius:12px;box-shadow:0 24px 60px rgba(17,50,139,.16);border-left:4px solid ' + (type === 'error' ? '#dc2626' : '#08ac3d') + ';font-weight:600;z-index:999;max-width:340px';
        document.body.appendChild(t);
        setTimeout(function () { t.remove(); }, 4000);
      }
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        btn.disabled = true;
        btn.textContent = 'Signing in…';
        fetch('../api/login.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ password: document.getElementById('password').value })
        })
          .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
          .then(function (res) {
            if (res.ok && res.j.ok) { window.location.href = 'index.php'; }
            else { toast((res.j && res.j.error) || 'Login failed', 'error'); btn.disabled = false; btn.textContent = 'Sign In'; }
          })
          .catch(function () { toast('Network error', 'error'); btn.disabled = false; btn.textContent = 'Sign In'; });
      });
    })();
  </script>
</body>
</html>
