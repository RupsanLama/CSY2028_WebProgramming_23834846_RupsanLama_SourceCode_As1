<?php
$servername = 'mysql';
$username = 'student';
$password = 'student';
$databasename = 'ijdb';

try {
    $connection = new PDO('mysql:dbname='.$databasename.';host='.$servername, $username, $password);
    // Set PDO to throw exceptions on error
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    die('Connection failed: ' . $e->getMessage());
}
?>
