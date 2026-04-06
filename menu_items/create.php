<?php
require_once dirname(__DIR__) . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

// Process form submission BEFORE any output
if ($_POST) {
    $query = "INSERT INTO menu_items (category_id, item_name, item_description, price, availability) 
              VALUES (:cat_id, :name, :desc, :price, :availability)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':cat_id', $_POST['category_id']);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':desc', $_POST['description']);
    $stmt->bindParam(':price', $_POST['price']);
    $stmt->bindParam(':availability', $_POST['availability']);
    
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    }
}

// Fetch categories for dropdown
$catQuery = "SELECT category_id, category_name FROM categories WHERE status = 'active'";
$catStmt = $db->prepare($catQuery);
$catStmt->execute();
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

// Now include navigation after all logic is complete
include_once dirname(__DIR__) . '/includes/navigation.php';
include_once dirname(__DIR__) . '/includes/breadcrumb.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Menu Item - Food Ordering System</title>
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
        h2 { margin-bottom: 20px; color: #333; }
    </style>
</head>
<body>
    <?php include_once dirname(__DIR__) . '/includes/navigation.php'; ?>
    
    <div class="container">
        <?php getBreadcrumb(); ?>
        
        <div class="form-card">
            <h2>➕ Add New Menu Item</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Category:</label>
                    <select name="category_id" required>
                        <option value="">Select Category</option>
                        <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Item Name:</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label>Price:</label>
                    <input type="number" step="0.01" name="price" required>
                </div>
                <div class="form-group">
                    <label>Availability:</label>
                    <select name="availability">
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                </div>
                <div>
                    <button type="submit">💾 Save Item</button>
                    <a href="index.php" class="btn-cancel">❌ Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>