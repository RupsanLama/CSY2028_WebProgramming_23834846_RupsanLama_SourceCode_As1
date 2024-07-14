<?php
session_start();
require 'dbconnection.php';

// Pull all administrators from the database
$allAdmins = $connection->prepare('SELECT * FROM administrators');
$allAdmins->execute();
$adminList = $allAdmins->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins</title>
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
        <li><a href="">Manage Admin</a></li>
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

<div class="category_header">
    <h2>Admins</h2>
    <a href="addAdmin.php" class="add_category login_btn" style="width:auto;">Add Admin</a>
</div>

<?php 
// Display each admin with edit and delete options
foreach ($adminList as $admin):
?>
<div class="admin_categories">
    <img src="banners/car.png" alt="category.png" width="150px" height="150px" style="grid-row: 1/3;">
    <p><?php echo htmlspecialchars($admin['admin_name']); ?></p>
    <a href="editAdmin.php?adminId=<?php echo urlencode($admin['admin_id']); ?>" class="login_btn" style="width: 18rem;">Edit</a>
    <a href="deleteAdmin.php?delete_id=<?php echo urlencode($admin['admin_id']); ?>" class="login_btn" style="width: 18rem;">Delete</a>
</div>
<?php
endforeach;
?>

<footer>
    &copy; Carbuy 2024
</footer>

</body>
</html>
