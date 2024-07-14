<?php
require 'dbconnection.php';

// Validate adminId from GET parameter
if (!isset($_GET['delete_id']) || !is_numeric($_GET['delete_id'])) {
    die("Invalid admin ID.");
}

$adminId = $_GET['delete_id'];

try {
    // Prepare and execute the delete query
    $deleteAdmin = $connection->prepare('DELETE FROM administrators WHERE admin_id = :adminId');
    $deleteAdmin->execute([':adminId' => $adminId]);

    // Redirect to manageAdmin.php after successful deletion
    header("Location: manageAdmin.php?delete_success=1");
    exit();

} catch (PDOException $e) {
    // Handle database errors
    die("Error deleting admin: " . $e->getMessage());
}
?>
