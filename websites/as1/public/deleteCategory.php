<?php
// Include the database connection file
require 'dbconnection.php';

// Retrieve the category ID from the URL parameter
$categoryID = $_GET['delete_id'];

// Step 1: Delete associated bids and reviews for auctions in this category
// Fetch auctions associated with the category
$getAuctions = $connection->prepare('SELECT * FROM auctions WHERE category_id = :category_id');
$getAuctions->execute([':category_id' => $categoryID]);
$auctionData = $getAuctions->fetchAll(PDO::FETCH_ASSOC);

// Iterate through each auction to delete bids and reviews
foreach ($auctionData as $auction) {
    // Delete bids for the current auction
    $deleteBid = $connection->prepare('DELETE FROM bids WHERE auction_id = :auction_id');
    $deleteBid->execute([':auction_id' => $auction['auction_id']]);

    // Delete reviews for the current auction
    $deleteReview = $connection->prepare('DELETE FROM review WHERE auction_id = :auction_id');
    $deleteReview->execute([':auction_id' => $auction['auction_id']]);
}

// Step 2: Delete auctions in this category
// Delete auctions from the auctions table
$deleteAuctions = $connection->prepare('DELETE FROM auctions WHERE category_id = :category_id');
$deleteAuctions->execute([':category_id' => $categoryID]);

// Step 3: Delete the category itself
// Delete the category from the categories table
$deleteCategory = $connection->prepare('DELETE FROM categories WHERE category_id = :category_id');
$deleteCategory->execute([':category_id' => $categoryID]);

// Redirect back to the adminCategories.php page after deletion
header("Location: adminCategories.php");
exit(); // Ensure no further code execution after redirection
?>
