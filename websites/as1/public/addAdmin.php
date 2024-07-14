<?php
// Include the database connection file
require 'dbconnection.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the email, password, and username from the POST request
    $admin_email = $_POST["email"];
    $admin_password = $_POST["password"];
    $admin_name = $_POST["username"];

    // Hash the password using the default hashing algorithm
    $password_hash = password_hash($admin_password, PASSWORD_DEFAULT);

    // Check if the username and email already exist in the users table
    $check_username_user = $connection->prepare('SELECT COUNT(*) AS count FROM users WHERE username = :username');
    $check_username_user->execute([':username' => $admin_name]);
    $user_result_name = $check_username_user->fetch(PDO::FETCH_ASSOC);

    $check_email_user = $connection->prepare('SELECT COUNT(*) AS count FROM users WHERE email = :email');
    $check_email_user->execute([':email' => $admin_email]);
    $user_result_email = $check_email_user->fetch(PDO::FETCH_ASSOC);

    // Check if the username and email already exist in the administrators table
    $check_username_admin = $connection->prepare('SELECT COUNT(*) AS count FROM administrators WHERE admin_name = :username');
    $check_username_admin->execute([':username' => $admin_name]);
    $admin_result_name = $check_username_admin->fetch(PDO::FETCH_ASSOC);

    $check_email_admin = $connection->prepare('SELECT COUNT(*) AS count FROM administrators WHERE email = :email');
    $check_email_admin->execute([':email' => $admin_email]);
    $admin_result_email = $check_email_admin->fetch(PDO::FETCH_ASSOC);

    // Check if the username or email already exists
    if ($user_result_name['count'] > 0 || $admin_result_name['count'] > 0) {
        // Username already exists, set the message
        $message = "Username already exists.";
    } else if($user_result_email['count'] > 0 || $admin_result_email['count'] > 0){
        // Email already exists, set the message
        $message = "Email already exists.";
    } else {
        // Username and email are unique, proceed with insertion into the administrators table
        $insert_data = $connection->prepare('INSERT INTO administrators(admin_name, email, password) VALUES(:admin_name, :email, :password)');
        $insert_data->execute([':admin_name' => $admin_name, ':email' => $admin_email, ':password' => $password_hash]);
        // Redirect to the manageAdmin.php page
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
    <title>Add Admin</title>
</head>

<body>
    <div class="main">
        <div class="login_right">
            <div class="header">
                <h2 id="heading" class="login_header">Add Admin</h2>
            </div>
            <div class="login_form register_form">
                <!-- Registration form -->
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <label for="email">Email</label><br>
                    <input type="email" name="email" required>
                    <label for="password">Password</label><br>
                    <input type="password" name="password" required><br>
                    <label for="username">Username</label><br>
                    <input type="text" name="username" required><br>
                    <?php
                    // Check if there is a message to display
                    if (!empty($message)) {
                        echo '<div class="message">';
                        echo '<p style="font-size:20px;">'.$message.'</p>';
                        echo '</div>';
                    } 
                    ?>
                    <div class="btn">
                        <button type="submit" id="login-btn" class="login_btn">Add Admin</button>
                    </div>
                </form>
            </div>
            <div class="register_message" style="margin-top:0.25rem">
                <p id="registerLogin">Already have an account?</p>
                <span id="register" onclick="login()">Login</span>
            </div>
        </div>
    </div>

</body>
</html>
