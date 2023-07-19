<?php

require 'includes/_database.php';
session_start();

if (!(array_key_exists('HTTP_REFERER', $_SERVER)) && str_contains($_SERVER['HTTP_REFERER'], $_ENV["URL"])) {
    header('Location: dashboard.php?msg=error_referer');
    exit;
} else if (!array_key_exists('token', $_SESSION) || !array_key_exists('token', $_REQUEST) || $_SESSION['token'] !== $_REQUEST["token"]) {
    //...
    header('Location: dashboard.php?msg=error_csrf');
    exit;
}

$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

if ($fileType != "csv") {
    echo "Sorry, only CSV files are allowed.";
    header('Location: import.php?msg="Sorry, only CSV files are allowed."');
    exit;
}
$handle = fopen($tmpFilePath, 'r');
if ($handle !== false) {
    while (($data = fgetcsv($handle, 1000, ',')) !== false) {
        $name = strip_tags($data[0]);
        $date = $data[1];
        $amount = floatval($data[2]);

        $query = $dbCo->prepare("INSERT INTO transaction (name, date_transaction, amount) VALUES (:name, :date, :amount)");
        $isOk = $query->execute([
            ':name' => $name,
            ':date' => $date,
            ':amount' => $amount
        ]);
    }

    fclose($handle);
    header('Location: index.php?import_msg=Importation réussie.');
} else {
    header('Location: index.php?import_msg=Erreur lors de l\'ouverture du fichier.');
}
