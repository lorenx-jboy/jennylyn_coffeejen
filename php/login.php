<?php
session_start();


// login check -- redirect if already logged in
require_once './functions.php';

logged_in();

// $_SESSION['failed_attempts'] = 0; $_SESSION['lock_until'] = 0; // debug purposes


// Calculate remaining lock time if the user is temporarily blocked
$failedAttempts = $_SESSION['failed_attempts'] ?? 0;
$remaining = 0;
if (isset($_SESSION['lock_until']) && time() < $_SESSION['lock_until']) {
    $remaining = $_SESSION['lock_until'] - time();
}

$errorMsg = $_SESSION['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brewstack Coffee - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/login.css">

    <link rel="stylesheet" href="../css/style.css">

</head>



<body>
    <!-- header -->
    <?php include '../includes/header.php' ?>
    <main>
        <div class="form-container">

            <?php if ($remaining > 0): ?>
            <div class="server-message">
                Too many failed attempts. Please wait <span id="countdown"><?= $remaining ?></span> second(s).
            </div>
            <?php endif; ?>

            <h2>Login</h2>

            <form id="login-form" action="/php/login_submit.php" method="POST" novalidate>
                <div class="form-group ">
                    <label for="login-username">Username <span class="required">*</span></label>
                    <input type="text" id="login-username" name="username" class="<?= $errorMsg ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($username ?? '') ?>" required pattern=".{3,}">

                    <small
                        class="error-message invalid-feedback"><?= htmlspecialchars($errorMsg) ? $errorMsg : 'Invalid username or password.' ?>
                    </small>
                </div>

                <div class="form-group">
                    <label for="login-password">Password <span class="required">*</span></label>
                    <div class="password-container is-invalid">
                        <input type="password" id="login-password" name="password" required
                            class="<?= $errorMsg ? 'is-invalid' : '' ?>" pattern=".{6,}">
                        <button type="button"
                            class="toggle-password-btn position-absolute translate-middle-y bg-transparent border-0 "
                            data-type="password" aria-label="Toggle password visibility">
                            <i class="bi bi-eye"></i>
                        </button>

                        <small
                            class="error-message invalid-feedback"><?= htmlspecialchars($errorMsg) ? $errorMsg : 'Invalid username or password.' ?>
                        </small>
                    </div>
                </div>

                <button type="submit" class="btn-primary" id="login-button">Log In</button>
            </form>

            <p class="form-footer">
                <?php if ((int)$failedAttempts >= 2 ): ?>
                <a class="" href="reset_password.php">Forgot password? click here -></a>
                <?php endif; ?>
            </p>

            <p class="form-footer">
                Don't have an account? <a class="register-link <?= $remaining > 0 ? 'disabled' : '' ?>"
                    href="register.php">Register</a>
            </p>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Brewstack Coffee System. All rights reserved.</p>
    </footer>

    <script>
    // Password toggle
    const toggleBtn = document.querySelector('.toggle-password-btn');
    const passwordInput = document.getElementById('login-password');
    toggleBtn.addEventListener('click', () => {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        toggleBtn.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' :
            '<i class="fas fa-eye-slash"></i>';
    });

    // Disable login/register if lock exists
    <?php if ($remaining > 0): ?>
    const loginBtn = document.getElementById('login-button');
    const registerLinkHeader = document.getElementById('register-link-header');
    // const registerLinkFooter = document.getElementById('register-link-footer');
    const serverMessage = document.querySelector('.server-message');

    loginBtn.disabled = true;
    registerLinkHeader.classList.add('disabled'); // Disable header register link
    // registerLinkFooter.classList.add('disabled'); // Disable footer register link

    let remainingSeconds = <?= $remaining ?>;
    const countdownEl = document.getElementById('countdown');
    serverMessage.style.display = 'block';

    const interval = setInterval(() => {
        remainingSeconds--;
        countdownEl.textContent = remainingSeconds;
        if (remainingSeconds <= 0) {
            clearInterval(interval);
            loginBtn.disabled = false;
            registerLinkHeader.classList.remove('disabled'); // Re-enable header register link
            // registerLinkFooter.classList.remove('disabled'); // Re-enable footer register link
            serverMessage.style.display = 'none';
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