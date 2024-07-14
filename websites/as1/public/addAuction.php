<?php
session_start();
require 'dbconnection.php';

// Pull all the list of categories from the categories database
$allCategories = $connection->prepare('SELECT * FROM categories');
$allCategories->execute();
$categoryList = $allCategories->fetchAll(PDO::FETCH_ASSOC);

// Insert data into database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token");
    }

    // Sanitize input data
    $auctionTitle = htmlspecialchars($_POST["title"]);
    $auctionCategory = (int)$_POST["category"]; // Ensure integer value for security
    $auctionDesc = htmlspecialchars($_POST["description"]);
    $endDate = $_POST["endDate"];
    $userId = $_SESSION['userId'];

    // Insert data into auctions table
    $insertData = $connection->prepare('INSERT INTO auctions(title, description, end_Date, created_date, category_id, user_id) VALUES(:title, :description, :endDate, CURRENT_TIMESTAMP, :category, :user)');
    $insertData->execute([':title' => $auctionTitle, ':description' => $auctionDesc, ':endDate' => $endDate, ':category' => $auctionCategory, ':user' => $userId]);

    // Redirect to yourAuction.php after successful insertion
    header("Location: yourAuction.php");
    exit();
}

// Generate CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Auction</title>
    <link rel="stylesheet" href="carbuy.css">
</head>
<body>
    <div class="main">
        <div class="auction_form">
            <h2>Add Auction</h2>
            <form action="addAuction.php" method="post">
                <!-- CSRF token -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <label for="title">Title:</label><br>
                <input type="text" name="title" class="form_input" required><br>
                
                <label for="categories">Category:</label>
                <select name="category" class="form_input" style="width: fit-content;">
                    <?php foreach ($categoryList as $category): ?>
                        <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                    <?php endforeach; ?>
                </select><br>
                
                <label for="description">Description:</label><br>
                <textarea name="description" class="form_input" style="resize: none; height: 8rem;" required></textarea><br>
                
                <label for="endDate">End Date:</label>
                <input type="date" name="endDate" class="form_input" style="width: fit-content;" required><br>
                
                <div class="btn">
                    <button type="submit" class="login_btn">ADD</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
