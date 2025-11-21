<?php
require_once "../config/db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$id = trim($data["user_id"]);
$q1 = trim($data["auth_question_1"]);
$q2 = trim($data["auth_question_2"]);
$q3 = trim($data["auth_question_3"]);
$a1 = trim($data["auth_answer_1"]);
$a2 = trim($data["auth_answer_2"]);
$a3 = trim($data["auth_answer_3"]);

$stmt = $pdo->prepare("SELECT a1_answer, a2_answer, a3_answer, a1_question, a2_question, a3_question FROM users WHERE id_number = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo json_encode(["success" => false, "message" => "ID not found."]);
    exit;
}

$matches = 0;
if ($row["a1_question"] === $q1 && password_verify($a1, $row["a1_answer"])) $matches++;
if ($row["a2_question"] === $q2 && password_verify($a2, $row["a2_answer"])) $matches++;
if ($row["a3_question"] === $q3 && password_verify($a3, $row["a3_answer"])) $matches++;

if ($matches >= 2) {
    echo json_encode(["success" => true, "message" => "Authentication successful."]);
} else {
    echo json_encode(["success" => false, "message" => "Security answers or questions are incorrect."]);
}

