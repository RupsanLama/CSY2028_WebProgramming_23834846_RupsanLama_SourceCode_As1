<?php
require 'dbconnection.php';

// Ensure auction_id is provided via GET
if (!isset($_GET['auction_id'])) {
    die("Auction ID not provided.");
}

$auctionId = $_GET['auction_id'];

// Fetch existing auction details from GET parameters
$oldAuctionTitle = $_GET['old_title'] ?? '';
$oldAuctionCategory = $_GET['old_category'] ?? '';
$oldAuctionDesc = $_GET['old_desc'] ?? '';
$oldAuctionDate = $_GET['old_date'] ?? '';

// Fetch all categories from the database
$allCategories = $connection->prepare('SELECT * FROM categories');
$allCategories->execute();
$categoryList = $allCategories->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs
    $newAuctionTitle = htmlspecialchars($_POST['title']);
    $newAuctionCategory = (int)$_POST['category']; // Ensure integer value for security
    $newAuctionDesc = htmlspecialchars($_POST['description']);
    $newAuctionDate = $_POST['endDate'];

    // Update auction in the database
    $updateAuction = $connection->prepare('UPDATE auctions SET title = :new_title, description = :new_desc, end_date = :new_date, category_id = :new_category WHERE auction_id = :auction_id');
    $updateAuction->execute([
        ':new_title' => $newAuctionTitle,
        ':new_desc' => $newAuctionDesc,
        ':new_date' => $newAuctionDate,
        ':new_category' => $newAuctionCategory,
        ':auction_id' => $auctionId
    ]);

    // Redirect to yourAuction.php after successful update
    header("Location: yourAuction.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Auction</title>
    <link rel="stylesheet" href="carbuy.css">
</head>
<body>
    <div class="main">
        <div class="auction_form">
            <h2>Edit Auction</h2>
            <form action="editAuction.php?auction_id=<?php echo urlencode($auctionId) ?>" method="post">
                <label for="title">Title:</label><br>
                <input type="text" name="title" value="<?php echo htmlspecialchars($oldAuctionTitle) ?>" class="form_input"><br>
                
                <label for="categories">Category:</label>
                <select name="category" class="form_input" style="width: fit-content;">
                    <?php foreach ($categoryList as $category): ?>
                        <?php $categoryId = $category['category_id']; ?>
                        <option value="<?php echo $categoryId; ?>" <?php if ($categoryId == $oldAuctionCategory) echo 'selected'; ?>><?php echo htmlspecialchars($category['category_name']); ?></option>
                    <?php endforeach; ?>
                </select><br>
                
                <label for="description">Description:</label><br>
                <textarea name="description" class="form_input" style="resize: none; height: 8rem;"><?php echo htmlspecialchars($oldAuctionDesc) ?></textarea><br>
                
                <label for="endDate">End Date:</label>
                <input type="date" name="endDate" value="<?php echo htmlspecialchars($oldAuctionDate) ?>" class="form_input" style="width: fit-content;"><br>
                
                <div class="btn" style="justify-content: space-evenly;">
                    <button type="submit" class="login_btn">Save</button>
                    <a href="yourAuction.php" class="login_btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
