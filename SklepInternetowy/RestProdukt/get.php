<?php
header("Content-Type: application/json");

include __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$produkt_id = $_GET['produkt_id'] ?? null;

if ($produkt_id) {
    $stmt = $con->prepare(
        "SELECT Produkt_ID, Nazwa, Opis, Status, Data_utworzenia
         FROM Produkt
         WHERE Produkt_ID = ?"
    );
    $stmt->bind_param("i", $produkt_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Produkt not found']);
        exit;
    }

    echo json_encode($result->fetch_assoc());

} else {
    $result = $con->query(
        "SELECT Produkt_ID, Nazwa, Opis, Status, Data_utworzenia
         FROM Produkt"
    );

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode($products);
}

$con->close();