<?php
include 'db.php';

$id = $_GET['id'] ?? $_POST['id'] ?? null;
$zamowienie = ['Klient'=>'', 'Klient_adres'=>'', 'Ilosc'=>'', 'Produkt_ID'=>''];
$produkty = [];
$responseProdukty = file_get_contents("http://portfolio123.onlinewebshop.net/SklepInternetowy/RestProdukt/get.php");

if ($responseProdukty) {
    $produkty = json_decode($responseProdukty, true);
	}

if ($id) {
    $response = file_get_contents("http://portfolio123.onlinewebshop.net/SklepInternetowy/RestZamowienie/get.php?zamowienie_id=$id");
    if ($response) {
        $zamowienie = json_decode($response, true);
    	}
	}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'Klient' => $_POST['Klient'],
        'Klient_adres' => $_POST['Klient_adres'],
        'Ilosc' => $_POST['Ilosc'],
        'Produkt_ID' => $_POST['Produkt_ID']
    ];

    if ($id) {
        $ch = curl_init("http://portfolio123.onlinewebshop.net/SklepInternetowy/RestZamowienie/put.php?zamowienie_id=$id");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    } else {
        $ch = curl_init("http://portfolio123.onlinewebshop.net/SklepInternetowy/RestZamowienie/add.php");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }
    header("Location: panelAdministratora.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?= $id ? 'Edytuj' : 'Dodaj' ?> Zamówienie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <h1><?= $id ? 'Edytuj' : 'Dodaj' ?> Zamówienie</h1>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Klient</label>
                <input type="text" name="Klient" class="form-control" value="<?= $zamowienie['Klient'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Adres klienta</label>
                <textarea name="Klient_adres" class="form-control" required><?= $zamowienie['Klient_adres'] ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Ilość</label>
                <input type="text" name="Ilosc" class="form-control" value="<?= $zamowienie['Ilosc'] ?>" required>
            </div>
			<div class="mb-3">
    			<label class="form-label">Produkt</label>
    			<select name="Produkt_ID" class="form-control" required>
        			<option value="">Wybierz produkt</option>
        			<?php foreach ($produkty as $produkt): ?>
            			<option value="<?= $produkt['Produkt_ID'] ?>"
               			<?= ($produkt['Produkt_ID'] == ($zamowienie['Produkt_ID'] ?? '')) ? 'selected' : '' ?>>
                		<?= htmlspecialchars($produkt['Nazwa']) ?>
            			</option>
        			<?php endforeach; ?>
    			</select>
			</div>
            <button type="submit" class="btn btn-success"><?= $id ? 'Zapisz zmiany' : 'Dodaj zamówienie' ?></button>
            <a href="panelAdministratora.php" class="btn btn-secondary">Anuluj</a>
        </form>
    </div>
</body>
</html>