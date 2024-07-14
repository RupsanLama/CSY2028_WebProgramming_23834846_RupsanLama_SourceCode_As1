<?php
session_start();
require 'dbConnection.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get review description from POST data
    $reviewDescription = $_POST['reviewtext'];
    // Get auction ID from query parameters
    $auctionId = $_GET['auction_id'];

    // Insert review into the database
    $insertReview = $connection->prepare('INSERT INTO review (review_description, auction_id, user_id, posted_date) VALUES (:review_description, :auction_id, :user_id, NOW())');
    
    // Bind parameters and execute the query
    $insertReview->execute([
        ':review_description' => $reviewDescription,
        ':auction_id' => $auctionId,
        ':user_id' => $_SESSION['userId'] // Assuming 'userId' is stored in session
    ]);

    // Optional: Handle errors or provide feedback to the user

    // Redirect back to the previous page after successful submission
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    // If the form was not submitted via POST, handle accordingly (optional)
    // Redirect or display an error message
    header("Location: index.php"); // Redirect to index.php or another page
    exit();
}
?>
