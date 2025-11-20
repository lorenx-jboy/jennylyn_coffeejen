<?php 
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once "../config/db.php";

$questions = null;
$error = "";
$success = "";
$idNo = "";

// Step 1 — Verify ID Number
if (isset($_POST['verifyId'])) {
    $idNo = trim($_POST['resetIdNo']);

    // Retrieve security questions from the database
    $stmt = $pdo->prepare("SELECT a1_question, a2_question, a3_question FROM users WHERE id_number = ? LIMIT 1");
    $stmt->execute([$idNo]);
    $questions = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$questions) {
        $error = "ID Number not found.";
    }
}


// Step 2 — Verify Security Answers
if (isset($_POST['verifyAnswers'])) {
    $idNo    = trim($_POST['resetIdNo']);
    $q1      = trim($_POST['a1_answer']);
    $q2      = trim($_POST['a2_answer']);
    $q3      = trim($_POST['a3_answer']);


    // Retrieve the user's stored security question answers (hashed)
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id_number = ? LIMIT 1");
    $stmt->execute(['2025-0001']);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        $error = "ID Number not found.";
    } else {

        // --- Verify the plain-text user answers against the stored hashes ---
        $matchQ1 = password_verify($q1, $row['a1_answer']);
        $matchQ2 = password_verify($q2, $row['a2_answer']);
        $matchQ3 = password_verify($q3, $row['a3_answer']);

        // Check if all answers match
        if ($matchQ1 && $matchQ2 && $matchQ3) {
            // Proceed to password reset step
            $_SESSION['verified_idNo'] = $idNo;
            $success = "Security answers verified successfully. You can now reset your password.";
        } else {
            $error = "Security answers do not match.";
        }
    }
}



// Step 3 — Reset Password
if (isset($_POST['resetPassword']) && isset($_SESSION['verified_idNo'])) {
    $idNo    = $_SESSION['verified_idNo'];
    $new     = $_POST['newPassword'];
    $confirm = $_POST['confirmNewPassword'];

    if ($new !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($new) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Hash the new password securely
        $passHash = password_hash($new, PASSWORD_DEFAULT);

        // Update the password in the database
        $update = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id_number = ?");
        if ($update->execute([$passHash, $idNo])) {
            $success = "Password reset successful!";
            unset($_SESSION['verified_idNo']);  // Clear session data after success
        } else {
            $error = "Failed to update password.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 500px;
        }
        .card {
            border-radius: 10px;
        }
        .alert {
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 8px;
        }
    </style>
</head>

<body class="bg-dark text-light">

    <div class="container mt-5">
        <div class="card p-4 bg-secondary">
            <h3 class="text-center mb-3">🔐 Reset Password</h3>

            <!-- Error Message -->
            <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <!-- Success Message -->
            <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <div class="text-center mt-3">
                <a href="../login.php" class="btn btn-light w-50">Back to Login</a>
            </div>
            <?php endif; ?>

            <!-- STEP 1 FORM - Verify ID -->
            <?php if (!$questions && empty($success)): ?>
            <form method="POST">
                <label class="form-label">Enter your ID Number</label>
                <input type="text" name="resetIdNo" class="form-control mb-3" required>

                <button type="submit" name="verifyId" class="btn btn-warning w-100">
                    Verify ID
                </button>
            </form>
            <?php endif; ?>

            <!-- STEP 2 FORM - Verify Security Answers -->
            <?php if ($questions && empty($success)): ?>
            <form method="POST">
                <input type="hidden" name="resetIdNo" value="<?= htmlspecialchars($idNo) ?>">

                <p class="fw-bold text-center mt-3">Answer Your Security Questions</p>

                <div class="mb-2">
                    <label class="form-label"><?= htmlspecialchars($questions['a1_question']) ?></label>
                    <input type="text" name="a1_answer" class="form-control" required>
                </div>

                <div class="mb-2">
                    <label class="form-label"><?= htmlspecialchars($questions['a2_question']) ?></label>
                    <input type="text" name="a2_answer" class="form-control" required>
                </div>

                <div class="mb-2">
                    <label class="form-label"><?= htmlspecialchars($questions['a3_question']) ?></label>
                    <input type="text" name="a3_answer" class="form-control" required>
                </div>

                <button type="submit" name="verifyAnswers" class="btn btn-success w-100">
                    Verify Answers
                </button>
            </form>
            <?php endif; ?>

            <!-- STEP 3 FORM - Reset Password (after security answers are verified) -->
            <?php if ($success && !empty($_SESSION['verified_idNo'])): ?>
            <form method="POST">
                <input type="hidden" name="resetIdNo" value="<?= htmlspecialchars($idNo) ?>">

                <p class="fw-bold text-center mt-3">Reset Your Password</p>

                <div class="mb-2">
                    <label class="form-label">New Password</label>
                    <input type="password" name="newPassword" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirmNewPassword" class="form-control" required>
                </div>

                <button type="submit" name="resetPassword" class="btn btn-primary w-100">
                    Reset Password
                </button>
            </form>
            <?php endif; ?>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

</body>

</html>
