<?php
require_once dirname(__DIR__) . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

$id = $_GET['id'];

// Process form submission BEFORE any output
if ($_POST) {
    $query = "UPDATE categories SET category_name=:name, category_description=:desc, category_image=:image, status=:status WHERE category_id=:id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':desc', $_POST['description']);
    $stmt->bindParam(':image', $_POST['image']);
    $stmt->bindParam(':status', $_POST['status']);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    }
}

// Fetch category data
$query = "SELECT * FROM categories WHERE category_id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$category = $stmt->fetch(PDO::FETCH_ASSOC);

// Now include navigation after all logic is complete
include_once dirname(__DIR__) . '/includes/navigation.php';
include_once dirname(__DIR__) . '/includes/breadcrumb.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Category - Food Ordering System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 800px; margin: 20px auto; padding: 20px; }
        .form-card { background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        input, textarea, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
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
            <h2>✏️ Edit Category</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Category Name:</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($category['category_name']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" rows="4"><?= htmlspecialchars($category['category_description']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Image URL:</label>
                    <input type="text" name="image" value="<?= htmlspecialchars($category['category_image']) ?>">
                </div>
                <div class="form-group">
                    <label>Status:</label>
                    <select name="status">
                        <option value="active" <?= $category['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $category['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <div>
                    <button type="submit">💾 Update Category</button>
                    <a href="index.php" class="btn-cancel">❌ Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>