<?php
session_start();
require_once '../config/db.php'; // use your old db.php

// login check -- redirect if already logged in
require_once './functions.php';
logged_in();

// Function to generate next ID number
function getNextIdNumber($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT id_number FROM users ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        $last = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($last && isset($last['id_number'])) {
            $num = intval(substr($last['id_number'], 5)) + 1; // get number after "2025-"
            return "2025-" . str_pad($num, 4, "0", STR_PAD_LEFT);
        } else {
            return "2025-0001";
        }

    } catch (PDOException $e) {
        return "2025-0001"; // fallback if something goes wrong
    }
}

// Example: auto-fill ID number for form
$nextIdNumber = getNextIdNumber($pdo);

// Create users table if it doesn't exist


// Handle form submission

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Brewstack Coffee - Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/register.css">

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <!-- header -->
    <?php include '../includes/header.php' ?>
    <main>

        <form id="register-form" action="" method="post">
            <h2 class="text-white text-center">Registration Form</h2>

            <!-- PERSONAL INFO -->
            <h4 class="text-light">Personal Information</h4>
            <div class="row g-2">

                <div class="col-md-3">
                    <div class="form-group text-light">
                        <label class="text-light">ID No. <span class="span">*</span></label>
                        <input type="text" class="form-control " id="id_number" name="id_number" readonly
                            value="<?php echo $nextIdNumber; ?>" data-validate="required|id_number">
                        <small class="error-message invalid-feedback"></small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group text-light">
                        <label class="">First Name <span class="span">*</span></label>
                        <input type="text" class="form-control " id="first_name" name="first_name" required
                            data-validate="first_name">
                        <small class="error-message invalid-feedback"></small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group text-light">
                        <label>Middle Name<span class="span" style="font-style:italic;">(optional)</span></label>
                        <input type="text" class="form-control " id="middle_name" name="middle_name"
                            data-validate="middle_name">
                        <small class="error-message invalid-feedback"></small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group text-light">
                        <label>LastName <span class="span">*</span></label>
                        <input type="text" class="form-control " id="last_name" name="last_name" required
                            data-validate="required|last_name">
                        <small class="error-message invalid-feedback"></small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group text-light">
                        <label>Extension Name<span class="span">*</span></label>
                        <input type="text" class="form-control " id="extension_name" name="extension_name" required
                            data-validate="extension_name">
                        <small class="error-message invalid-feedback"></small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group text-light">
                        <label>Sex <span class="span">*</span></label>
                        <select id="sex" class="form-control " name="sex" required data-validate="required">
                            <option value="">Select</option>
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                        <small class="error-message invalid-feedback"></small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group text-light">
                        <label>Birthdate <span class="span">*</span></label>
                        <input type="date" class="form-control " id="birthdate" name="birthdate" required
                            data-validate="required|birthdate">
                        <small class="error-message invalid-feedback"></small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group text-light">
                        <label>Age <span class="span">*</span></label>
                        <input type="text" class="form-control " id="age" name="age" readonly
                            data-validate="required|age">
                        <small class="error-message invalid-feedback"></small>
                    </div>
                </div>
            </div>


            <!-- ADDRESS -->
            <h4 class="text-light">Address</h4>
            <div class="row g-2">
                <div class="col-md-3 text-light">
                    <label>Purok <span class="span">*</span></label>
                    <input type="text" class="form-control " id="purok" name="purok" required
                        data-validate="required|purok">
                    <small class="error-message invalid-feedback"></small>

                </div>
                <div class="col-md-3 text-light">
                    <label>Barangay <span class="span">*</span></label>
                    <input type="text" class="form-control " id="barangay" name="barangay" required
                        data-validate="required|barangay">
                    <small class="error-message invalid-feedback"></small>

                </div>
                <div class="col-md-3 text-light">
                    <label>City <span class="span">*</span></label>
                    <input type="text" class="form-control " id="city" name="city" required
                        data-validate="required|city">
                    <small class="error-message invalid-feedback"></small>
                </div>
                <div class="col-md-3 text-light">
                    <label>Province <span class="span">*</span></label>
                    <input type="text" class="form-control " id="province" name="province" required
                        data-validate="required|province">
                    <small class="error-message invalid-feedback"></small>

                </div>
                <div class="col-md-3 text-light">
                    <label>Country <span class="span">*</span></label>
                    <input type="text" class="form-control " id="country" name="country" required
                        data-validate="required|country">
                    <small class="error-message invalid-feedback"></small>

                </div>
                <div class="col-md-3 text-light">
                    <div class="form-group text-light"></div>
                    <label>Zip <span class="span">*</span></label>
                    <input type="text" class="form-control " id="zipcode" maxlength="4" name="zipcode" required
                        data-validate="required|zipcode">
                    <small class="error-message invalid-feedback"></small>

                </div>
            </div>

            <!-- CREDENTIALS -->
            <h4 class="text-light">Credentials</h4>
            <div class="row g-2">
                <div class="col-md-3 text-light">
                    <div class="form-group text-light"></div>
                    <label>Email <span class="span">*</span></label>
                    <input type="email" class="form-control " id="email" name="email" required
                        data-validate="required|email">
                    <small class="error-message invalid-feedback"></small>

                </div>
                <div class="col-md-3 text-light">
                    <div class="form-group text-light"></div>
                    <label>Username <span class="span">*</span></label>
                    <input type="text" class="form-control " id="username" name="username" required
                        data-validate="required|username">
                    <small class="error-message invalid-feedback"></small>
                </div>

                <div class="col-md-3 text-light">
                    <label>Password <span class="span">*</span></label>
                    <div class="position-relative">
                        <input type="password" class="form-control" id="password" name="password" required
                            data-validate="required|password">
                        <button type="button"
                            class="toggle-password-btn position-absolute translate-middle-y bg-transparent border-0 "
                            data-type="password" aria-label="Toggle password visibility">
                            <i class="bi bi-eye"></i>
                        </button>
                        <small class="error-message invalid-feedback"></small>

                    </div>

                    <div class="password-strength-bar mt-2">
                        <div class="strength-fill"></div>
                    </div>
                </div>

                <div class="col-md-3 text-light">
                    <label>Re-enter Password <span class="span">*</span></label>
                    <div class="position-relative">
                        <input type="password" class="form-control" id="repassword" name="repassword" required
                            data-validate="required|repassword">
                        <button type="button"
                            class="toggle-password-btn position-absolute translate-middle-y bg-transparent border-0"
                            data-type="repassword" aria-label="Toggle password visibility">
                            <i class="bi bi-eye"></i>
                        </button>
                        <small class="error-message invalid-feedback"></small>
                    </div>
                </div>
            </div>

            <!-- SECURITY -->
            <h4 class="text-light">Security Questions</h4>
            <div class="row">
                <!-- Q1 -->
                <div class="col-md-4 ">
                    <select id="a1_question" class="form-control" name="a1_question" required
                        data-validate="required|a1_question">
                        <option value="" disabled selected hidden>Select Question 1</option>
                        <option value="teacher">Who is your favorite teacher in high school?</option>
                        <option value="pet">What is the name of your favorite pet?</option>
                        <option value="best_friend">Who is your best friend in Elementary?</option>
                        <option value="first_car">What was the make or model of your first car?</option>
                        <option value="childhood_game">What was your favorite game as a child?</option>
                        <option value="favorite_food">What is your favorite food?</option>
                    </select>

                    <div class="position-relative mt-1">
                        <input type="password" id="a1_answer" name="a1_answer" class="form-control" required
                            placeholder="Enter your answer" data-validate="required|a1_answer">
                        <button type="button"
                            class="toggle-password-btn position-absolute   translate-middle-y bg-transparent border-0"
                            data-type="a1_answer" aria-label="Toggle password visibility">
                            <i class="bi bi-eye"></i>
                        </button>
                        <small class="error-message invalid-feedback"></small>
                    </div>
                </div>

                <!-- Q2 -->
                <div class="col-md-4 ">
                    <select id="a2_question" class="form-control " name="a2_question" required
                        data-validate="required|a2_question">
                        <option value="" disabled selected hidden>Select Question 2</option>
                        <option value="dream_job">What was your dream job when you were a kid?</option>
                        <option value="favorite_movie">What is your all-time favorite movie?</option>
                        <option value="travel_destination">Which country would you love to visit the most?</option>
                        <option value="hobby">What hobby do you enjoy the most?</option>
                        <option value="childhood_memory">What is your happiest childhood memory?</option>
                        <option value="superpower">If you could have any superpower, what would it be?</option>

                    </select>

                    <div class="position-relative mt-1">
                        <input type="password" id="a2_answer" name="a2_answer" class="form-control" required
                            placeholder="Enter your answer" data-validate="required|a2_answer">
                        <button type="button"
                            class="toggle-password-btn position-absolute  translate-middle-y bg-transparent border-0"
                            data-type="a2_answer" aria-label="Toggle password visibility">
                            <i class="bi bi-eye"></i>
                        </button>
                        <small class="error-message invalid-feedback"></small>
                    </div>
                </div>

                <!-- Q3 -->
                <div class="col-md-4">
                    <select id="a3_question" class="form-control " name="a3_question" required
                        data-validate="required|a3_question">
                        <option value="" disabled selected hidden>Select Question 3</option>
                        <option value="first_job">What was your first part-time or summer job?</option>
                        <option value="favorite_book">Which book has influenced you the most?</option>
                        <option value="childhood_nickname">Did you have a childhood nickname? What was it?</option>
                        <option value="proud_moment">What is a moment in your life that made you really proud?</option>
                        <option value="fear">What is a fear you’ve overcome or still have?</option>
                        <option value="hidden_talent">Do you have a hidden talent? What is it?</option>

                    </select>

                    <div class="position-relative mt-1">
                        <input type="password" id="a3_answer" name="a3_answer" class="form-control" required
                            placeholder="Enter your answer" data-validate="required|a3_answer">
                        <button type="button"
                            class="toggle-password-btn position-absolute  translate-middle-y bg-transparent border-0"
                            data-type="a3_answer" aria-label="Toggle password visibility">
                            <i class="bi bi-eye"></i>
                        </button>
                        <small class="error-message invalid-feedback"></small>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="mt-3 btn btn-success w-25">Register</button>
            </div>
        </form>
    </main>
    <footer>
        <p>&copy; 2025 Brewstack Coffee — All rights reserved.</p>
    </footer>


    <script type="module" src="../js/register.js"></script>
</body>

</html>