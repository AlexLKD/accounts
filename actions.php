<?php

require 'includes/_database.php';
session_start();
if (!(array_key_exists('HTTP_REFERER', $_SERVER)) && str_contains($_SERVER['HTTP_REFERER'], $_ENV["URL"])) {
    header('Location: index.php?msg=error_referer');
    exit;
} else if (!array_key_exists('token', $_SESSION) || !array_key_exists('token', $_REQUEST) || $_SESSION['token'] !== $_REQUEST["token"]) {
    //...
    header('Location: index.php?msg=error_csrf');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['token']) && $_POST['token'] === $_SESSION['token']) {
        $name = $_POST['name'];
        $date = $_POST['date'];
        $amount = $_POST['amount'];
        $category = $_POST['category'];

        $query = $dbCo->prepare("INSERT INTO transaction (name, date_transaction, amount, id_category) VALUES (:name, :date, :amount, :categoryId)");
        $isOk = $query->execute([
            ':name' => $name,
            ':date' => $date,
            ':amount' => $amount,
            ':categoryId' => $category
        ]);
        header('Location: add.php?transac_msg=' . ($isOk ? 'Transaction ajoutée avec succès.' : 'Une erreur s\'est produite lors de l\'enregistrement de la transaction.'));
    } else {
        echo "Token CSRF invalide. Soumission de formulaire invalide.";
    }
}
