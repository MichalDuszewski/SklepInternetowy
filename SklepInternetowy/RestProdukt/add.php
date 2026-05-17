<?php
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

include __DIR__ . '/../db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['Nazwa']) || empty($data['Opis']) || empty($data['Status'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$stmt = $con->prepare(
    "INSERT INTO Produkt (Nazwa, Opis, Status, Data_utworzenia) VALUES (?, ?, ?, NOW())"
);
$stmt->bind_param("sss", $data['Nazwa'], $data['Opis'], $data['Status']);

if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode([
        'message' => 'Produkt created',
        'Produkt_ID' => $stmt->insert_id
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
$stmt->close();
$con->close();
exit;