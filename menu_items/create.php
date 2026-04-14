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

:root {
    --bg-main: #0b0b12;
    --card-bg: #141420;
    --card-hover: #1c1c2b;
    --primary: #7c3aed;
    --primary-light: #a78bfa;
    --text-main: #f1f1ff;
    --text-muted: #a1a1c2;
    --border: rgba(255,255,255,0.08);
}

/* BODY */
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: var(--bg-main);
    color: var(--text-main);
}

/* CONTAINER */
.container {
    max-width: 800px;
    margin: 30px auto;
    padding: 20px;
}

/* CARD */
.form-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
}

/* TITLE */
h2 {
    margin-bottom: 20px;
    color: var(--primary-light);
}

/* FORM GROUP */
.form-group {
    margin-bottom: 20px;
}

/* LABEL */
label {
    display: block;
    margin-bottom: 6px;
    font-size: 14px;
    color: var(--text-muted);
}

/* INPUTS */
input, textarea, select {
    width: 100%;
    padding: 10px;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 14px;
    background: #0f0f1a;
    color: var(--text-main);
    outline: none;
    transition: 0.2s;
}

/* INPUT FOCUS */
input:focus, textarea:focus, select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 1px var(--primary);
}

/* BUTTON */
button {
    background: var(--primary);
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: 0.2s;
}

button:hover {
    background: #6d28d9;
    transform: translateY(-1px);
}

/* CANCEL BUTTON */
.btn-cancel {
    background: #2a2a3d;
    margin-left: 10px;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    padding: 12px 25px;
    border-radius: 8px;
    color: var(--text-main);
    font-size: 14px;
    transition: 0.2s;
}

.btn-cancel:hover {
    background: #3a3a55;
}

/* SELECT OPTION FIX (for readability) */
select option {
    background: #0f0f1a;
    color: var(--text-main);
}
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