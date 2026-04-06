<?php
require_once dirname(__DIR__) . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : die('Customer ID required');

// Delete the customer
$query = "DELETE FROM customers WHERE customer_id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);

if ($stmt->execute()) {
    // Redirect back to index page
    header("Location: index.php");
    exit();
} else {
    // If delete fails, still redirect but with error message in session
    session_start();
    $_SESSION['error'] = "Error deleting customer";
    header("Location: index.php");
    exit();
}
?>