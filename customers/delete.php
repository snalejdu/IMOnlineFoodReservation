<?php
require_once dirname(__DIR__) . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['id']) || empty($_POST['id'])) {
        die("Invalid request.");
    }

    $id = intval($_POST['id']);

    $database = new Database();
    $db = $database->getConnection();

    $query = "DELETE FROM customers WHERE customer_id = :id";
    $stmt = $db->prepare($query);

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: index.php?success=deleted");
        exit;
    } else {
        echo "Failed to delete.";
    }
} else {
    echo "Invalid request method.";
}