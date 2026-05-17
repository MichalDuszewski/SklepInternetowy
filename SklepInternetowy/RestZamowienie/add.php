<?php
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

include __DIR__ . '/../db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['Klient']) || empty($data['Klient_adres']) || empty($data['Ilosc']) || empty($data['Produkt_ID'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$stmt = $con->prepare(
    "INSERT INTO Zamowienie (Klient, Klient_adres, Ilosc, Produkt_ID) VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("ssii", $data['Klient'], $data['Klient_adres'], $data['Ilosc'], $data['Produkt_ID']);

if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode([
        'message' => 'Zamowienie created',
        'Zamowienie_ID' => $stmt->insert_id
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}

$stmt->close();
$con->close();