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

    // Validate ID format (e.g., xxxx-xxxx)
    if (!preg_match('/^\d{4}-\d{4}$/', $idNo)) {
        $error = "Please enter a valid ID number (xxxx-xxxx).";
    } else {
        // Retrieve security questions from the database
        $stmt = $pdo->prepare("SELECT a1_question, a2_question, a3_question FROM users WHERE id_number = ? LIMIT 1");
        $stmt->execute([$idNo]);
        $questions = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$questions) {
            $error = "ID Number not found.";
        }
    }
}

// Step 2 — Verify Security Answers
if (isset($_POST['verifyAnswers'])) {
    $idNo = trim($_POST['resetIdNo']);
    $q1 = trim($_POST['a1_answer']);
    $q2 = trim($_POST['a2_answer']);
    $q3 = trim($_POST['a3_answer']);

    // Retrieve the user's stored security question answers (hashed)
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id_number = ? LIMIT 1");
    $stmt->execute([$idNo]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        $error = "ID Number not found.";
    } else {
        // --- Verify the plain-text user answers against the stored hashes ---
        $matchQ1 = password_verify($q1, $row['a1_answer']);
        $matchQ2 = password_verify($q2, $row['a2_answer']);
        $matchQ3 = password_verify($q3, $row['a3_answer']);

        if ($matchQ1 && $matchQ2 && $matchQ3) {
            $_SESSION['verified_idNo'] = $idNo;
            $success = "Security answers verified successfully. You can now reset your password.";
        } else {
            $error = "Security answers do not match.";
        }
    }
}

