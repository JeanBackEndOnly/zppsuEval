<?php
require_once '../../auth/control.php'; // Adjust the path as needed
$pdo = db_connect(); // Initializes $pdo
header('Content-Type: application/json');

if (isset($_GET['department_id'])) {
    $department_id = intval($_GET['department_id']); // Safe casting

    try {
        $stmt = $pdo->prepare("SELECT id, fname, lname FROM professor WHERE department_id = ? ORDER BY lname ASC");
        $stmt->execute([$department_id]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (Exception $e) {
        echo json_encode(['error' => 'Query failed']);
    }
} else {
    echo json_encode(['error' => 'No department_id provided']);
}
