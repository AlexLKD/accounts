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
    $name = $_POST['name'];
    $date = $_POST['date'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];

    // Prepare a SQL query to insert a new transaction
    $query = $dbCo->prepare("INSERT INTO transaction (name, date_transaction, amount, id_category) VALUES (:name, :date, :amount, :categoryId)");

    // Execute the query by binding the parameters with the form values
    $isOk = $query->execute([
        ':name' => strip_tags($name), // Strip any HTML tags from the name
        ':date' => $date,
        ':amount' => floatval(strip_tags($amount)), // Convert the amount to a float after stripping any HTML tags
        ':categoryId' => $category
    ]);

    // Redirect to the add.php page with a success or error message
    header('Location: add.php?transac_msg=' . ($isOk ? 'Transaction added successfully.' : 'An error occurred while saving the transaction.'));
}
