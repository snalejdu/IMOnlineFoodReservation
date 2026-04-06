<?php
require_once dirname(__DIR__) . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

$id = $_GET['id'];

// Process form submission BEFORE any output
if ($_POST) {
    $query = "UPDATE customers SET customer_name=:name, email=:email, phone=:phone, address=:address WHERE customer_id=:id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':phone', $_POST['phone']);
    $stmt->bindParam(':address', $_POST['address']);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    }
}

// Fetch customer data
$query = "SELECT * FROM customers WHERE customer_id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

// Now include navigation after all logic is complete
include_once dirname(__DIR__) . '/includes/navigation.php';
include_once dirname(__DIR__) . '/includes/breadcrumb.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer - Food Ordering System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 800px; margin: 20px auto; padding: 20px; }
        .form-card { background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        button { background: #ffc107; color: #333; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; }
        button:hover { background: #e0a800; }
        .btn-cancel { background: #6c757d; margin-left: 10px; text-decoration: none; display: inline-block; text-align: center; padding: 12px 25px; border-radius: 5px; color: white; }
        h2 { margin-bottom: 20px; color: #333; }
    </style>
</head>
<body>
    <?php include_once dirname(__DIR__) . '/includes/navigation.php'; ?>
    
    <div class="container">
        <?php getBreadcrumb(); ?>
        
        <div class="form-card">
            <h2>✏️ Edit Customer</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($customer['customer_name']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone:</label>
                    <input type="text" name="phone" value="<?= $customer['phone'] ?>" required>
                </div>
                <div class="form-group">
                    <label>Address:</label>
                    <textarea name="address" rows="3"><?= htmlspecialchars($customer['address']) ?></textarea>
                </div>
                <div>
                    <button type="submit">💾 Update Customer</button>
                    <a href="index.php" class="btn-cancel">❌ Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>