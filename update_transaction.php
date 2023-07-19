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
        $transactionId = $_POST['update'];
        $name = $_POST['name'];
        $date = $_POST['date'];
        $amount = $_POST['amount'];
        $category = $_POST['category'];

        $query = $dbCo->prepare("UPDATE transaction SET name = :name, date_transaction = :date, amount = :amount, id_category = :category WHERE id_transaction = :transactionId");
        $isOk = $query->execute([
            ':transactionId' => $transactionId,
            ':name' => strip_tags($name),
            ':date' => $date,
            ':amount' => floatval(strip_tags($amount)),
            ':category' => $category
        ]);
        header('Location: index.php?update_msg=' . ($isOk ? 'Transaction mise à jour avec succès.' : 'Une erreur s\'est produite lors de la mise à jour de la transaction.'));
    } else {
        echo "Token CSRF invalide. Soumission de formulaire invalide.";
    }
}
