<?php
session_start();
require_once '../config/db.php'; // PDO connection

$errorMsg = '';
$username = '';

// Initialize session variables
if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0;
}
if (!isset($_SESSION['lock_until'])) {
    $_SESSION['lock_until'] = 0;
}

// Calculate delay based on attempts
function getDelay($attempts) {
    if ($attempts >= 3) {
        return 60; // Lock for 60 seconds after 3 failed attempts
    }
    return min($attempts * 15, 60); // 15s per attempt, max 60s
}

// Check if user is currently locked out
$now = time();
if ($now < $_SESSION['lock_until']) {
    $wait = $_SESSION['lock_until'] - $now;
    $errorMsg = "Too many failed attempts. Please wait $wait second(s) before trying again.";
    $_SESSION['error'] = $errorMsg;
    header('Location: login.php');
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $errorMsg = 'Please enter both username and password.';
        $_SESSION['error'] = $errorMsg;
        header('Location: login.php');
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password_hash'])) {
                // Successful login: reset attempts and lock
                $_SESSION['failed_attempts'] = 0;
                $_SESSION['lock_until'] = 0;

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // clear error message after success login
                unset($_SESSION['error']);

                header('Location: dashboard.php');
                exit;

            } else {
                // Failed login: increment attempts and set lock timer
                $_SESSION['failed_attempts']++;
                $delay = getDelay($_SESSION['failed_attempts']);
                $_SESSION['lock_until'] = time() + $delay;

                $errorMsg = 'Invalid username or password.';
                $_SESSION['error'] = $errorMsg;
                header('Location: login.php');
                exit;
            }

        } catch (PDOException $e) {
            $errorMsg = "Database error: " . $e->getMessage();
        }
    }

    
}
?>