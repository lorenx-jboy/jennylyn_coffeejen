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
            (id_number, first_name, middle_name, last_name, extension_name, sex, birthdate, age, purok, barangay, city, province, country, zip_code, email, username, password, a1_question, a1_answer, a2_question, a2_answer, a3_question, a3_answer)
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
        header('Location: login.php');
        exit;
    } catch (PDOException $e) {
        $errorMsg = "Registration failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Brewstack Coffee - Register</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background-image: url(../image/pngtree-coffee-shop-with-many-wooden-tables-and-chairs-picture-image_2611848.jpg.jpg);
    color: #f8f3e9;
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
    margin: 0;
    padding: 0;
}

header, footer {
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
    background: #1f181699;
    width: 100%;
    max-width: 1200px;
    padding: 20px 40px;
    border-radius: 40px;
    overflow: hidden;
    max-height: 100vh;
    
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

.form-control, select {
    height: 28px; /* compact height */
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
<form action="register.php" method="POST">

    <!-- PERSONAL INFO -->
    <h3>Personal Information</h3>
    <div class="row g-2">
        <div class="col-md-3">
            <label>ID No. *</label>
            <input type="text" class="form-control" name="id_number" readonly value="<?php echo $nextIdNumber; ?>">
        </div>
        <div class="col-md-3">
            <label>First Name *</label>
            <input type="text" class="form-control" name="first_name" required>
        </div>
        <div class="col-md-3">
            <label>Middle</label>
            <input type="text" class="form-control" name="middle_name">
        </div>
        <div class="col-md-3">
            <label>Last *</label>
            <input type="text" class="form-control" name="last_name" required>
        </div>
        <div class="col-md-3">
            <label>Ext</label>
            <select class="form-control" name="extension_name">
                <option value="">None</option>
                <option value="Jr">Jr</option>
                <option value="Sr">Sr</option>
            </select>
        </div>
        <div class="col-md-3">
            <label>Sex *</label>
            <select class="form-control" name="sex" required>
                <option value="">Select</option>
                <option>Male</option>
                <option>Female</option>
            </select>
        </div>
        <div class="col-md-3">
            <label>Birthdate *</label>
            <input type="date" class="form-control" name="birthdate" required>
        </div>
        <div class="col-md-3">
            <label>Age *</label>
            <input type="text" class="form-control" name="age" readonly>
        </div>
    </div>

    <!-- ADDRESS -->
    <h3>Address</h3>
    <div class="row g-2">
        <div class="col-md-3">
            <label>Purok *</label>
            <input type="text" class="form-control" name="purok" required>
        </div>
        <div class="col-md-3">
            <label>Barangay *</label>
            <input type="text" class="form-control" name="barangay" required>
        </div>
        <div class="col-md-3">
            <label>City *</label>
            <input type="text" class="form-control" name="city" required>
        </div>
        <div class="col-md-3">
            <label>Province *</label>
            <input type="text" class="form-control" name="province" required>
        </div>
        <div class="col-md-3">
            <label>Country *</label>
            <input type="text" class="form-control" name="country" required>
        </div>
        <div class="col-md-3">
            <label>Zip *</label>
            <input type="text" class="form-control" maxlength="4" name="zip_code" required>
        </div>
    </div>

    <!-- CREDENTIALS -->
    <h3>Credentials</h3>
    <div class="row g-2">
        <div class="col-md-3">
            <label>Email *</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="col-md-3">
            <label>Username *</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="col-md-3">
            <label>Password *</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <div class="col-md-3">
            <label>Re-enter *</label>
            <input type="password" class="form-control" name="re_password" required>
        </div>
    </div>

    <!-- SECURITY -->
    <h3>Security Questions</h3>
    <div class="row g-2">
        <div class="col-md-4">
            <label>Q1 *</label>
            <select class="form-control" name="a1_question" required>
                <option value="">Select</option>
                <option value="experience">Experience?</option>
                <option value="hot_iced">Hot or iced?</option>
            </select>
            <input type="text" class="form-control mt-1" name="a1_answer" required>
        </div>
        <div class="col-md-4">
            <label>Q2 *</label>
            <select class="form-control" name="a2_question" required>
                <option value="">Select</option>
                <option value="fav_coffee">Favorite coffee?</option>
            </select>
            <input type="text" class="form-control mt-1" name="a2_answer" required>
        </div>
        <div class="col-md-4">
            <label>Q3 *</label>
            <select class="form-control" name="a3_question" required>
                <option value="">Select</option>
                <option value="ideal_coffee">Ideal cup?</option>
            </select>
            <input type="text" class="form-control mt-1" name="a3_answer" required>
        </div>
    </div>

    <button type="submit" class="btn-register mt-3">Register</button>
</form>
</div>
</div>

<footer>
    <p>&copy; 2025 Brewstack Coffee — All rights reserved.</p>
</footer>

</div>

</body>
</html>
