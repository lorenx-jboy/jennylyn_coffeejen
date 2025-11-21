<?php 
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// login check -- redirect if already logged in
require_once './functions.php';
logged_in();

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
    <!-- header -->
    <?php include '../includes/header.php' ?>
    
    <main>
        <!-- MAIN CONTAINER -->
    <form id="password-reset-form" class="container p-4 mt-3 d-flex flex-column rounded-2" style="max-width: 800px; height: 80vh;"
    data-submit="verifyUserID">
        <h3 class="text-warning text-shadow text-center">🔐 Reset Password</h3>
        <p class="text-center text-light">Enter your ID Number to answer Authentication questions and reset your password.</p>
        <br>

        <div class="row flex-grow-1">
            <!-- Right Column: Email + Password Fields -->
            <div class="col-md-6 position-relative">

                <div class="mb-3 position-relative">
                    <label for="" class="form-label">Username:</label>
                    <span class="fw-bold" name="resetPass">None</span>
                </div>

                <!-- ID Number Input -->
                <div class="mb-3 position-relative">
                    <label for="user_id" class="form-label">ID Number</label>
                    <input type="text" class="form-control" id="user_id" name="user_id" placeholder="XXXX-XXXX" maxlength="9" required>
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
                    <select class="form-select form-control authQuestion" id="authQuestion1Select" name="" required>
                        <option value="" disabled selected hidden>Select Question 1</option>
                        <option value="teacher">Who is your favorite teacher in high school?</option>
                        <option value="pet">What is the name of your favorite pet?</option>
                        <option value="best_friend">Who is your best friend in Elementary?</option>
                        <option value="first_car">What was the make or model of your first car?</option>
                        <option value="childhood_game">What was your favorite game as a child?</option>
                        <option value="favorite_food">What is your favorite food?</option>
                    </select>
                    <div class="invalid-feedback text-warning">Question is required.</div>
                    <div class="input-group mt-2">
                        <input type="password" class="form-control authAnswer" name="auth_answer_1" placeholder="Answer to question 1" required>
                        <button type="button" class="toggle-password-btn position-absolute translate-middle-y bg-transparent border-0" data-type="password" aria-label="Toggle password visibility">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="invalid-feedback text-warning">Answer is required.</div>
                </div>

                <!-- Question 2 -->
                <div class="mb-2 position-relative">
                    <select class="form-select form-control authQuestion" id="authQuestion2Select" name="auth_question_2" required>
                        <option value="" disabled selected hidden>Select Question 2</option>
                        <option value="dream_job">What was your dream job when you were a kid?</option>
                        <option value="favorite_movie">What is your all-time favorite movie?</option>
                        <option value="travel_destination">Which country would you love to visit the most?</option>
                        <option value="hobby">What hobby do you enjoy the most?</option>
                        <option value="childhood_memory">What is your happiest childhood memory?</option>
                        <option value="superpower">If you could have any superpower, what would it be?</option>
                    </select>
                    <div class="invalid-feedback text-warning">Question is required.</div>
                    <div class="input-group mt-2">
                        <input type="password" class="form-control authAnswer" name="auth_answer_2" placeholder="Answer to question 2" required>
                        <button type="button" class="toggle-password-btn position-absolute translate-middle-y  bg-transparent border-0" data-type="password" aria-label="Toggle password visibility">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="invalid-feedback text-warning">Answer is required.</div>
                </div>

                <!-- Question 3 -->
                <div class="position-relative">
                    <select class="form-select form-control authQuestion" id="authQuestion3Select" name="auth_question_3" required>
                        <option value="" disabled selected hidden>Select Question 3</option>
                        <option value="first_job">What was your first part-time or summer job?</option>
                        <option value="favorite_book">Which book has influenced you the most?</option>
                        <option value="childhood_nickname">Did you have a childhood nickname? What was it?</option>
                        <option value="proud_moment">What is a moment in your life that made you really proud?</option>
                        <option value="fear">What is a fear you’ve overcome or still have?</option>
                        <option value="hidden_talent">Do you have a hidden talent? What is it?</option>
                    </select>
                    <div class="invalid-feedback text-warning">Question is required.</div>
                    <div class="input-group mt-2">
                        <input type="password" class="form-control authAnswer" name="auth_answer_3" placeholder="Answer to question 3" required>
                        <button type="button" class="toggle-password-btn position-absolute translate-middle-y  bg-transparent border-0" data-type="password" aria-label="Toggle password visibility">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="invalid-feedback text-warning">Answer is required.</div>
                </div>
            </div>
        </div>

        <!-- Submit button always at the bottom -->
        <div class="mt-auto">
            <button type="submit" class="btn btn-success w-100" name="verifyAnswers">Authenticate Questions</button>
        </div>
    </form>
</main>

        <!-- FOOTER -->
<footer>
    <p>&copy; 2025 Brewstack Coffee — All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>



<script>
    /* ==========================
   PASSWORD RESET MODULE
   ========================== */

