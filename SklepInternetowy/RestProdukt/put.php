<?php
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

include __DIR__ . '/../db.php';

$produkt_id = $_GET['produkt_id'] ?? null;

if (!$produkt_id) {
    http_response_code(400);
    echo json_encode(['error' => 'produkt_id is required']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['Nazwa']) || empty($data['Opis']) || empty($data['Status'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$stmt = $con->prepare(
    "UPDATE Produkt SET Nazwa=?, Opis=?, Status=? WHERE Produkt_ID=?"
);
$stmt->bind_param("sssi", $data['Nazwa'], $data['Opis'], $data['Status'], $produkt_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Produkt not found or data unchanged']);
    } else {
        http_response_code(200);
        echo json_encode(['message' => 'Produkt updated']);
    }
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}

$stmt->close();
$con->close();