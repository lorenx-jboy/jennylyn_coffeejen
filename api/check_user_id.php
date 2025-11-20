<?php
require_once "../config/db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$id = trim($data["user_id"] ?? "");

if (!preg_match('/^\d{4}-\d{4}$/', $id)) {
    echo json_encode(["success" => false, "message" => "Invalid ID format."]);
    exit;
}

$stmt = $pdo->prepare("SELECT a1_question, a2_question, a3_question FROM users WHERE id_number = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo json_encode(["success" => false, "message" => "ID Number not found."]);
    exit;
}

echo json_encode([
    "success" => true,
    "questions" => [
        "q1" => $row["a1_question"],
        "q2" => $row["a2_question"],
        "q3" => $row["a3_question"]
    ]
]);
