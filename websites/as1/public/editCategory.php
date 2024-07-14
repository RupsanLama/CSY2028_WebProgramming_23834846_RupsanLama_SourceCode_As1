<?php
// Include the database connection file
require 'dbconnection.php';

// Retrieve the category ID from the URL parameter
$categoryID = $_GET['old_id'];

// Fetch the category data based on the category ID
$getCategory = $connection->prepare('SELECT * FROM categories WHERE category_id = :category_id');
$getCategory->execute([':category_id' => $categoryID]);
$categoryData = $getCategory->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve new category name from the form
    $newCategoryName = $_POST["categoryName"];

    // Check if the new category name already exists in other categories
    $checkCategoryName = $connection->prepare('SELECT COUNT(*) AS count FROM categories WHERE category_name = :category_name AND category_id != :category_id');
    $checkCategoryName->execute([':category_name' => $newCategoryName, ':category_id' => $categoryID]);
    $result = $checkCategoryName->fetch(PDO::FETCH_ASSOC);

    // If the category name already exists, display an error message
    if ($result['count'] > 0) {
        echo "Category name already exists!";
    } else {
        // Update the category name in the database
        $updateCategoryName = $connection->prepare('UPDATE categories SET category_name = :new_name WHERE category_id = :category_id');
        $updateCategoryName->execute([':new_name' => $newCategoryName, ':category_id' => $categoryID]);

        // Redirect back to the adminCategories.php page after successful update
        header("Location: adminCategories.php");
        exit(); // Ensure no further code execution after redirection
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link rel="stylesheet" href="carbuy.css">
</head>

<body>
    <div class="main">
        <div class="category_form">
            <h2>Edit Category</h2>
            <form action="editCategory.php?old_id=<?php echo urlencode($categoryID)?>" method="post">
                <label for="categoryName">Category Name:</label><br>
                <input type="text" name="categoryName" value="<?php echo htmlspecialchars($categoryData['category_name']) ?>" required><br>
                <div class="btn" style="justify-content:space-evenly">
                    <button type="submit" class="login_btn">Save</button>
                    <a href="adminCategories.php" class="login_btn" style="text-decoration:none; text-align:center">Cancel</a>
                </div> 
            </form>
        </div>
    </div>

</body>
</html>