// Step 3 — Reset Password
if (isset($_POST['resetPassword']) && isset($_SESSION['verified_idNo'])) {
    $idNo = $_SESSION['verified_idNo'];
    $new = $_POST['newPassword'];
    $confirm = $_POST['confirmNewPassword'];

    if ($new !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($new) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $passHash = password_hash($new, PASSWORD_DEFAULT);

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
    <title>Brewstack Coffee - Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/reset_password.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="page-wrapper">
        <!-- header -->
        <?php include '../includes/header.php' ?>

        <!-- MAIN CONTAINER -->
        <div class="container-wrapper">
            <form id="password-reset-form" class="container p-4 mt-3 d-flex flex-column rounded-2" style="max-width: 800px; height: 80vh;" novalidate>
                <h3 class="text-warning text-shadow text-center">🔐 Reset Password</h3>
                <p class="text-center text-light">Enter your ID Number to answer Authentication questions and reset your password.</p>

                <div class="row flex-grow-1">
                    <!-- Right Column: Email + Password Fields -->
                    <div class="col-md-6 position-relative">
                        <!-- ID Number Input -->
                        <div class="mb-3 position-relative">
                            <label for="user_id" class="form-label">ID Number</label>
                            <input type="text" class="form-control" id="user_id" name="resetIdNo" placeholder="XXXX-XXXX" value="<?= $_POST['resetIdNo'] ?? '' ?>" maxlength="9" required>
                            <div class="invalid-feedback">⚠️ User ID must be in the format xxxx-xxxx.</div>
                        </div>

                        <!-- New Password -->
                        <div class="mb-3 position-relative">
                            <label for="newPassword" class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="New password" required>
                                <button type="button" class="toggle-password-btn position-absolute translate-middle-y bg-transparent border-0 " data-type="password" aria-label="Toggle password visibility">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">Password is required.</div>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-3 position-relative">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmPassword" name="confirmNewPassword" placeholder="Confirm password" required>
                                <button type="button" class="toggle-password-btn position-absolute translate-middle-y bg-transparent border-0 " data-type="password" aria-label="Toggle password visibility">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">Password confirmation does not match.</div>
                        </div>
                    </div>

                    <!-- Left Column: Authentication Questions Only -->
                    <div class="col-md-6">
                        <label class="form-label">Authentication Questions</label>
                        <!-- Question 1 -->
                        <div class="mb-2 position-relative">
                            <select class="form-select" id="authQuestion1Select" name="auth_question_1" required>
                                <option value="" selected>Select question 1</option>
                                <option value="best_friend">Who is your best friend in elementary?</option>
                                <option value="childhood_home">What street did you grow up on?</option>
                                <option value="favorite_color">What is your favorite color?</option>
                                <option value="first_teacher">Who was your first teacher?</option>
                                <option value="favorite_food">What is your favorite food?</option>
                                <option value="dream_job">What is your dream job?</option>
                            </select>
                            <div class="input-group mt-2">
                                <input type="password" class="form-control authAnswer" name="auth_answer_1" placeholder="Answer to question 1" required>
                                <button type="button" class="toggle-password-btn position-absolute translate-middle-y bg-transparent border-0" data-type="password" aria-label="Toggle password visibility">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Question 2 -->
                        <div class="mb-2 position-relative">
                            <select class="form-select" id="authQuestion2Select" name="auth_question_2" required>
                                <option value="" selected>Select question 2</option>
                                <option value="favorite_pet">What is the name of your favorite pet?</option>
                                <option value="birth_city">In what city were you born?</option>
                                <option value="mother_maiden">What is your mother’s maiden name?</option>
                                <option value="first_phone">What was your first mobile phone brand?</option>
                                <option value="favorite_movie">What is your favorite movie?</option>
                                <option value="first_car">What was the model of your first car?</option>
                            </select>
                            <div class="input-group mt-2">
                                <input type="password" class="form-control authAnswer" name="auth_answer_2" placeholder="Answer to question 2" required>
                                <button type="button" class="toggle-password-btn position-absolute translate-middle-y  bg-transparent border-0" data-type="password" aria-label="Toggle password visibility">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Question 3 -->
                        <div class="position-relative">
                            <select class="form-select" id="authQuestion3Select" name="auth_question_3" required>
                                <option value="" selected>Select question 3</option>
                                <option value="favorite_teacher">Who is your favorite teacher in high school?</option>
                                <option value="first_vacation">Where did you spend your first vacation?</option>
                                <option value="childhood_nickname">What was your childhood nickname?</option>
                                <option value="favorite_subject">What was your favorite subject in school?</option>
                                <option value="favorite_hobby">What is your favorite hobby?</option>
                                <option value="favorite_city">What city would you most like to visit?</option>
                            </select>
                            <div class="input-group mt-2">
                                <input type="password" class="form-control authAnswer" name="auth_answer_3" placeholder="Answer to question 3" required>
                                <button type="button" class="toggle-password-btn position-absolute translate-middle-y  bg-transparent border-0" data-type="password" aria-label="Toggle password visibility">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit button always at the bottom -->
                <div class="mt-auto">
                    <button type="submit" class="btn btn-success w-100" name="verifyAnswers" disabled>Authenticate Questions</button>
                </div>
            </form>
        </div>

        <!-- FOOTER -->
        <footer>
            <p>&copy; 2025 Brewstack Coffee — All rights reserved.</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

    <script>
        // Enable Submit Button when all answers are filled in
        document.getElementById('password-reset-form').addEventListener('input', function() {
            let isValid = true;
            let answersFilled = true;

            // Check if all answers are filled in
            document.querySelectorAll('.authAnswer').forEach(function(input) {
                if (input.value.trim() === '') {
                    answersFilled = false;
                }
            });

            // Enable/Disable the submit button
            document.querySelector('button[type="submit"]').disabled = !(answersFilled);
        });

        const userId = document.getElementById('user_id');

        document.getElementById('user_id').addEventListener('input', async () => {
            
            const result = await checkUserId(userId.value);
        });

            
        const urls = {
            checkUserId: "php/check_user_id.php",

        }

        async function checkUserId(user_id) {
            try {
                const response = await fetch(urls.checkUserId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({user_id: user_id})
                });
                const data = await response.json();
                console.table(data);
                return data;
            } catch (error) {
                console.error('Error:', error);
            }
        }






        
    </script>
</body>
</html>
