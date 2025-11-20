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
    $idNo    = $_SESSION['verified_idNo'];
    $new     = $_POST['newPassword'];
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


    <style>
    body {
        background-image: url("../image/image2.png");
        background-color: rgba(0, 0, 0, 0.25);
        background-blend-mode: darken;
        background-size: cover;
        background-position: center;
        margin: 0;
        padding: 0;
        color: #ecebea;
    }

    header,
    footer {
        background: #1a1412b5;
        color: #ecebea;
        padding: 10px 20px;
    }

    footer {
        text-align: center;
        font-size: 14px;
    }

    .page-wrapper {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .container-wrapper {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px;
    }

    .card {
        width: 100%;
        max-width: 500px;
        border-radius: 14px;
        background-color: rgba(78, 52, 46, 0.9);
        padding: 20px;
    }

    h3 {
        text-align: center;
        margin-bottom: 15px;
    }

    .form-control {
        border-radius: 8px;
        margin-bottom: 10px;
        height: 36px;
    }

    .btn {
        border-radius: 8px;
    }
    </style>
</head>

<body>

    <div class="page-wrapper">

        <!-- HEADER -->
        <header class="d-flex justify-content-between align-items-center">
            <h4 class="m-0">Brewstack Coffee</h4>

            <?php if (!empty($_SESSION['username'])): ?>
            <form action="logout.php" method="post">
                <button type="submit" class="btn btn-sm btn-secondary">Logout</button>
            </form>
            <?php else: ?>
            <a href="login.php" class="btn btn-sm btn-warning">Login</a>
            <?php endif; ?>
        </header>

        <!-- MAIN CONTAINER -->
        <div class="container-wrapper">
            <!-- <div class="card">
                <h3>🔐 Reset Password</h3>

                <!-- Error -->
                <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <!-- Success --
                <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="text-center mt-3">
                    <a href="../login.php" class="btn btn-light w-50">Back to Login</a>
                </div>
                <?php endif; ?>

                <!-- STEP 1: Verify ID --
                <?php if (!$questions && empty($success)): ?>
                <form method="POST">
                    <label class="form-label">Enter your ID Number</label>
                    <input type="text" name="resetIdNo" class="form-control" required>
                    <button type="submit" name="verifyId" class="btn btn-warning w-100 mt-2">
                        Verify ID
                    </button>
                </form>
                <?php endif; ?>

                <!-- STEP 2: Verify Security Answers --
                <?php if ($questions && empty($success)): ?>
                <form method="POST" class="mt-5">
                    <input type="hidden" name="resetIdNo" value="<?= htmlspecialchars($idNo) ?>">
                    <p class="fw-bold text-center mt-2">Answer Your Security Questions</p>

                    <!-- Question 1 --
                    <div class="mb-4">
                        <select id="a1_question" class="form-control" name="a1_question" required>
                            <option value="" disabled selected hidden>Select Question 1</option>
                            <option value="teacher">Who is your favorite teacher in high school?</option>
                            <option value="pet">What is the name of your favorite pet?</option>
                            <option value="best_friend">Who is your best friend in Elementary?</option>
                            <option value="first_car">What was the make or model of your first car?</option>
                            <option value="childhood_game">What was your favorite game as a child?</option>
                            <option value="favorite_food">What is your favorite food?</option>
                        </select>
                        <input type="password" name="a1_answer" class="form-control mt-2" required
                            placeholder="Enter your answer">
                    </div>

                    <!-- Question 2 --
                    <div class="mb-4">
                        <select id="a2_question" class="form-control" name="a2_question" required>
                            <option value="" disabled selected hidden>Select Question 2</option>
                            <option value="dream_job">What was your dream job when you were a kid?</option>
                            <option value="favorite_movie">What is your all-time favorite movie?</option>
                            <option value="travel_destination">Which country would you love to visit the most?</option>
                            <option value="hobby">What hobby do you enjoy the most?</option>
                            <option value="childhood_memory">What is your happiest childhood memory?</option>
                            <option value="superpower">If you could have any superpower, what would it be?</option>
                        </select>
                        <input type="password" name="a2_answer" class="form-control mt-2" required
                            placeholder="Enter your answer">
                    </div>

                    <!-- Question 3 --
                    <div class="mb-4">
                        <select id="a3_question" class="form-control" name="a3_question" required>
                            <option value="" disabled selected hidden>Select Question 3</option>
                            <option value="first_job">What was your first part-time or summer job?</option>
                            <option value="favorite_book">Which book has influenced you the most?</option>
                            <option value="childhood_nickname">Did you have a childhood nickname? What was it?</option>
                            <option value="proud_moment">What is a moment in your life that made you really proud?
                            </option>
                            <option value="fear">What is a fear you’ve overcome or still have?</option>
                            <option value="hidden_talent">Do you have a hidden talent? What is it?</option>
                        </select>
                        <input type="password" name="a3_answer" class="form-control mt-2" required
                            placeholder="Enter your answer">
                    </div>

                    <button type="submit" name="verifyAnswers" class="btn btn-success w-100 mt-3">
                        Verify Answers
                    </button>
                </form>

                <?php endif; ?>

                <!-- STEP 3: Reset Password --
                <?php if ($success && !empty($_SESSION['verified_idNo'])): ?>
                <form method="POST">
                    <input type="hidden" name="resetIdNo" value="<?= htmlspecialchars($idNo) ?>">

                    <label>New Password</label>
                    <input type="password" name="newPassword" class="form-control" required
                        placeholder="Enter new password">

                    <label>Confirm Password</label>
                    <input type="password" name="confirmNewPassword" class="form-control" required
                        placeholder="Confirm new password">

                    <button type="submit" name="resetPassword" class="btn btn-primary w-100 mt-2">
                        Reset Password
                    </button>
                </form>
                <?php endif; ?>

            </div> -->


            
<form id="password-reset-form" class="container p-4 mt-3 border d-flex flex-column bg-gray rounded-2"
    style="max-width: 800px; height: 80vh;" novalidate>
    <h3 class="mb-4 text-center text-primary">Password Reset</h3>
    <p class="text-center text-light">Enter your ID Number to answer Authentication questions and reset your
        password.</p>


    <div class="row flex-grow-1">

        <!-- Right Column: Email + Password Fields -->
        <div class="col-md-6 position-relative">

            <div class="mb-3 position-relative">
                <label for="" class="form-label">Username:</label>
                <span class="fw-bold" name="changepassUsername">None</span>
            </div>

            <!-- User Email -->
            <div class="mb-3 position-relative">
                <label for="user_id" class="form-label">ID Number</label>
                <input type="text" class="form-control" id="user_id" name="user_id" placeholder="XXXX-XXXX"
                    value="<?= $_POST['user_id'] ?? '' ?>" maxlength="9">
                <div class="invalid-feedback">
                    ⚠️ User ID must be in the format xxxx-xxxx.
                </div>
            </div>


            <!-- New Password -->
            <div class="mb-3 position-relative">
                <label for="newPassword" class="form-label">New Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="newPassword" name="new_password"
                        placeholder="New password">
                    <span class="input-group-text toggle-eye"><i class="bi bi-eye"></i></span>
                </div>
                <div class="invalid-feedback">
                    Password is required.
                </div>

                <!-- Password Strength Box (Floating) -->
                <div id="passwordStrength" class="border p-3 bg-light position-absolute"
                    style="top: 70px; left: 0; right: 0; opacity: 1; display: none; z-index: 10; border-radius: 5px; transition: opacity 0.5s;">
                    <div class="mb-2">
                        <div class="progress">
                            <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>
                    <ul class="mb-0" style="list-style: none; padding-left: 0;">
                        <li id="lengthReq">✔ At least 8 characters</li>
                        <li id="specialReq">✔ Contains special character</li>
                        <li id="capsReq">✔ Contains uppercase letter</li>
                        <li id="numberReq">✔ Contains number</li>
                    </ul>
                </div>
            </div>

            <!-- Confirm New Password -->
            <div class="mb-3 position-relative">
                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm password">
                    <span class="input-group-text toggle-eye"><i class="bi bi-eye"></i></span>
                </div>
                <div class="invalid-feedback">
                    Password confirmation does not match.
                </div>

                <!-- Confirm Password Match Box -->
                <div id="confirmStrength" class="border p-2 bg-light position-absolute"
                    style="top: 70px; left: 0; right: 0; opacity: 1; display: none; z-index: 10; border-radius: 5px; transition: opacity 0.5s;">
                    <span id="matchText">Passwords do not match</span>
                </div>
            </div>
        </div>

        <!-- Left Column: Authentication Questions Only -->
        <div class="col-md-6">
            <label class="form-label">Authentication Questions</label>

            <!-- Question 1 -->
            <div class="mb-2 position-relative">
                <select class="form-select" id="authQuestion1Select" name="auth_question_1">
                    <option value="" selected>Select question 1</option>
                    <option value="best_friend">Who is your best friend in elementary?</option>
                    <option value="childhood_home">What street did you grow up on?</option>
                    <option value="favorite_color">What is your favorite color?</option>
                    <option value="first_teacher">Who was your first teacher?</option>
                    <option value="favorite_food">What is your favorite food?</option>
                    <option value="dream_job">What is your dream job?</option>
                </select>
                <div class="input-group mt-2">
                    <input type="password" class="form-control authAnswer" name="auth_answer_1"
                        placeholder="Answer to question 1">
                    <span class="input-group-text toggle-eye"><i class="bi bi-eye"></i></span>
                </div>
                <div class="invalid-feedback">
                    Invalid question or answer.
                </div>
            </div>

            <!-- Question 2 -->
            <div class="mb-2 position-relative">
                <select class="form-select" id="authQuestion2Select" name="auth_question_2">
                    <option value="" selected>Select question 2</option>
                    <option value="favorite_pet">What is the name of your favorite pet?</option>
                    <option value="birth_city">In what city were you born?</option>
                    <option value="mother_maiden">What is your mother’s maiden name?</option>
                    <option value="first_phone">What was your first mobile phone brand?</option>
                    <option value="favorite_movie">What is your favorite movie?</option>
                    <option value="first_car">What was the model of your first car?</option>
                </select>
                <div class="input-group mt-2">
                    <input type="password" class="form-control authAnswer" name="auth_answer_2"
                        placeholder="Answer to question 2">
                    <span class="input-group-text toggle-eye"><i class="bi bi-eye"></i></span>
                </div>
                <div class="invalid-feedback">
                    Invalid question or answer.
                </div>
            </div>

            <!-- Question 3 -->
            <div class="position-relative">
                <select class="form-select" id="authQuestion3Select" name="auth_question_3">
                    <option value="" selected>Select question 3</option>
                    <option value="favorite_teacher">Who is your favorite teacher in high school?</option>
                    <option value="first_vacation">Where did you spend your first vacation?</option>
                    <option value="childhood_nickname">What was your childhood nickname?</option>
                    <option value="favorite_subject">What was your favorite subject in school?</option>
                    <option value="favorite_hobby">What is your favorite hobby?</option>
                    <option value="favorite_city">What city would you most like to visit?</option>
                </select>
                <div class="input-group mt-2">
                    <input type="password" class="form-control authAnswer" name="auth_answer_3"
                        placeholder="Answer to question 3">
                    <span class="input-group-text toggle-eye"><i class="bi bi-eye"></i></span>
                </div>
                <div class="invalid-feedback">
                    Invalid question or answer.
                </div>
            </div>
        </div>


    </div>

    <!-- Submit button always at the bottom -->
    <div class="mt-auto">
        <button type="submit" class="btn btn-primary w-100" disabled>Authenticate Questions</button>
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

</body>

</html>