<?php
header("Content-Type: application/json");

include __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$zamowienie_id = $_GET['zamowienie_id'] ?? null;

if ($zamowienie_id) {
    $stmt = $con->prepare(
        "SELECT Zamowienie_ID, Klient, Klient_adres, Ilosc, Produkt_ID
         FROM Zamowienie WHERE Zamowienie_ID=?"
    );
    $stmt->bind_param("i", $zamowienie_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Zamowienie not found']);
        exit;
    }

    echo json_encode($result->fetch_assoc());
    $stmt->close();

} else {
    $result = $con->query("SELECT Zamowienie_ID, Klient, Klient_adres, Ilosc, Produkt_ID FROM Zamowienie");
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    echo json_encode($orders);
}

$con->close();