<?php
require 'dbconnection.php';

// Ensure adminId is provided via GET
if (!isset($_GET['adminId'])) {
    die("Admin ID not provided.");
}

$adminId = $_GET['adminId'];

// Fetch existing admin details from the database
$getAdmin = $connection->prepare('SELECT * FROM administrators WHERE admin_id = :adminId');
$getAdmin->execute([':adminId' => $adminId]);
$resultAdmin = $getAdmin->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $useremail = htmlspecialchars($_POST["email"]);
    $userpassword = htmlspecialchars($_POST["password"]);
    $username = htmlspecialchars($_POST["username"]);

    // Check if the new username or email already exists in administrators table
    $checkUsername = $connection->prepare('SELECT COUNT(*) AS count FROM administrators WHERE admin_name = :username AND admin_id != :adminId');
    $checkUsername->execute([':username' => $username, ':adminId' => $adminId]);
    $resultName = $checkUsername->fetch(PDO::FETCH_ASSOC);

    $checkEmail = $connection->prepare('SELECT COUNT(*) AS count FROM administrators WHERE email = :email AND admin_id != :adminId');
    $checkEmail->execute([':email' => $useremail, ':adminId' => $adminId]);
    $resultEmail = $checkEmail->fetch(PDO::FETCH_ASSOC);

    

    // If the username or email already exists, display an error message
    if ($resultName['count'] > 0 /* || $UserResultName['count'] > 0 */) {
        $message = "Username already exists.";
    } elseif ($resultEmail['count'] > 0 /* || $UserResultEmail['count'] > 0 */) {
        $message = "Email already exists.";
    } else {
        // Update the administrator's information if no duplicate username or email found
        $updatedata = $connection->prepare('UPDATE administrators SET admin_name = :admin_name, email = :email, password = :password WHERE admin_id= :admin_id');
        $updatedata->execute([':admin_name' => $username, ':email' => $useremail, ':password' => $userpassword, ':admin_id' => $adminId]);
        header("Location: manageAdmin.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="carbuy.css">
    <script src="carbuy.js"></script>
    <title>Edit Admin</title>
</head>
<body>
    <div class="main">
        <div class="login_right">
            <div class="header">
                <h2 id="heading" class="login_header">Edit Admin</h2>
            </div>
            <div class="login_form register_form">
                <form action="editAdmin.php?adminId=<?php echo $adminId ?>" method="post">
                    <label for="email">Email:</label><br>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($resultAdmin['email']) ?>" required><br>
                    
                    <label for="password">Password:</label><br>
                    <input type="password" name="password" value="<?php echo htmlspecialchars($resultAdmin['password']) ?>" required><br>
                    
                    <label for="username">Username:</label><br>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($resultAdmin['admin_name']) ?>" required><br>
                    
                    <?php if (!empty($message)): ?>
                        <div class="message">
                            <p style="font-size: 20px;"><?php echo $message; ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="btn" style="justify-content: space-evenly;">
                        <button type="submit" class="login_btn">Save</button>
                        <a href="manageAdmin.php" class="login_btn">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
