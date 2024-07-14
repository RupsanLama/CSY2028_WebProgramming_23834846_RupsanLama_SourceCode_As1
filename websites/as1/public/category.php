<?php
require 'dbConnection.php';
session_start();

// Retrieve category name and ID from query parameters
$categoryName = $_GET['name'];
$categoryId = $_GET['id'];

// Pull all auctions belonging to the specified category
$allAuctions = $connection->prepare('SELECT * FROM auctions WHERE category_id = :category_id');
$allAuctions->execute([':category_id' => $categoryId]);
$auctionList = $allAuctions->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($categoryName); ?> Category</title>
    <link rel="stylesheet" href="carbuy.css">
    <script src="carbuy.js"></script>
</head>
<body>
<header>
    <h1><a href="index.php"><span class="C">C</span>
        <span class="a">a</span>
        <span class="r">r</span>
        <span class="b">b</span>
        <span class="u">u</span>
        <span class="y">y</span></a>
    </h1>

    <form action="#" class="form-flex">
        <input type="text" name="search" placeholder="Search for a car" />
        <input type="submit" name="submit" value="Search" />
    </form>

    <?php
    // Display profile link or login link based on session status
    if (isset($_SESSION['login']) && isset($_SESSION['user'])) {
        echo '<span class="profile" onmouseenter="displayUserAuction()" onmouseleave="hideUserAuction()">';
        echo '<img src="banners/profile.png" alt="profile">';
        echo '</span>';
    } else if (isset($_SESSION['login']) && isset($_SESSION['admin'])) {
        echo '<span class="profile" onmouseenter="displayAdminCategory()" onmouseleave="hideAdminCategory()">';
        echo '<img src="banners/profile.png" alt="profile">';
        echo '</span>';
    } else {
        echo '<a class="text-size login" href="login.php">Login</a>';
    }
    ?>
</header>

<nav>
    <ul>
        <?php
        // Display list of categories
        $allCategories = $connection->prepare('SELECT * FROM categories');
        $allCategories->execute();
        $categoryList = $allCategories->fetchAll(PDO::FETCH_ASSOC);
        $categoryNum = 0;
        foreach ($categoryList as $category) {
            echo '<li><a class="categoryLink" href="category.php?name=' . urlencode($category['category_name']) . '&id=' . $category['category_id'] . '">' . htmlspecialchars($category['category_name']) . '</a></li>';
            $categoryNum++;
            if ($categoryNum == 7) {
                break; // Limit the number of categories shown for simplicity
            }
        }
        ?>
        <li><a class="more auctionLink" onmouseenter="displayCategory()" onmouseleave="hideCategory()">More</a></li>
    </ul>
</nav>

<div id="userAuction" class="userAuction" onmouseenter="displayUserAuction()" onmouseleave="hideUserAuction()">
    <ul>
        <li><a href="yourAuction.php">Your Auctions</a></li>
        <li><a href="addAuction.php">Add Auction</a></li>
        <li><a href="logout.php">Log out</a></li>
    </ul>
</div>

<div id="adminCategory" class="userAuction" onmouseenter="displayAdminCategory()" onmouseleave="hideAdminCategory()">
    <ul>
        <li><a href="adminCategories.php">Categories</a></li>
        <li><a href="manageAdmin.php">Manage Admin</a></li>
        <li><a href="logout.php">Log out</a></li>
    </ul>
</div>

<div id="moreCategory" class="moreCategory" onmouseenter="displayCategory()" onmouseleave="hideCategory()">
    <?php
    // Display more categories
    if ($categoryList) {
        foreach ($categoryList as $category) {
            echo '<a href="category.php?name=' . urlencode($category['category_name']) . '&id=' . $category['category_id'] . '">' . htmlspecialchars($category['category_name']) . '</a>';
        }
    } else {
        echo 'No more category';
    }
    ?>
</div>

<div class="auction_main">
    <ul>
        <?php
        // Display each auction in the category
        foreach ($auctionList as $auction) {
            // Retrieve the highest bid amount for the current auction
            $highestBid = $connection->prepare("SELECT MAX(bid_amount) AS highest_bid FROM bids WHERE auction_id = :auction_id");
            $highestBid->execute([':auction_id' => $auction['auction_id']]);
            $result = $highestBid->fetch(PDO::FETCH_ASSOC);

            echo '<li>';
            echo '<img src="banners/car.png" width="200px" height="200px" alt="car name">';
            echo '<article>';
            echo '<h2>' . htmlspecialchars($auction['title']) . '</h2>';
            echo '<h3>' . htmlspecialchars($categoryName) . '</h3>';
            echo '<p>' . htmlspecialchars($auction['description']) . '</p>';
            echo '<p class="price">Current bid: £' . ($result['highest_bid'] !== null ? htmlspecialchars($result['highest_bid']) : '0') . '</p>';
            echo '<div class="auction_btns">';
            echo '<a href="carPage.php?id=' . $auction['auction_id'] . '">More</a>';
            echo '</div>';
            echo '</article>';
            echo '</li>';
        }
        ?>
    </ul>
</div>

<footer>
    &copy; Carbuy 2024
</footer>

</body>
</html>
