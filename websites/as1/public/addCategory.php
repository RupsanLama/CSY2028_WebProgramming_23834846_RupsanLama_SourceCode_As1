<?php
require 'dbconnection.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoryName = $_POST["name"];

    try {
        // Check if category name already exists
        $checkCategoryName = $connection->prepare('SELECT COUNT(*) AS count FROM categories WHERE category_name = :category_name');
        $checkCategoryName->execute([':category_name' => $categoryName]);
        $result = $checkCategoryName->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            $message = "Category already exists.";
        } else {
            // Insert new category into the database
            $insertData = $connection->prepare('INSERT INTO categories(category_name) VALUES(:category_name)');
            $insertData->execute([':category_name' => $categoryName]);
            header("Location: adminCategories.php");
            exit();
        }
    } catch (PDOException $e) {
        // Handle database errors
        die("Error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="carbuy.css">
</head>

<body>
    <div class="main">
        <div class="category_form">
            <h2>Add Category</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="mt-3">
                <label for="categoryName">Category Name:</label><br>
                <input type="text" name="name" required><br>
                <?php
                if (!empty($message)) {
                    echo '<div class="message">';
                    echo '<p>' . htmlspecialchars($message) . '</p>';
                    echo '</div>';
                }
                ?>
                <div class="btn">
                    <button type="submit" class="login_btn">ADD</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
