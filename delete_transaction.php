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


if (isset($_GET['delete'])) {
    $dealId = $_GET['delete'];
    $query = $dbCo->prepare("DELETE FROM transaction WHERE id_transaction = :dealId");
    $isOk = $query->execute([
        ':dealId' => intval(strip_tags($dealId))
    ]);
    // message if task is deleted or not
    header('Location: index.php?msg=' . ($isOk ? 'La tâche a été supprimée' : 'La tâche n\'a pas pu être supprimée'));
    exit;
}
