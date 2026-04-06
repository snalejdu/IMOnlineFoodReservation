<?php
require_once dirname(__DIR__) . '/config/database.php';
include_once dirname(__DIR__) . '/includes/navigation.php';
include_once dirname(__DIR__) . '/includes/breadcrumb.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT m.*, c.category_name FROM menu_items m 
          LEFT JOIN categories c ON m.category_id = c.category_id 
          ORDER BY m.item_id DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Menu Items - Food Ordering System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 1400px; margin: 20px auto; padding: 20px; }
        .content-card { background: white; border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px; }
        .btn { padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; }
        .btn-primary { background: #28a745; color: white; }
        .btn-primary:hover { background: #218838; transform: translateY(-2px); }
        .btn-warning { background: #ffc107; color: #333; }
        .btn-warning:hover { background: #e0a800; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #c82333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #667eea; color: white; font-weight: 600; }
        tr:hover { background: #f5f5f5; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; display: inline-block; }
        .status-available { background: #28a745; color: white; }
        .status-unavailable { background: #dc3545; color: white; }
        .action-buttons { display: flex; gap: 8px; flex-wrap: wrap; }
        .search-box { padding: 10px; width: 300px; border: 1px solid #ddd; border-radius: 5px; }
        .stats { background: #e9ecef; padding: 10px 15px; border-radius: 5px; margin-bottom: 20px; }
        @media (max-width: 768px) {
            .container { padding: 10px; }
            .header-actions { flex-direction: column; align-items: stretch; }
            .search-box { width: 100%; }
            table { font-size: 12px; }
            th, td { padding: 8px; }
        }
    </style>
</head>
<body>
    <?php include_once dirname(__DIR__) . '/includes/navigation.php'; ?>
    
    <div class="container">
        <?php getBreadcrumb(); ?>
        
        <div class="content-card">
            <div class="header-actions">
                <h2>🍽️ Menu Items Management</h2>
                <div>
                    <a href="create.php" class="btn btn-primary">➕ Add New Item</a>
                </div>
            </div>
            
            <div class="stats">
                <strong>Total Menu Items:</strong> <?= count($items) ?>
            </div>
            
            <input type="text" class="search-box" placeholder="🔍 Search menu items..." id="searchInput" onkeyup="searchTable()">
            
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category</th>
                            <th>Item Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Availability</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="dataTable">
                        <?php if(count($items) > 0): ?>
                            <?php foreach($items as $item): ?>
                            <tr>
                                <td><?= $item['item_id'] ?></td>
                                <td><?= htmlspecialchars($item['category_name']) ?></td>
                                <td><strong><?= htmlspecialchars($item['item_name']) ?></strong></td>
                                <td><?= htmlspecialchars(substr($item['item_description'], 0, 50)) ?>...</td>
                                <td><strong>$<?= number_format($item['price'], 2) ?></strong></td>
                                <td>
                                    <span class="status-badge status-<?= $item['availability'] ?>">
                                        <?= ucfirst($item['availability']) ?>
                                    </span>
                                </td>
                                <td class="action-buttons">
                                    <a href="edit.php?id=<?= $item['item_id'] ?>" class="btn btn-warning">✏️ Edit</a>
                                    <a href="delete.php?id=<?= $item['item_id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center;">No menu items found. <a href="create.php">Add your first menu item</a></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        function searchTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("dataTable");
            tr = table.getElementsByTagName("tr");
            
            for (i = 0; i < tr.length; i++) {
                var found = false;
                var tds = tr[i].getElementsByTagName("td");
                for (var j = 0; j < tds.length; j++) {
                    var td = tds[j];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = found ? "" : "none";
            }
        }
    </script>
</body>
</html>