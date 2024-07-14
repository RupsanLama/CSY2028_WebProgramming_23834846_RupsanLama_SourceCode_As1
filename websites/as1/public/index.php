<?php
// Include the database connection file
require 'dbConnection.php';

// Start the session
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Carbuy Auctions</title>
    <link rel="stylesheet" href="carbuy.css" />
    <script src="carbuy.js"></script>
</head>

<body>
    <header>
        <h1><a href=""><span class="C">C</span>
            <span class="a">a</span>
            <span class="r">r</span>
            <span class="b">b</span>
            <span class="u">u</span>
            <span class="y">y</span></a>
        </h1>
        
        <!-- Search form -->
        <form action="#" class="form-flex">
            <input type="text" name="search" placeholder="Search for a car" />
            <input type="submit" name="submit" value="Search" />
        </form>
        
        <?php
        // Check if the user is logged in and is a regular user
        if(isset($_SESSION['login']) && isset($_SESSION['user'])){
            echo '<span class="profile" onmouseenter="displayUserAuction()" onmouseleave="hideUserAuction()">';
            echo '<img src="banners/profile.png" alt="profile">';
            echo '</span>';
        }
        // Check if the user is logged in and is an admin
        else if(isset($_SESSION['login']) && isset($_SESSION['admin'])) {
            echo '<span class="profile" onmouseenter="displayAdminCategory()" onmouseleave="hideAdminCategory()">';
            echo '<img src="banners/profile.png" alt="profile">';
            echo '</span>';
        }
        // If the user is not logged in, show the login link
        else{
            echo '<a class="text-size login" href="login.php">Login</a>';
        }
        ?>
    </header>

    <nav>
        <ul>
            <?php
                // Pull all the list of categories from the categories database
                $selectCategories = $connection->prepare('SELECT * FROM categories');
                $selectCategories->execute();
                $categoryList = $selectCategories->fetchAll(PDO::FETCH_ASSOC);
                $categoryCounter = 0;
                foreach($categoryList as $category):
                    echo '<li><a class="categoryLink" href="category.php?name='.$category['category_name'].'&id='.$category['category_id'].'">'.$category['category_name'].'</a></li>';
                    $categoryCounter++;
                    if ($categoryCounter == 7) {
                        break; // Exit the loop after 7 categories
                    }
                endforeach;
            ?>
            <li><a class="more auctionLink" onmouseenter="displayCategory()" onmouseleave="hideCategory()">More</a></li>
        </ul>
    </nav>
    <img src="banners/1.jpg" alt="Banner" height="200px" />

    <!-- User Auction Menu -->
    <div id="userAuction" class="userAuction" onmouseenter="displayUserAuction()" onmouseleave="hideUserAuction()">
        <ul>
            <li><a href="yourAuction.php">Your Auctions</a></li>
            <li><a href="addAuction.php">Add Auction</a></li>
            <li><a href="logout.php">Log out</a></li>
        </ul>
    </div>

    <!-- Admin Category Menu -->
    <div id="adminCategory" class="userAuction" onmouseenter="displayAdminCategory()" onmouseleave="hideAdminCategory()">
        <ul>
            <li><a href="adminCategories.php">Categories</a></li>
            <li><a href="manageAdmin.php">Manage Admin</a></li>
            <li><a href="logout.php">Log out</a></li>
        </ul>
    </div>
    
    <!-- More Categories Menu -->
    <div id="moreCategory" class="moreCategory" onmouseenter="displayCategory()" onmouseleave="hideCategory()">
            <?php
                if($categoryList){
                    foreach($categoryList as $category):
                        echo '<a href="category.php?name='.$category['category_name'].'&id='.$category['category_id'].'">'.$category['category_name'].'</a>';
                    endforeach;
                }
                else{
                    echo 'No more category';
                }
            ?>
    </div>

    <main>
        <h1>Latest Car Auctions</h1>
        <ul class="carList">
            <?php
                // Pull 10 most recently added auctions from auctions table
                $selectAuctions = $connection->prepare('SELECT * FROM auctions ORDER BY created_date DESC LIMIT 10');
                $selectAuctions->execute();
                $recentAuctions = $selectAuctions->fetchAll(PDO::FETCH_ASSOC);
                if($recentAuctions){
                    foreach($recentAuctions as $auction):
                        // Pull the data from categories table where category_id is equal to $auction['category_id']
                        $category = $connection->prepare('SELECT * FROM categories WHERE category_id = :category_id');
                        $category->execute([':category_id' => $auction['category_id']]);
                        $categoryData = $category->fetch(PDO::FETCH_ASSOC);

                        // Pull the highest bid amount from the bids table
                        $highestBid = $connection->prepare("SELECT MAX(bid_amount) AS highest_bid FROM bids WHERE auction_id = :auction_id");
                        $highestBid->execute([':auction_id' => $auction['auction_id']]);
                        $bidData = $highestBid->fetch(PDO::FETCH_ASSOC);

                        // Display the auction details
                        echo '<li>';
                        echo '<img src="banners/car.png" alt="car name">';
                        echo '<article>';
                        echo '<h2>'.$auction['title'].'</h2>';
                        echo '<h3>'.$categoryData['category_name'].'</h3>';
                        echo '<p>'.$auction['description'].'</p>';
                        if ($bidData['highest_bid'] !== null) {
                            echo '<p class="price">Current bid: £'.$bidData['highest_bid'].'</p>';
                        } else {
                            echo '<p class="price">Current bid: £0</p>';
                        }
                        echo '<div class="auction_btns">';
                            echo '<a href="carPage.php?id='.$auction['auction_id'].'" class="auctionLink">More</a>';
                        echo '</div>';
                        echo '</article>';
                        echo '</li>';
                    endforeach;
                }
                else{
                    echo '<p>No auctions available</p>';
                }
            ?>
        </ul>
        
        <footer>
            &copy; Carbuy 2024
        </footer>
    </main>
    
</body>

</html>
