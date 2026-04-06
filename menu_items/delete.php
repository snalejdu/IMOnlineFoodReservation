<?php
require_once dirname(__DIR__) . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : die('Item ID required');

// Delete the menu item
$query = "DELETE FROM menu_items WHERE item_id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);

if ($stmt->execute()) {
    // Redirect back to index page
    header("Location: index.php");
    exit();
} else {
    // If delete fails, still redirect but with error message in session
    session_start();
    $_SESSION['error'] = "Error deleting menu item";
    header("Location: index.php");
    exit();
}
?>