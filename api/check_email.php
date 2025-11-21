<?php

require_once "../config/db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data["email"] ?? "");


$stmt = $pdo->prepare("SELECT email FROM users WHERE email = :email");
$stmt->execute([':email' => $username]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo json_encode(["success" => false, "message" => "Email not found.", "email" => $email, "user" => $row['email']]);
    exit;
}

echo json_encode([
    "success" => true,
    "message" => "Email found."
]);
