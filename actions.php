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
            ':name' => strip_tags($name),
            ':date' => $date,
            ':amount' => floatval(strip_tags($amount)),
            ':categoryId' => $category
        ]);
        header('Location: add.php?transac_msg=' . ($isOk ? 'Transaction ajoutée avec succès.' : 'Une erreur s\'est produite lors de l\'enregistrement de la transaction.'));
    } else {
        echo "Token CSRF invalide. Soumission de formulaire invalide.";
    }
}


// Récupérer les données du formulaire
$operationId = $_POST['operation_id'];
$name = $_POST['name'];
$date = $_POST['date'];
$amount = $_POST['amount'];
$category = $_POST['category'];

// Vérifier si le montant est négatif
if (substr($amount, 0, 1) === '-') {
    // Si le montant est négatif, enlever le signe '-' pour l'enregistrement en base de données
    $amount = substr($amount, 1);
} else {
    // Si le montant est positif, ajouter le signe '-' pour l'enregistrement en base de données
    $amount = '-' . $amount;
}

// Requête SQL pour mettre à jour l'opération dans la base de données
$query = $dbCo->prepare("UPDATE transaction SET name = :name, date_transaction = :date, amount = :amount, id_category = :category WHERE id = :operationId");
$isOk = $query->execute([
    ':name' => $name,
    ':date' => $date,
    ':amount' => $amount,
    ':category' => $category,
    ':operationId' => $operationId
]);

if ($isOk) {
    // La mise à jour a réussi
    echo "L'opération a été mise à jour avec succès.";
} else {
    // La mise à jour a échoué
    echo "Erreur lors de la mise à jour de l'opération.";
}
