<?php
session_start();
require_once '../config/db.php'; // PDO connection

$errorMsg = '';
$username = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $errorMsg = 'Please enter both username and password.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password_hash'])) {
                // Successful login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: dashboard.php');
                exit;
            } else {
                $errorMsg = 'Invalid username or password.';
            }
        } catch (PDOException $e) {
            $errorMsg = "Database error: " . $e->getMessage();
        }
    }
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
/* GENERAL */
body, html {
    height: 100%;
    margin: 0;
    font-family: Arial, sans-serif;
    background: url(../image/pngtree-coffee-shop-with-many-wooden-tables-and-chairs-picture-image_2611848.jpg.jpg) no-repeat center center/cover;
    color: #f8f3e9;
}
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
</style>
</head>
<body>

<header class="d-flex justify-content-between align-items-center">
    <a href="home.php" class="nav-brand">Brewstack Coffee</a>
    <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="register.php">Register</a>
    </div>
</header>

<main>
    <div class="form-container">
        <h2>Login</h2>
        <?php if (!empty($errorMsg)): ?>
            <div class="server-message"><?php echo htmlspecialchars($errorMsg); ?></div>
        <?php endif; ?>
        <form id="login-form" action="login.php" method="POST" novalidate>
            <div class="form-group">
                <label for="login-username">Username <span class="required">*</span></label>
                <input type="text" id="login-username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="login-password">Password <span class="required">*</span></label>
                <div class="password-container">
                    <input type="password" id="login-password" name="password">
                    <button type="button" class="toggle-password" id="toggle-login-password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-primary">Log In</button>
        </form>

        <p class="form-footer">
            <a href="#" id="forgot-password-link">Forgot Password?</a>
        </p>

        <p class="form-footer">
            Don't have an account? <a href="register.php">Register</a>
        </p>
    </div>
</main>

<footer>
    <p>&copy; 2025 Brewstack Coffee System. All rights reserved.</p>
</footer>

<script>
    // Toggle password visibility
    const toggleBtn = document.getElementById('toggle-login-password');
    const passwordInput = document.getElementById('login-password');
    toggleBtn.addEventListener('click', () => {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        toggleBtn.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
</script>

</body>
</html>
