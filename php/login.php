<?php
session_start();

// Calculate remaining lock time if the user is temporarily blocked
$failedAttempts = $_SESSION['failed_attempts'];
$remaining = 0;
if (isset($_SESSION['lock_until']) && time() < $_SESSION['lock_until']) {
    $remaining = $_SESSION['lock_until'] - time();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Brewstack Coffee - Login</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body, html { height: 100%; margin: 0; font-family: Arial, sans-serif; background: url("/image/image2.png") no-repeat center center/cover; color: #f8f3e9; }
header { background: #1a1412b5; padding: 10px 20px; }
.nav-brand { font-size: 1.5rem; font-weight: bold; color: #f8f3e9; text-decoration: none; }
.nav-links a { color: #f8f3e9; margin-left: 15px; text-decoration: none; }
.nav-links a:hover { text-decoration: underline; }
main { display: flex; justify-content: center; align-items: center; height: calc(100% - 60px - 40px); padding: 10px; }
.form-container { background: rgba(31,24,22,0.6); padding: 25px 30px; border-radius: 25px; width: 100%; max-width: 400px; box-shadow: 0 0 15px rgba(0,0,0,0.5); }
.form-container h2 { text-align: center; margin-bottom: 15px; font-weight: 600; }
.form-group { margin-bottom: 12px; }
.form-group label { display: block; margin-bottom: 4px; font-size: 14px; }
.form-group input { width: 100%; padding: 6px 10px; font-size: 13px; border-radius: 6px; border: 1px solid #ccc; height: 30px; background-color: #fff; }
.password-container { position: relative; }
.toggle-password { position: absolute; top: 50%; right: 6px; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #555; padding: 0; }
.btn-primary { width: 100%; padding: 6px; font-size: 14px; font-weight: bold; background-color: #4e342e; border: none; border-radius: 6px; color: #c09d74; }
.btn-primary:hover { background-color: #3e2721; color: #f8f3e9; }
.server-message { background: #8b0000; color: #fff; padding: 6px; border-radius: 5px; margin-bottom: 8px; font-size: 13px; text-align: center; }
.form-footer { font-size: 13px; text-align: center; margin-top: 8px; }
.form-footer a { color: #c09d74; text-decoration: none; }
.form-footer a:hover { text-decoration: underline; }
footer { text-align: center; padding: 10px 0; font-size: 12px; background: #1a1412b5; position: relative; }
button:disabled { opacity: 0.6; cursor: not-allowed; }
input:invalid { border-color: red; }
.nav-links a.disabled, .form-footer a.disabled { pointer-events: none; opacity: 0.5; }
</style>
</head>
<body>

<header class="d-flex justify-content-between align-items-center">
    <a href="home.php" class="nav-brand">Brewstack Coffee</a>
    <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="register.php" id="register-link-header" class="register-link">Register</a>
    </div>
</header>

<main>
    <div class="form-container">

        <?php if ($remaining > 0): ?>
            <div class="server-message">
                Too many failed attempts. Please wait <span id="countdown"><?= $remaining ?></span> second(s).
            </div>
        <?php endif; ?>

        <h2>Login</h2>

        <?php if (!empty($errorMsg)): ?>
            <div class="server-message"><?= htmlspecialchars($errorMsg) ?></div>
        <?php endif; ?>

        <form id="login-form" action="/php/login_submit.php" method="POST" novalidate>
            <div class="form-group">
                <label for="login-username">Username <span class="required">*</span></label>
                <input type="text" id="login-username" name="username" value="<?= htmlspecialchars($username ?? '') ?>" required pattern=".{3,}">
                <div class="invalid-feedback">Username must be at least 3 characters.</div>
            </div>

            <div class="form-group">
                <label for="login-password">Password <span class="required">*</span></label>
                <div class="password-container">
                    <input type="password" id="login-password" name="password" required pattern=".{6,}">
                    <div class="invalid-feedback">Password must be at least 6 characters.</div>
                    <button type="button" class="toggle-password" id="toggle-login-password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-primary" id="login-button">Log In</button>
        </form>

        <p class="form-footer">
            <?php if ((int)$failedAttempts >= 2 ): ?>
                <a class="" href="reset_password.php" id="register-link-footer">forgot password? click here -></a>
            <?php endif; ?>
        </p>

        <p class="form-footer">
            Don't have an account? <a class="register-link <?= $remaining > 0 ? 'disabled' : '' ?>" href="register.php" id="register-link-footer">Register</a>
        </p>
    </div>
</main>

<footer>
    <p>&copy; 2025 Brewstack Coffee System. All rights reserved.</p>
</footer>

<script>
// Password toggle
const toggleBtn = document.getElementById('toggle-login-password');
const passwordInput = document.getElementById('login-password');
toggleBtn.addEventListener('click', () => {
    const type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;
    toggleBtn.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
});

// Disable login/register if lock exists
<?php if ($remaining > 0): ?>
const loginBtn = document.getElementById('login-button');
const registerLinkHeader = document.getElementById('register-link-header');
const registerLinkFooter = document.getElementById('register-link-footer');

loginBtn.disabled = true;
registerLinkHeader.classList.add('disabled'); // Disable header register link
registerLinkFooter.classList.add('disabled'); // Disable footer register link

let remainingSeconds = <?= $remaining ?>;
const countdownEl = document.getElementById('countdown');

const interval = setInterval(() => {
    remainingSeconds--;
    countdownEl.textContent = remainingSeconds;
    if (remainingSeconds <= 0) {
        clearInterval(interval);
        loginBtn.disabled = false;
        registerLinkHeader.classList.remove('disabled'); // Re-enable header register link
        registerLinkFooter.classList.remove('disabled'); // Re-enable footer register link
    }
}, 1000);
<?php endif; ?>

// Client-side form validation
const form = document.getElementById('login-form');
form.addEventListener('submit', function(event) {
    if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
        form.querySelectorAll('input').forEach(input => {
            if (!input.checkValidity()) {
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });
    }
});
</script>

</body>
</html>
