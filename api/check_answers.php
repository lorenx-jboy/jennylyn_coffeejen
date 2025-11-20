<?php
require_once "../config/db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$id = trim($data["id_number"]);
$a1 = trim($data["a1"]);
$a2 = trim($data["a2"]);
$a3 = trim($data["a3"]);

$stmt = $pdo->prepare("SELECT a1_answer, a2_answer, a3_answer FROM users WHERE id_number = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo json_encode(["success" => false, "message" => "ID not found."]);
    exit;
}

if (
    password_verify($a1, $row["a1_answer"]) &&
    password_verify($a2, $row["a2_answer"]) &&
    password_verify($a3, $row["a3_answer"])
) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Security answers incorrect."]);
}
