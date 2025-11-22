<?php
session_start();
require_once '../config/db.php';

// Init session vars
if (!isset($_SESSION['failed_attempts'])) $_SESSION['failed_attempts'] = 0;
if (!isset($_SESSION['lock_until'])) $_SESSION['lock_until'] = 0;
if (!isset($_SESSION['lock_cycle'])) $_SESSION['lock_cycle'] = 0;

// Function to detect if request is AJAX
function isAjax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
           && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

// Delay rules based on lock cycle
function getDelayByCycle($cycle) {
    if ($cycle == 1) return 15;
    if ($cycle == 2) return 30;
    return 60;
}

// Reset attempts if lock expired
if (time() >= $_SESSION['lock_until'] && $_SESSION['lock_until'] > 0) {
    $_SESSION['failed_attempts'] = 0;
    $_SESSION['lock_until'] = 0;
}

// ---------------------------------------------
// AJAX CHECK: return lock status only
// ---------------------------------------------
if (isAjax() && ($_SERVER['REQUEST_METHOD'] === 'GET')) {

    $remaining = 0;

    if (time() < $_SESSION['lock_until']) {
        $remaining = $_SESSION['lock_until'] - time();
    }

    header('Content-Type: application/json');
    echo json_encode([
        'remaining' => $remaining
    ]);
    exit;
}

// ---------------------------------------------
// NORMAL LOGIN POST REQUEST (Form submit)
// ---------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Active lockout check
    if (time() < $_SESSION['lock_until']) {
        $wait = $_SESSION['lock_until'] - time();
        $_SESSION['error'] = "Too many failed attempts. Please wait $wait seconds.";
        header("Location: login.php");
        exit;
    }

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Please enter both username and password.";
        header("Location: login.php");
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {

            // Login success
            $_SESSION['failed_attempts'] = 0;
            $_SESSION['lock_until'] = 0;
            $_SESSION['lock_cycle'] = 0;

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            unset($_SESSION['error']);
            header("Location: dashboard.php");
            exit;

        } else {
            // Login fail
            $_SESSION['failed_attempts']++;

            if ($_SESSION['failed_attempts'] >= 3) {
                $_SESSION['lock_cycle']++;
                $delay = getDelayByCycle($_SESSION['lock_cycle']);
                $_SESSION['lock_until'] = time() + $delay;
                $_SESSION['failed_attempts'] = 0;
            }

            $_SESSION['error'] = "Invalid username or password.";
            header("Location: login.php");
            exit;
        }

    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: login.php");
        exit;
    }
}
?>
