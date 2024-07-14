<?php
require 'dbconnection.php';

// Validate auction_id from GET parameter
if (!isset($_GET['auction_id'])) {
    die("Auction ID not provided.");
}

$auctionId = $_GET['auction_id'];

try {
    // Begin transaction for atomic operations
    $connection->beginTransaction();

    // Delete bids associated with the auction
    $deleteBid = $connection->prepare('DELETE FROM bids WHERE auction_id = :auction_id');
    $deleteBid->execute([':auction_id' => $auctionId]);

    // Delete reviews associated with the auction
    $deleteReview = $connection->prepare('DELETE FROM review WHERE auction_id = :auction_id');
    $deleteReview->execute([':auction_id' => $auctionId]);

    // Delete the auction itself
    $deleteAuction = $connection->prepare('DELETE FROM auctions WHERE auction_id = :auction_id');
    $deleteAuction->execute([':auction_id' => $auctionId]);

    // Commit transaction
    $connection->commit();

    // Redirect to yourAuction.php after successful deletion
    header("Location: yourAuction.php?delete_success=1");
    exit();

} catch (PDOException $e) {
    // Rollback the transaction on error
    $connection->rollBack();
    die("Error deleting auction: " . $e->getMessage());
}
?>
