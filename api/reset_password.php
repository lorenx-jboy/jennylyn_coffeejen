<?php
require_once "../config/db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$id = trim($data["id_number"]);
$pass = $data["pass"];
$confirm = $data["confirm"];

if ($pass !== $confirm) {
    echo json_encode(["success" => false, "message" => "Passwords do not match."]);
    exit;
}

if (strlen($pass) < 6) {
    echo json_encode(["success" => false, "message" => "Password must be at least 6 characters."]);
    exit;
}

$hashed = password_hash($pass, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id_number = ?");
$stmt->execute([$hashed, $id]);

echo json_encode(["success" => true]);
