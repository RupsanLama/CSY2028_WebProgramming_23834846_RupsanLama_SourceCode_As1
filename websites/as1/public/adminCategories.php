<?php
session_start();
require 'dbconnection.php';

// Step 1: Retrieve all categories from the database
$getCategories = $connection->prepare('SELECT * FROM categories');
$getCategories->execute();
$categoryList = $getCategories->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>
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
        // Display profile link based on session (user or admin)
        if(isset($_SESSION['login']) && isset($_SESSION['user'])){
            echo '<span class="profile" onmouseenter="displayUserAuction()" onmouseleave="hideUserAuction()">';
            echo '<img src="banners/profile.png" alt="profile">';
            echo '</span>';
        } elseif(isset($_SESSION['login']) && isset($_SESSION['admin'])) {
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
            // Display up to 7 categories in the main navigation
            $categoryNum = 0;
            foreach($categoryList as $cat):
                echo '<li><a class="categoryLink" href="category.php?name='.$cat['category_name'].'&id='.$cat['category_id'].'">'.$cat['category_name'].'</a></li>';
                $categoryNum++;
                if ($categoryNum == 7) {
                    break; // Limit to 7 categories
                }
            endforeach;
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
        // Display all categories in the "More" section
        if($categoryList){
            foreach($categoryList as $cat){
                echo '<a href="category.php?name='.$cat['category_name'].'&id='.$cat['category_id'].'">'.$cat['category_name'].'</a>';
            }
        } else {
            echo 'No more category';
        }
        ?>
    </div>

    <div class="category_header">
        <h2>Categories</h2>
        <a href="addCategory.php" class="add_category login_btn" style="width:auto;">Add Category</a>
    </div>

    <?php 
    // Display each category with edit and delete links
    foreach ($categoryList as $cat):
        echo '<div class="admin_categories">';
        echo '<img src="banners/car.png" alt="category.png" width="150px" height="150px" style="grid-row: 1/3;">';
        echo '<p>'.$cat['category_name'].'</p>';
        echo '<a href="editCategory.php?old_id='.urlencode($cat['category_id']).'" class="login_btn" style="width: 18rem;">Edit</a>';
        echo '<a href="deleteCategory.php?delete_id='.urlencode($cat['category_id']).'" class="login_btn" style="width: 18rem;">Delete</a>';
        echo '</div>';
    endforeach; 
    ?>

    <footer>
        &copy; Carbuy 2024
    </footer>
</body>
</html>
