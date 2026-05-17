<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $apiUrl = "http://portfolio123.onlinewebshop.net/SklepInternetowy/RestZamowienie/delete.php";

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['id' => $id]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    header("Location: panelAdministratora.php");
    exit;
}

$zamowienia = [];
$response = file_get_contents('http://portfolio123.onlinewebshop.net/SklepInternetowy/RestZamowienie/get.php');
if ($response) {
    $zamowienia = json_decode($response, true);
    }
        
foreach ($zamowienia as &$z) {
    $idProduktu = $z['Produkt_ID'];
    $responseProdukt = file_get_contents("http://portfolio123.onlinewebshop.net/SklepInternetowy/RestProdukt/get.php?produkt_id=$idProduktu");
    
    if ($responseProdukt) {
        $produkt = json_decode($responseProdukt, true);
        $z['Produkt_Nazwa'] = $produkt['Nazwa'] ?? 'Nieznany produkt';
    } else {
        $z['Produkt_Nazwa'] = 'Nieznany produkt';
    }
}
unset($z);
        
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Panel Administratora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h1>Sklep Internetowy</h1>
    <a href="formularzZamowienie.php" class="btn btn-primary mb-3">Dodaj zamówienie</a>
    <a href="index.php" class="btn btn-primary mb-3">Powrót na stronę główną</a>
    <a href="oProjekcie.php" class="btn btn-primary mb-3">O projekcie</a>
    <h2>Panel Administratora</h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Klient</th>
            <th>Adres klienta</th>
            <th>Ilość</th>
            <th>Produkt</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($zamowienia) && is_array($zamowienia)): ?>
            <?php foreach ($zamowienia as $z): ?>
                <tr>
                    <td><?= htmlspecialchars($z['Zamowienie_ID']) ?></td>
                    <td><?= htmlspecialchars($z['Klient']) ?></td>
                    <td><?= htmlspecialchars($z['Klient_adres']) ?></td>
                    <td><?= htmlspecialchars($z['Ilosc']) ?></td>
                    <td><?= htmlspecialchars($z['Produkt_Nazwa']) ?></td>
                    <td>
                        <a href="formularzZamowienie.php?id=<?= $z['Zamowienie_ID'] ?>" class="btn btn-sm btn-warning">Edytuj</a>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?= $z['Zamowienie_ID'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Czy na pewno chcesz usunąć zamówienie?');">
                                Usuń
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">Brak zamówień w bazie</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
