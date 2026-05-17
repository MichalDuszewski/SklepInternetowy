<?php
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

include __DIR__ . '/../db.php';

$zamowienie_id = $_GET['zamowienie_id'] ?? null;

if (!$zamowienie_id) {
    http_response_code(400);
    echo json_encode(['error' => 'zamowienie_id is required']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['Klient']) || empty($data['Klient_adres']) || empty($data['Ilosc']) || empty($data['Produkt_ID'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$stmt = $con->prepare(
    "UPDATE Zamowienie SET Klient=?, Klient_adres=?, Ilosc=?, Produkt_ID=? WHERE Zamowienie_ID=?"
);
$stmt->bind_param("ssiii", $data['Klient'], $data['Klient_adres'], $data['Ilosc'], $data['Produkt_ID'], $zamowienie_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Zamowienie not found or data unchanged']);
    } else {
        http_response_code(200);
        echo json_encode(['message' => 'Zamowienie updated']);
    }
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}

$stmt->close();
$con->close();