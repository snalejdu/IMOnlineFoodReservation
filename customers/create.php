<?php
require_once dirname(__DIR__) . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

// Process form submission BEFORE any output
if ($_POST) {
    $query = "INSERT INTO customers (customer_name, email, phone, address) VALUES (:name, :email, :phone, :address)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':phone', $_POST['phone']);
    $stmt->bindParam(':address', $_POST['address']);
    
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Error creating customer";
    }
}

// Now include navigation after all logic is complete
include_once dirname(__DIR__) . '/includes/navigation.php';
include_once dirname(__DIR__) . '/includes/breadcrumb.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Customer - Food Ordering System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 800px; margin: 20px auto; padding: 20px; }
        .form-card { background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        button { background: #28a745; color: white; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; }
        button:hover { background: #218838; }
        .btn-cancel { background: #6c757d; margin-left: 10px; text-decoration: none; display: inline-block; text-align: center; padding: 12px 25px; border-radius: 5px; color: white; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        h2 { margin-bottom: 20px; color: #333; }
    </style>
</head>
<body>
    <?php include_once dirname(__DIR__) . '/includes/navigation.php'; ?>
    
    <div class="container">
        <?php getBreadcrumb(); ?>
        
        <div class="form-card">
            <h2>➕ Add New Customer</h2>
            
            <?php if(isset($error)): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Phone:</label>
                    <input type="text" name="phone" required>
                </div>
                <div class="form-group">
                    <label>Address:</label>
                    <textarea name="address" rows="3"></textarea>
                </div>
                <div>
                    <button type="submit">💾 Save Customer</button>
                    <a href="index.php" class="btn-cancel">❌ Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>