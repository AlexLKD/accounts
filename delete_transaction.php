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


// Check if the 'delete' parameter is set in the URL
if (isset($_GET['delete'])) {
    // Get the 'dealId' from the URL parameter
    $dealId = $_GET['delete'];

    // Prepare a SQL query to delete the transaction with the specified 'dealId'
    $query = $dbCo->prepare("DELETE FROM transaction WHERE id_transaction = :dealId");

    // Execute the query by binding the 'dealId' parameter  
    $isOk = $query->execute([
        ':dealId' => intval(strip_tags($dealId))
    ]);

    // Redirect to the index.php page with a message
    header('Location: index.php?msg=' . ($isOk ? 'The task has been deleted.' : 'The task could not be deleted.'));
    exit; // Terminate the script to prevent further execution
}
