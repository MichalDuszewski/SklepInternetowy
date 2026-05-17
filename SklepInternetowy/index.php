<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $apiUrl = "http://portfolio123.onlinewebshop.net/SklepInternetowy/RestProdukt/delete.php";

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['id' => $id]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    header("Location: index.php");
    exit;
}

$products = [];
$response = file_get_contents('http://portfolio123.onlinewebshop.net/SklepInternetowy/RestProdukt/get.php');
if ($response) {
    $products = json_decode($response, true);
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Sklep Internetowy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h1>Sklep Internetowy</h1>
    <a href="formularzProdukt.php" class="btn btn-primary mb-3">Dodaj produkt</a>
    <a href="formularzZamowienie.php" class="btn btn-primary mb-3">Zamów produkt</a>
    <a href="panelAdministratora.php" class="btn btn-primary mb-3">Panel administratora</a>
    <a href="oProjekcie.php" class="btn btn-primary mb-3">O projekcie</a>
    <h2>Produkty</h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nazwa</th>
            <th>Opis</th>
            <th>Status</th>
            <th>Data utworzenia</th>
            <th>Akcje</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($products) && is_array($products)): ?>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['Produkt_ID']) ?></td>
                    <td><?= htmlspecialchars($p['Nazwa']) ?></td>
                    <td><?= htmlspecialchars($p['Opis']) ?></td>
                    <td><?= htmlspecialchars($p['Status']) ?></td>
                    <td><?= htmlspecialchars($p['Data_utworzenia']) ?></td>
                    <td>
                        <a href="formularzProdukt.php?id=<?= $p['Produkt_ID'] ?>" class="btn btn-sm btn-warning">Edytuj</a>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?= $p['Produkt_ID'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Czy na pewno chcesz usunąć produkt?');">
                                Usuń
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">Brak produktów w bazie</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table> 
</div>
</body>
</html>
