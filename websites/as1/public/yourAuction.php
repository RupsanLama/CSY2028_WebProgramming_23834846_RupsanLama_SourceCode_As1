<?php
session_start();
require 'dbconnection.php';

// Fetch all auctions for the logged-in user
$allAuctions = $connection->prepare('SELECT auctions.*, categories.category_name 
                                    FROM auctions 
                                    INNER JOIN categories ON auctions.category_id = categories.category_id'); // Ensure you have ORDER BY or additional filtering if needed
$allAuctions->execute();
$auctionList = $allAuctions->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Auctions</title>
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
            // Display categories in the navigation bar
            $allCategories = $connection->prepare('SELECT * FROM categories');
            $allCategories->execute();
            $categoryList = $allCategories->fetchAll(PDO::FETCH_ASSOC);
            $categoryNum = 0;
            foreach ($categoryList as $category) {
                echo '<li><a class="categoryLink" href="category.php?name=' . $category['category_name'] . '&id=' . $category['category_id'] . '">' . $category['category_name'] . '</a></li>';
                $categoryNum++;
                if ($categoryNum == 7) {
                    break; // Exit the loop after a certain number of categories
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
        if ($categoryList) {
            foreach ($categoryList as $category) {
                echo '<a href="category.php?name=' . $category['category_name'] . '&id=' . $category['category_id'] . '">' . $category['category_name'] . '</a>';
            }
        } else {
            echo 'No more category';
        }
        ?>
    </div>

    <div class="category_header">
        <h2>Your Auctions</h2>
        <a href="addAuction.php" class="add_category login_btn" style="width:auto;">Add Auction</a>
    </div>

    <div class="auction_main">
        <ul>
            <?php
            foreach ($auctionList as $auction) {
                // Fetch the highest bid amount for each auction
                $highestBid = $connection->prepare("SELECT MAX(bid_amount) AS highest_bid FROM bids WHERE auction_id = :auction_id");
                $highestBid->execute([':auction_id' => $auction['auction_id']]);
                $result = $highestBid->fetch(PDO::FETCH_ASSOC);

                echo '<li>';
                echo '<img src="banners/car.png" width="200px" height="200px" alt="car name">';
                echo '<article>';
                echo '<h2>' . $auction['title'] . '</h2>';
                echo '<h3>' . $auction['category_name'] . '</h3>';
                echo '<p>' . $auction['description'] . '</p>';
                if ($result['highest_bid'] !== null) {
                    echo '<p class="price">Current bid: £' . $result['highest_bid'] . '</p>';
                } else {
                    echo '<p class="price">Current bid: £0</p>';
                }
                echo '<div class="auction_btns">';
                echo '<a href="carPage.php?id=' . $auction['auction_id'] . '">More</a>';
                echo '<a href="editAuction.php?old_title=' . urlencode($auction['title']) . '&old_category=' . urlencode($auction['category_name']) . '&old_desc=' . urlencode($auction['description']) . '&old_date=' . urlencode($auction['end_date']) . '&auction_id=' . urlencode($auction['auction_id']) . '">Edit</a>';
                echo '<a href="deleteAuction.php?auction_id=' . urlencode($auction['auction_id']) . '">Delete</a>';
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
