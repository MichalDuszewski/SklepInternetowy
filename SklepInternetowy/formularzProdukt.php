<?php
include 'db.php';

$id = $_GET['id'] ?? $_POST['id'] ?? null;
$produkt = ['Nazwa'=>'', 'Opis'=>'', 'Status'=>''];

if ($id) {
    $response = file_get_contents("http://portfolio123.onlinewebshop.net/SklepInternetowy/RestProdukt/get.php?produkt_id=$id");
    if ($response) {
        $produkt = json_decode($response, true);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'Nazwa' => $_POST['Nazwa'],
        'Opis' => $_POST['Opis'],
        'Status' => $_POST['Status']
    ];

    if ($id) {
        $ch = curl_init("http://portfolio123.onlinewebshop.net/SklepInternetowy/RestProdukt/put.php?produkt_id=$id");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    } else {
        $ch = curl_init("http://portfolio123.onlinewebshop.net/SklepInternetowy/RestProdukt/add.php");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?= $id ? 'Edytuj' : 'Dodaj' ?> produkt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <h1><?= $id ? 'Edytuj' : 'Dodaj' ?> produkt</h1>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Nazwa</label>
                <input type="text" name="Nazwa" class="form-control" value="<?= $produkt['Nazwa'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Opis</label>
                <textarea name="Opis" class="form-control" required><?= $produkt['Opis'] ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <input type="text" name="Status" class="form-control" value="<?= $produkt['Status'] ?>" required>
            </div>
            <button type="submit" class="btn btn-success"><?= $id ? 'Zapisz zmiany' : 'Dodaj produkt' ?></button>
            <a href="index.php" class="btn btn-secondary">Anuluj</a>
        </form>
    </div>
</body>
</html>