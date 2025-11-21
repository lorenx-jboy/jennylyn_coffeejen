<?php

require_once "../config/db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$username = trim($data["username"] ?? "");


$stmt = $pdo->prepare("SELECT username FROM users WHERE username = :username");
$stmt->execute([':username' => $username]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo json_encode(["success" => false, "message" => "Username not found."]);
    exit;
}

echo json_encode([
    "success" => true,
    "message" => "Username found."
]);
