<?php
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

include __DIR__ . '/../db.php';

$produkt_id = $_POST['id'] ?? null;

if (!$produkt_id) {
    http_response_code(400);
    echo json_encode(['error' => 'produkt_id is required']);
    exit;
}

$stmt = $con->prepare("DELETE FROM Produkt WHERE Produkt_ID=?");
$stmt->bind_param("i", $produkt_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Produkt not found']);
    } else {
        http_response_code(200);
        echo json_encode(['message' => 'Produkt deleted']);
    }
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}

$stmt->close();
$con->close();