const urls = {
    checkUserId: "/api/check_user_id.php",
    authenticateQuestions: "/api/authenticate_questions.php",
    resetPassword: "/api/reset_password.php",
};

const RESET_STORAGE = "passwordResetState";
const FORM_STORAGE = "passwordResetForm";
const form = document.getElementById("password-reset-form");
const userIdInput = document.getElementById("user_id");
const submitBtn = form.querySelector('button[type="submit"]');
const usernameDisplay = document.querySelector("span[name='resetPass']");

form.setAttribute("novalidate", true);
submitBtn.disabled = true;

/* ─────────────────────────────
   STATE MANAGEMENT
────────────────────────────── */

function getState() {
    return JSON.parse(localStorage.getItem(RESET_STORAGE)) || {
        validId: false,
        validAuth: false,
        complete: false,
        username: ""
    };
}

function setState(patch) {
    const updated = { ...getState(), ...patch };
    localStorage.setItem(RESET_STORAGE, JSON.stringify(updated));
    updateUI();
}

function clearState() {
    localStorage.removeItem(RESET_STORAGE);
}


/* ─────────────────────────────
   API UTIL
────────────────────────────── */

async function post(url, payload) {
    try {
        const response = await fetch(url, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload),
        });
        return await response.json();
    } catch (err) {
        console.error("API ERROR:", err);
        return { success: false, message: "Server error" };
    }
}


/* ─────────────────────────────
   API CALLS
────────────────────────────── */

async function apiCheckUserId() {
    const result = await post(urls.checkUserId, { user_id: userIdInput.value });
    if (result.success) {
        setState({ validId: true, username: result.username });
    } else {
        setState({ validId: false });
    }

    return result;
}

async function apiAuthenticate(form) {
    const data = Object.fromEntries(new FormData(form));
    const result = await post(urls.authenticateQuestions, data);
    console.error("AUTH RESULT",result);
    if (result.success) setState({ validAuth: true });

    return result;
}

async function apiResetPassword(form) {
    const data = Object.fromEntries(new FormData(form));
    const result = await post(urls.resetPassword, data);

    if (result.success) {
        clearState();
        clearFormData();
        alert("Password successfully reset!");
        location.reload();
    }

    return result;
}


/* ─────────────────────────────
   FORM DATA SAVE/RESTORE
────────────────────────────── */

function saveForm() {
    const data = Object.fromEntries(new FormData(form));
    localStorage.setItem(FORM_STORAGE, JSON.stringify(data));
}

function loadForm() {
    const saved = localStorage.getItem(FORM_STORAGE);
    if (!saved) return;

    const data = JSON.parse(saved);
    Object.entries(data).forEach(([key, value]) => {
        const field = form.querySelector(`[name="${key}"]`);
        if (field) field.value = value;
    });
}

function clearFormData() {
    localStorage.removeItem(FORM_STORAGE);
}


/* ─────────────────────────────
   UI FLOW LOGIC
────────────────────────────── */

function updateUI() {
    const state = getState();

    if (state.validId && !state.validAuth) {
        form.dataset.submit = "verifyAnswers";
    } else if (state.validId && state.validAuth) {
        form.dataset.submit = "resetPassword";
    } else {
        form.dataset.submit = "verifyUserID";
    }

    // Display username if found
    if (state.username) usernameDisplay.textContent = state.username;

    console.log(`📌 Stage: ${form.dataset.submit}`);
    console.table(state);
}


/* ─────────────────────────────
   INPUT EVENTS
────────────────────────────── */

userIdInput.addEventListener("input", async () => {
    // Format as ####-####
    let num = userIdInput.value.replace(/\D/g, "").substring(0, 8);
    userIdInput.value = num.length > 4 ? `${num.slice(0,4)}-${num.slice(4)}` : num;

    const result = await apiCheckUserId();
    if (!result.success) console.warn("❌ ID Not Found");

    submitBtn.disabled = !result.success;

    saveForm();
});


/* ─────────────────────────────
   FORM SUBMISSION FLOW
────────────────────────────── */

form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const stage = form.dataset.submit;

    if (stage === "verifyUserID") {
        await apiCheckUserId();
    } 
    else if (stage === "verifyAnswers") {
        await apiAuthenticate(form);
    } 
    else if (stage === "resetPassword") {
        await apiResetPassword(form);
        return;
    }

    saveForm();
    updateUI();
});


/* ─────────────────────────────
   INIT
────────────────────────────── */

loadForm();
updateUI();

console.log("%c Password Reset JS Loaded", "color: orange; font-weight: bold;");

// toggle password visibility
document.querySelectorAll(".toggle-password-btn").forEach(btn => {
    btn.addEventListener("click", () => {
        // Find the closest input associated with this toggle button
        const input = btn.closest(".input-group").querySelector("input");
        const icon = btn.querySelector("i");

        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace("bi-eye", "bi-eye-slash");
        } else {
            input.type = "password";
            icon.classList.replace("bi-eye-slash", "bi-eye");
        }
    });
});

</script>
</body>
</html>
