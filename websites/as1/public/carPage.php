<?php
session_start();
require 'dbConnection.php';

$auctionId = $_GET['id'];

// Retrieve auction details
$auction = $connection->prepare('SELECT * FROM auctions WHERE auction_id = :auction_id');
$auction->execute([':auction_id' => $auctionId]);
$auctionData = $auction->fetch(PDO::FETCH_ASSOC);

// Convert database date to PHP DateTime format for calculating time difference
$date = new DateTime($auctionData['end_date']);
$currentDate = new DateTime();
$differenceDate = $currentDate->diff($date);

// Retrieve category details
$category = $connection->prepare('SELECT * FROM categories WHERE category_id = :category_id');
$category->execute([':category_id' => $auctionData['category_id']]);
$categoryData = $category->fetch(PDO::FETCH_ASSOC);

// Retrieve auction creator's details
$user = $connection->prepare('SELECT * FROM users WHERE user_id = :user_id');
$user->execute([':user_id' => $auctionData['user_id']]);
$userData = $user->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car page</title>
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
                echo '<li><a class="categoryLink" href="category.php?name=' . $category['category_name'] . '&id=' . $category['category_id'] . '">' . $category['category_name'] . '</a></li>';
                $categoryNum++;
                if ($categoryNum == 7) {
                    break; // Exit the loop
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
                echo '<a href="category.php?name=' . $category['category_name'] . '&id=' . $category['category_id'] . '">' . $category['category_name'] . '</a>';
            }
        } else {
            echo 'No more category';
        }
        ?>
    </div>

    <article class="car car_page">
        <img src="banners/car.png" alt="car name">
        <section class="details">
            <h2><?php echo $auctionData['title']; ?></h2>
            <h3><?php echo $categoryData['category_name']; ?></h3>
            <p>Auction created by <a href="#"><?php echo $userData['username']; ?></a></p>
            <p class="price">Current bid: £
                <?php
                // Display highest bid amount
                $highestBid = $connection->prepare("SELECT MAX(bid_amount) AS highest_bid FROM bids WHERE auction_id = :auction_id");
                $highestBid->execute([':auction_id' => $auctionId]);
                $result = $highestBid->fetch(PDO::FETCH_ASSOC);

                if ($result['highest_bid'] !== null) {
                    echo $result['highest_bid'];
                } else {
                    echo "0";
                }
                ?>
            </p>
            <time>Time left: <?php
                            // Display time left for the auction
                            if ($differenceDate->days > 0) {
                                echo $differenceDate->format("%a days %H hours %I minutes");
                            } else {
                                echo $differenceDate->format("%H hours %I minutes");
                            }
                            ?>
            </time>
            <?php
            // Display bid form if user is logged in
            if (isset($_SESSION['login']) && isset($_SESSION['user'])) {
                echo '<form action="bid.php?auctionId=' . $auctionId . '" class="bid" method="post">';
                echo '<input type="text" name="bid" placeholder="Enter bid amount" class="form_input" required/>';
                echo '<input type="submit" value="Place bid" class="login_btn" style="width:fit-content;margin-left:2rem;" />';
                echo '</form>';
            }
            ?>
        </section>
        <section class="description">
            <p><?php echo $auctionData['description']; ?></p>
        </section>

        <section class="reviews">
            <h2>Reviews of <?php echo $userData['username']; ?>.</h2>
            <ul>
                <?php
                // Display reviews for the auction
                $review = $connection->prepare('SELECT * FROM review WHERE auction_id = :auction_id');
                $review->execute([':auction_id' => $auctionId]);
                $reviewData = $review->fetchAll(PDO::FETCH_ASSOC);

                if ($reviewData) {
                    foreach ($reviewData as $reviewSingle) {
                        $userReview = $connection->prepare('SELECT * FROM users WHERE user_id = :user_id');
                        $userReview->execute([':user_id' => $reviewSingle['user_id']]);
                        $userData = $userReview->fetch(PDO::FETCH_ASSOC);

                        echo '<li><strong>' . $userData['username'] . ' said</strong> ' . $reviewSingle['review_description'] . ' <em>' . $reviewSingle['posted_date'] . '</em></li>';
                    }
                } else {
                    echo '<li>No review yet.</li>';
                }
                ?>
            </ul>
            <?php
            // Display review form if user is logged in
            if (isset($_SESSION['login']) && isset($_SESSION['user'])) {
                echo '<form action="review.php?auction_id=' . $auctionId . '" method="post">';
                echo '<label>Add your review</label>';
                echo '<textarea name="reviewtext" class="form_input" style="resize: none; width:55rem; height: 6rem;" required ></textarea>';
                echo '<input type="submit" name="submit" value="Add Review" class="login_btn" style="width:fit-content;" />';
                echo '</form>';
            }
            ?>
        </section>
    </article>

    <footer>
        &copy; Carbuy 2024
    </footer>
</body>

</html>
