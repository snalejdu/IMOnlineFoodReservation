<?php
require_once dirname(__DIR__) . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

// Process form submission BEFORE any output
if ($_POST) {
    $query = "INSERT INTO categories (category_name, category_description, category_image, status) VALUES (:name, :desc, :image, :status)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':desc', $_POST['description']);
    $stmt->bindParam(':image', $_POST['image']);
    $stmt->bindParam(':status', $_POST['status']);
    
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Error creating category";
    }
}

// Now include navigation after all logic is complete
include_once dirname(__DIR__) . '/includes/navigation.php';
include_once dirname(__DIR__) . '/includes/breadcrumb.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category - Food Ordering System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 800px; margin: 20px auto; padding: 20px; }
        .form-card { background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        input, textarea, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
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
            <h2>➕ Add New Category</h2>
            
            <?php if(isset($error)): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Category Name:</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label>Image URL:</label>
                    <input type="text" name="image" placeholder="https://example.com/image.jpg">
                </div>
                <div class="form-group">
                    <label>Status:</label>
                    <select name="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div>
                    <button type="submit">💾 Save Category</button>
                    <a href="index.php" class="btn-cancel">❌ Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>