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
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the form
    $transactionId = $_POST['update'];
    $name = $_POST['name'];
    $date = $_POST['date'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];

    // Prepare a SQL query to update the transaction
    $query = $dbCo->prepare("UPDATE transaction SET name = :name, date_transaction = :date, amount = :amount, id_category = :category WHERE id_transaction = :transactionId");

    // Execute the query by binding the parameters with the form values
    $isOk = $query->execute([
        ':transactionId' => $transactionId,
        ':name' => strip_tags($name), // Strip any HTML tags from the name
        ':date' => $date,
        ':amount' => floatval(strip_tags($amount)), // Convert the amount to a float after stripping any HTML tags
        ':category' => $category
    ]);

    // Redirect to the index.php page with a message
    header('Location: index.php?update_msg=' . ($isOk ? 'Transaction updated successfully.' : 'An error occurred while updating the transaction.'));
}
