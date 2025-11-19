<?php
// get_next.php
require_once 'config/db.php'; // your PDO connection

try {
    // Get the last id_number
    $stmt = $pdo->prepare("SELECT id_number FROM users ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $last = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($last && isset($last['id_number'])) {
        // Extract numeric part after the dash
        $num = intval(substr($last['id_number'], 5)) + 1;
        $nextId = "2025-" . str_pad($num, 4, "0", STR_PAD_LEFT);
    } else {
        $nextId = "2025-0001";
    }

    echo $nextId;

} catch (PDOException $e) {
    echo "Error generating next ID: " . $e->getMessage();
}
?>


