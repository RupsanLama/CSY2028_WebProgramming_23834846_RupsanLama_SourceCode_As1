<?php
require 'dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve bid amount from POST data
    $bidAmount = $_POST["bid"];
    // Retrieve auction ID from GET parameter
    $auctionId = $_GET['auctionId'];

    // Prepare and execute SQL query to insert bid into 'bids' table
    $insertBid = $connection->prepare('INSERT INTO bids(bid_amount, auction_id) VALUES(:amount, :auction_id)');
    $insertBid->execute([':amount' => $bidAmount, ':auction_id' => $auctionId]);

    // Redirect back to previous page after successful bid insertion
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
