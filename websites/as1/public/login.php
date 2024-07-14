<?php
// Start the session
session_start();

// Include the database connection file
require 'dbconnection.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the email and password from the POST request
    $user_email = $_POST["email"];
    $user_password = $_POST["password"];

    // Check if the admin exists in the administrators table
    $check_admin = $connection->prepare('SELECT * FROM administrators WHERE email = :email');
    $check_admin->execute([':email' => $user_email]);
    $result_admin = $check_admin->fetch(PDO::FETCH_ASSOC);

    // Check if the user exists in the users table
    $check_user = $connection->prepare('SELECT * FROM users WHERE email = :email');
    $check_user->execute([':email' => $user_email]);
    $result_user = $check_user->fetch(PDO::FETCH_ASSOC);

    // Verify the password for admin
    if ($result_admin && password_verify($user_password, $result_admin['password'])) {
        // Set session variables for admin
        $_SESSION['admin'] = "yes";
        $_SESSION['login'] = "yes";
        unset($_SESSION['user']);
        // Redirect to the index page
        header("Location: index.php");
        exit();
    }
    // Verify the password for user
    else if ($result_user && password_verify($user_password, $result_user['password'])) {
        // Set session variables for user
        $_SESSION['userId'] = $result_user['user_id'];
        $_SESSION['login'] = "yes";
        $_SESSION['user'] = "yes";
        unset($_SESSION['admin']);
        // Redirect to the index page
        header("Location: index.php");
        exit();
    }
    else {
        // User does not exist or credentials are incorrect
        $message = "Invalid email or password.";
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
    <title>Login</title>
</head>

<body>
    <div class="main">
        <div class="login_right">
            <div class="header">
                <h2 id="heading" class="login_header">Login</h2>
            </div>
            <div class="login_form" style="margin-top:3.25rem">
                <!-- Login form -->
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <label for="email">Email</label><br>
                    <input type="email" name="email" required>
                    <label for="password">Password</label><br>
                    <input type="password" name="password" required><br>
                    <?php
                    // Check if there is a message to display
                    if(!empty($message)){
                        echo '<div class="message">';
                        echo '<p style="font-size:20px;">Invalid email or password.</p>';
                        echo '</div>';
                    }
                    ?>
                    <div class="btn">
                        <button type="submit" id="login-btn" class="login_btn">Login</button>
                    </div>
                </form>
            </div>
            <div class="register_message">
                <p id="registerLogin">Don't have an account?</p>
                <span id="register" onclick="register()">Register</span>
            </div>
        </div>
    </div>

</body>

</html>
