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

    $stmt = $pdo->prepare("SELECT a1_question, a2_question, a3_question FROM users WHERE id_number = ? LIMIT 1");
    $stmt->execute([$idNo]);
    $questions = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$questions) {
        $error = "ID Number not found.";
    }
}
// Step 2 — Reset Password
if (isset($_POST['resetPassword'])) {
    $idNo    = trim($_POST['resetIdNo']);
    $q1      = trim($_POST['resetQ1']);
    $q2      = trim($_POST['resetQ2']);
    $q3      = trim($_POST['resetQ3']);
    $new     = $_POST['newPassword'];
    $confirm = $_POST['confirmNewPassword'];

    if ($new !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($new) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {

        $stmt = $pdo->prepare("SELECT a1_answer, a2_answer, a3_answer FROM users WHERE id_number = ? LIMIT 1");
        $stmt->execute([$idNo]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            $error = "ID Number not found.";
        } else {

            // --- Verify using password_verify() ---
            $matchQ1 = password_verify(strtolower($q1), $row['a1_answer']);
            $matchQ2 = password_verify(strtolower($q2), $row['a2_answer']);
            $matchQ3 = password_verify(strtolower($q3), $row['a3_answer']);

            if ($matchQ1 && $matchQ2 && $matchQ3) {

                $passHash = password_hash($new, PASSWORD_DEFAULT);

                $update = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id_number = ?");
                $ok = $update->execute([$passHash, $idNo]);

                if ($ok) {
                    $success = "Password reset successful!";
                } else {
                    $error = "Failed to update password.";
                }

            } else {
                $error = "Security answers do not match.";
            }
        }
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-dark text-light">

    <div class="container mt-5">
        <div class="card p-4 bg-secondary">
            <h3 class="text-center mb-3">🔐 Reset Password</h3>

            <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <div class="text-center mt-3">
                <a href="../login.php" class="btn btn-light">Back to Login</a>
            </div>
            <?php endif; ?>

            <!-- STEP 1 FORM -->
            <?php if (!$questions && empty($success)): ?>
            <form method="POST">
                <label class="form-label">Enter your ID Number</label>
                <input type="text" name="resetIdNo" class="form-control mb-3" required>

                <button type="submit" name="verifyId" class="btn btn-warning w-100">
                    Verify ID
                </button>
            </form>
            <?php endif; ?>

            <!-- STEP 2 FORM (SHOW AFTER VERIFY) -->
            <?php if ($questions && empty($success)): ?>
            <form method="POST">
                <input type="hidden" name="resetIdNo" value="<?= htmlspecialchars($idNo) ?>">

                <p class="fw-bold text-center mt-3">Answer Your Security Questions</p>

                <div class="mb-2">
                    <label><?= htmlspecialchars($questions['a1_question']) ?></label>
                    <input type="text" name="resetQ1" class="form-control" required>
                </div>

                <div class="mb-2">
                    <label><?= htmlspecialchars($questions['a2_question']) ?></label>
                    <input type="text" name="resetQ2" class="form-control" required>
                </div>

                <div class="mb-2">
                    <label><?= htmlspecialchars($questions['a3_question']) ?></label>
                    <input type="text" name="resetQ3" class="form-control" required>
                </div>

                <div class="mb-2">
                    <label>New Password</label>
                    <input type="password" name="newPassword" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="confirmNewPassword" class="form-control" required>
                </div>

                <button type="submit" name="resetPassword" class="btn btn-primary w-100">
                    Reset Password
                </button>
            </form>
            <?php endif; ?>

        </div>
    </div>

</body>

</html>