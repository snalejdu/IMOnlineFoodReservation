<?php
require_once dirname(__DIR__) . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

$id = $_GET['id'];

// Process form submission BEFORE any output
if ($_POST) {
    $query = "UPDATE menu_items SET category_id=:cat_id, item_name=:name, item_description=:desc, price=:price, availability=:availability WHERE item_id=:id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':cat_id', $_POST['category_id']);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':desc', $_POST['description']);
    $stmt->bindParam(':price', $_POST['price']);
    $stmt->bindParam(':availability', $_POST['availability']);
    $stmt->bindParam(':id', $id);
    
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

// Fetch menu item data
$query = "SELECT * FROM menu_items WHERE item_id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$item = $stmt->fetch(PDO::FETCH_ASSOC);

// Now include navigation after all logic is complete
include_once dirname(__DIR__) . '/includes/navigation.php';
include_once dirname(__DIR__) . '/includes/breadcrumb.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Menu Item - Food Ordering System</title>
   <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --bg: #0b0b12;
    --card: #141420;
    --card2: #1c1c2b;
    --primary: #7c3aed;
    --primary-light: #a78bfa;
    --text: #f1f1ff;
    --muted: #a1a1c2;
    --border: rgba(255,255,255,0.08);
    --warning: #fbbf24;
    --danger: #ef4444;
}

/* BODY */
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: var(--bg);
    color: var(--text);
}

/* GRID BACKGROUND */
body::before {
    content: '';
    position: fixed;
    inset: 0;
    background-image:
        linear-gradient(rgba(124,58,237,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(124,58,237,0.03) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
}

/* CONTAINER */
.container {
    max-width: 800px;
    margin: 30px auto;
    padding: 20px;
}

/* FORM CARD */
.form-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
}

/* TITLE */
h2 {
    margin-bottom: 20px;
    color: var(--primary-light);
    font-size: 22px;
}

/* FORM GROUP */
.form-group {
    margin-bottom: 18px;
}

/* LABEL */
label {
    display: block;
    margin-bottom: 6px;
    font-size: 13px;
    color: var(--muted);
}

/* INPUTS */
input, textarea, select {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: #0f0f1a;
    color: var(--text);
    font-size: 14px;
    outline: none;
    transition: 0.2s;
}

/* FOCUS */
input:focus,
textarea:focus,
select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(124,58,237,0.2);
}

/* TEXTAREA */
textarea {
    resize: vertical;
}

/* BUTTON (UPDATE) */
button {
    background: var(--warning);
    color: #111;
    padding: 12px 22px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s;
}

button:hover {
    background: #f59e0b;
    transform: translateY(-1px);
}

/* CANCEL BUTTON */
.btn-cancel {
    background: #2a2a3d;
    margin-left: 10px;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    padding: 12px 22px;
    border-radius: 8px;
    color: var(--text);
    font-size: 14px;
    transition: 0.2s;
}

.btn-cancel:hover {
    background: #3a3a55;
}

/* SELECT OPTIONS FIX */
select option {
    background: #0f0f1a;
    color: var(--text);
}
</style>
</head>
<body>
    <?php include_once dirname(__DIR__) . '/includes/navigation.php'; ?>
    
    <div class="container">
        <?php getBreadcrumb(); ?>
        
        <div class="form-card">
            <h2>✏️ Edit Menu Item</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Category:</label>
                    <select name="category_id" required>
                        <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>" <?= $cat['category_id'] == $item['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['category_name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Item Name:</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($item['item_name']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" rows="4"><?= htmlspecialchars($item['item_description']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Price:</label>
                    <input type="number" step="0.01" name="price" value="<?= $item['price'] ?>" required>
                </div>
                <div class="form-group">
                    <label>Availability:</label>
                    <select name="availability">
                        <option value="available" <?= $item['availability'] == 'available' ? 'selected' : '' ?>>Available</option>
                        <option value="unavailable" <?= $item['availability'] == 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
                    </select>
                </div>
                <div>
                    <button type="submit">💾 Update Item</button>
                    <a href="index.php" class="btn-cancel">❌ Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>