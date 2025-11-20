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
    $zip_code = $_POST['zipcode'];
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
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':a1_question' => $a1_question,
            ':a1_answer' => password_hash($a1_answer, PASSWORD_DEFAULT),
            ':a2_question' => $a2_question,
            ':a2_answer' => password_hash($a2_answer, PASSWORD_DEFAULT),
            ':a3_question' => $a3_question,
            ':a3_answer' => password_hash($a3_answer, PASSWORD_DEFAULT),
        ]);

        $_SESSION['success'] = "Registration successful! You can now login.";
        echo json_encode(['success' => true, 'message' => "Registration successful! You can now login."]);
        exit;
    } catch (PDOException $e) {
        $errorMsg = "Registration failed: " . $e->getMessage();
        error_log($errorMsg);
    }
}



// if ($_SERVER['REQUEST_METHOD'] === 'POST'){
//     echo json_encode(['success' => true, 'message' => "Registration successful! You can now login."]);
//     exit;
// }


?>