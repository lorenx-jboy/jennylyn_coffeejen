<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

require_once __DIR__ . '/auth.php';
// $pdo is provided by db.php which is required by auth.php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$idNo = trim($_POST['resetIdNo'] ?? '');
$q1 = trim($_POST['resetQ1'] ?? '');
$q2 = trim($_POST['resetQ2'] ?? '');
$q3 = trim($_POST['resetQ3'] ?? '');
$new = $_POST['newPassword'] ?? '';
$confirm = $_POST['confirmNewPassword'] ?? '';

if ($idNo === '' || $q1 === '' || $q2 === '' || $q3 === '' || $new === '' || $confirm === '') {
    header('Location: login.php?error=' . urlencode('Please fill all required fields'));
    exit;
}

if ($new !== $confirm) {
    header('Location: login.php?error=' . urlencode('Passwords do not match'));
    exit;
}

if (strlen($new) < 6) {
    header('Location: login.php?error=' . urlencode('Password must be at least 6 characters'));
    exit;
}

try {
    // Ensure table exists (safe)
    // ensureUsersTableExists($pdo);

    $stmt = $pdo->prepare('SELECT sec_q1, sec_q2, sec_q3 FROM users WHERE id_number = ? LIMIT 1');
    $stmt->execute([$idNo]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        header('Location: login.php?error=' . urlencode('ID Number not found'));
        exit;
    }

    $h1 = hash('sha256', strtolower($q1));
    $h2 = hash('sha256', strtolower($q2));
    $h3 = hash('sha256', strtolower($q3));

    if ($h1 !== $row['sec_q1'] || $h2 !== $row['sec_q2'] || $h3 !== $row['sec_q3']) {
        header('Location: login.php?error=' . urlencode('Security answers do not match'));
        exit;
    }

    $update = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id_number = ?');
    $ok = $update->execute([hash('sha256', $new), $idNo]);
    if ($ok) {
        header('Location: login.php?success=' . urlencode('Password reset successful'));
        exit;
    } else {
        header('Location: login.php?error=' . urlencode('Failed to update password'));
        exit;
    }
} catch (Exception $ex) {
    header('Location: login.php?error=' . urlencode('Server error: ' . $ex->getMessage()));
    exit;
}
