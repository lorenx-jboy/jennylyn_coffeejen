<?php
session_start();
require_once '../config/db.php'; // use your old db.php

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
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_number VARCHAR(20) NOT NULL UNIQUE,
            first_name VARCHAR(50) NOT NULL,
            middle_name VARCHAR(50),
            last_name VARCHAR(50) NOT NULL,
            extension_name VARCHAR(10),
            sex VARCHAR(10) NOT NULL,
            birthdate DATE NOT NULL,
            age INT,
            purok VARCHAR(50),
            barangay VARCHAR(50),
            city VARCHAR(50),
            province VARCHAR(50),
            country VARCHAR(50),
            zip_code VARCHAR(10),
            email VARCHAR(100) NOT NULL UNIQUE,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            a1_question VARCHAR(100),
            a1_answer VARCHAR(100),
            a2_question VARCHAR(100),
            a2_answer VARCHAR(100),
            a3_question VARCHAR(100),
            a3_answer VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
} catch (PDOException $e) {
    die("Table creation failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_number = $_POST['id_number'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'] ?? '';
    $last_name = $_POST['last_name'];
    $extension_name = $_POST['extension_name'] ?? '';
    $sex = $_POST['sex'];
    $birthdate = $_POST['birthdate'];
    $age = $_POST['age'] ?? null;
    $purok = $_POST['purok'];
    $barangay = $_POST['barangay'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $country = $_POST['country'];
    $zip_code = $_POST['zip_code'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $a1_question = $_POST['a1_question'];
    $a1_answer = $_POST['a1_answer'];
    $a2_question = $_POST['a2_question'];
    $a2_answer = $_POST['a2_answer'];
    $a3_question = $_POST['a3_question'];
    $a3_answer = $_POST['a3_answer'];

    try {
        $stmt = $pdo->prepare("
            INSERT INTO users
            (id_number, first_name, middle_name, last_name, extension_name, sex, birthdate, age, purok, barangay, city, province, country, zip_code, email, username, password_hash, a1_question, a1_answer, a2_question, a2_answer, a3_question, a3_answer)
            VALUES
            (:id_number, :first_name, :middle_name, :last_name, :extension_name, :sex, :birthdate, :age, :purok, :barangay, :city, :province, :country, :zip_code, :email, :username, :password, :a1_question, :a1_answer, :a2_question, :a2_answer, :a3_question, :a3_answer)
        ");
        $stmt->execute([
            ':id_number' => $id_number,
            ':first_name' => $first_name,
            ':middle_name' => $middle_name,
            ':last_name' => $last_name,
            ':extension_name' => $extension_name,
            ':sex' => $sex,
            ':birthdate' => $birthdate,
            ':age' => $age,
            ':purok' => $purok,
            ':barangay' => $barangay,
            ':city' => $city,
            ':province' => $province,
            ':country' => $country,
            ':zip_code' => $zip_code,
            ':email' => $email,
            ':username' => $username,
            ':password' => $password,
            ':a1_question' => $a1_question,
            ':a1_answer' => $a1_answer,
            ':a2_question' => $a2_question,
            ':a2_answer' => $a2_answer,
            ':a3_question' => $a3_question,
            ':a3_answer' => $a3_answer
        ]);

        $_SESSION['success'] = "Registration successful! You can now login.";
        echo json_encode(['success' => true, 'message' => "Registration successful! You can now login."]);
        header('Location: login.php');
        exit;
    } catch (PDOException $e) {
        $errorMsg = "Registration failed: " . $e->getMessage();
        error_log($errorMsg);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Brewstack Coffee - Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
    body {
        background-image: url("../image/image2.png");
        background-color: rgba(0, 0, 0, 0.25); /* darkness level */
        background-blend-mode: darken;  /* or try multiply */
        background-size: cover;
        background-position: center;
        margin: 0;
        padding: 0;
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

    /* FIXED LAYOUT, NO SCROLL */
    .page-wrapper {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .register-container {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px;
    }

    .register-box {
        /* background: #522b2099; */
        width: 100%;
        max-width: 1200px;
        padding: 20px 40px;
        border-radius: 14px;
        /* overflow: hidden; */
        /* max-height: 100vh; */

    }

    h2 {
        text-align: center;
        margin-bottom: 10px;
        font-weight: 1000;
        color: #f8f3e9;
    }

    h3 {
        font-size: 16px;
        margin-top: 10px;
        border-bottom: 1px solid #c09d74;
        padding-bottom: 3px;
        margin-bottom: 8px;
    }

    .form-control,
    select {
        height: 28px;
        /* compact height */
        font-size: 13px;
        padding: 2px 6px;
    }

    .btn-register {
        background-color: #4e342e;
        width: 100%;
        padding: 6px;
        font-size: 14px;
        font-weight: bold;
        color: #c09d74;
        border: none;
        border-radius: 8px;
    }

    /* Compact spacing */
    .row.g-2 {
        --bs-gutter-y: 5px;
        --bs-gutter-x: 8px;
    }


    .error-message {
        color: #d9534f;
        font-size: 13px;
        font-weight: 500;
        margin-top: 2px;
        min-height: 14px;
        display: block;
    }

    .input-error {
        border-color: #d9534f !important;
    }


    /* Remove default show password icon  */
    input[type="password"]::-ms-reveal,
    input[type="password"]::-ms-clear {
        display: none !important;
    }

    input[type="password"]::-webkit-textfield-decoration-container,
    input[type="password"]::-webkit-credentials-auto-fill-button {
        display: none !important;
        visibility: hidden;
        pointer-events: none;
    }

    .span {
        color: red;
    }

    /* Remove default show password icon */
    .form-control.is-valid,
    .was-validated .form-control:valid {
        background-image: none !important;
        padding-right: .75rem !important; /* optional: remove extra space */
    }

    .toggle-password-btn {
        top: 15px;
        right: 6px;
        transform: translateY(-50%);
    }

    .toggle-password-btn:focus {
        outline: 1px solid #c09d74;
    }

    input {
        color: brown;
    }
    </style>
</head>

<body>

    <div class="page-wrapper">

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

        <div class="register-container">
            <div class="register-box">
                <h2>Registration Form</h2>
                <form id="register-form" action="" method="post">
                <!-- PERSONAL INFO -->
                <h3>Personal Information</h3>
                <div class="row g-2">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>ID No. <span class="span">*</span></label>
                            <input type="text" class="form-control " id="id_number" name="id_number" readonly
                                value="<?php echo $nextIdNumber; ?>" data-validate="required|id_number">
                            <small class="error-message invalid-feedback"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>First Name <span class="span">*</span></label>
                            <input type="text" class="form-control " id="first_name" name="first_name" required
                                data-validate="required|first_name">
                            <small class="error-message invalid-feedback"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Middle <span class="span" style="font-style:italic;">(optional)</span></label>
                            <input type="text" class="form-control " id="middle_name" name="middle_name"
                                data-validate="middle_name">
                            <small class="error-message invalid-feedback"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>LastName <span class="span">*</span></label>
                            <input type="text" class="form-control " id="last_name" name="last_name" required
                                data-validate="required|last_name">
                            <small class="error-message invalid-feedback"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Extenstion name <span class="span" ;
                                    style="font-style:italic;">(optional)</span></label>
                            <select id="extension_name" class="form-control " name="extension_name" data-validate="">
                                <option value="">None</option>
                                <option value="Jr">Jr</option>
                                <option value="Sr">Sr</option>
                            </select>
                            <small class="error-message invalid-feedback"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Sex <span class="span">*</span></label>
                            <select id="sex" class="form-control " name="sex" required
                                data-validate="required">
                                <option value="">Select</option>
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                            <small class="error-message invalid-feedback"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Birthdate <span class="span">*</span></label>
                            <input type="date" class="form-control " id="birthdate" name="birthdate" required
                                data-validate="required|birthdate">
                            <small class="error-message invalid-feedback"></small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Age <span class="span">*</span></label>
                            <input type="text" class="form-control " id="age" name="age" readonly
                                data-validate="required|age">
                            <small class="error-message invalid-feedback"></small>
                        </div>
                    </div>

                </div>


                <!-- ADDRESS -->
                <h3>Address</h3>
                <div class="row g-2">
                    <div class="col-md-3">
                        <label>Purok <span class="span">*</span></label>
                        <input type="text" class="form-control " id="purok" name="purok" required
                            data-validate="required|purok">
                        <small class="error-message invalid-feedback"></small>

                    </div>
                    <div class="col-md-3">
                        <label>Barangay <span class="span">*</span></label>
                        <input type="text" class="form-control " id="barangay" name="barangay" required
                            data-validate="required|barangay">
                        <small class="error-message invalid-feedback"></small>

                    </div>
                    <div class="col-md-3">
                        <label>City <span class="span">*</span></label>
                        <input type="text" class="form-control " id="city" name="city" required
                            data-validate="required|city">
                        <small class="error-message invalid-feedback"></small>
                    </div>
                    <div class="col-md-3">
                        <label>Province <span class="span">*</span></label>
                        <input type="text" class="form-control " id="province" name="province" required
                            data-validate="required|province">
                        <small class="error-message invalid-feedback"></small>

                    </div>
                    <div class="col-md-3">
                        <label>Country <span class="span">*</span></label>
                        <input type="text" class="form-control " id="country" name="country" required
                            data-validate="required|country">
                        <small class="error-message invalid-feedback"></small>

                    </div>
                    <div class="col-md-3">
                        <label>Zip <span class="span">*</span></label>
                        <input type="text" class="form-control " id="zipcode" maxlength="4" name="zipcode" required
                            data-validate="required|zipcode">
                        <small class="error-message invalid-feedback"></small>

                    </div>
                </div>

                <!-- CREDENTIALS -->
                <h3>Credentials</h3>
                <div class="row g-2">
                    <div class="col-md-3">
                        <label>Email <span class="span">*</span></label>
                        <input type="email" class="form-control " id="email" name="email" required
                            data-validate="required|email">
                        <small class="error-message invalid-feedback"></small>

                    </div>
                    <div class="col-md-3">
                        <label>Username <span class="span">*</span></label>
                        <input type="text" class="form-control " id="username" name="username" required
                            data-validate="required|username">
                        <small class="error-message invalid-feedback"></small>
                    </div>

                    <div class="col-md-3">
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

                    <div class="col-md-3">
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
                <h3>Security Questions</h3>
                <div class="row g-2">
                    <div class="col-md-4">
                        <label>Q1 <span class="span">*</span></label>
                        <select id="a1_question" class="form-control " name="a1_question" required data-validate="required">
                            <option value="">Select</option>
                            <option value="teacher"> Who is your favorite teacher in high school? </option>
                            <option value="pet">What is the name of your favorite pet? </option>
                            <option value="best_friend">Who is your best friend in Elementary? </option>

                        </select>
                        <input type="password" class="form-control mt-1 " id="a1_answer" name="a1_answer" required
                            data-validate="required|a1_answer">
                        <small class="error-message invalid-feedback"></small>
                    </div>
                    <div class="col-md-4">
                        <label>Q2 <span class="span">*</span></label>
                        <select id="a2_question" class="form-control " name="a2_question" required data-validate="required">
                            <option value="">Select</option>
                            <option value="fav_coffee">Favorite coffee?</option>
                        </select>
                        <input type="password" class="form-control mt-1 " name="a2_answer" required
                            data-validate="required|a2_answer">
                        <small class="error-message invalid-feedback"></small>
                    </div>
                    <div class="col-md-4">
                        <label>Q3 <span class="span">*</span></label>
                        <select class="form-control" id="a3_question" name="a3_question" required data-validate="required">
                            <option value="">Select</option>
                            <option value="ideal_coffee">Ideal cup?</option>
                        </select>
                        <input type="password" class="form-control mt-1 " name="a3_answer" required
                            data-validate="required|a3_answer">
                        <small class="error-message invalid-feedback"></small>

                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="submit" class="mt-3 btn btn-success w-25">Register</button>
                </div>


                </form>
            </div>
        </div>

        <footer>
            <p>&copy; 2025 Brewstack Coffee — All rights reserved.</p>
        </footer>

    </div>

    <!--<script src="../js/personal_information_register.js"></script> 
<script src="../js/address_access_register.js"></script>
<script src="../js/password_register.js"></script>
<script src="../js/security_question_register.js"></script>
-->

    <script type="module" src="../js/register.js"></script>
</body>

</html>