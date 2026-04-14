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
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --bg: #0b0b12;
    --card: #141420;
    --card2: #1b1b2b;
    --primary: #7c3aed;
    --primary-light: #a78bfa;
    --text: #f1f1ff;
    --muted: #a1a1c2;
    --border: rgba(255,255,255,0.08);
    --green: #22c55e;
    --red: #ef4444;
    --amber: #f59e0b;
}

/* BODY */
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: var(--bg);
    color: var(--text);
}

/* BACKGROUND GRID */
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
    max-width: 1400px;
    margin: 30px auto;
    padding: 20px;
}

/* CARD */
.content-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 25px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
}

/* HEADER */
.header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

h2 {
    color: var(--primary-light);
    font-size: 22px;
}

/* BUTTONS */
.btn {
    padding: 10px 16px;
    text-decoration: none;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    transition: 0.2s;
}

/* PRIMARY */
.btn-primary {
    background: var(--primary);
    color: #fff;
}

.btn-primary:hover {
    background: #6d28d9;
    transform: translateY(-2px);
}

/* WARNING */
.btn-warning {
    background: rgba(245,158,11,0.15);
    color: #fbbf24;
    border: 1px solid rgba(245,158,11,0.3);
}

.btn-warning:hover {
    background: rgba(245,158,11,0.25);
}

/* DANGER */
.btn-danger {
    background: rgba(239,68,68,0.15);
    color: #f87171;
    border: 1px solid rgba(239,68,68,0.3);
}

.btn-danger:hover {
    background: rgba(239,68,68,0.25);
}

/* STATS */
.stats {
    background: var(--card2);
    padding: 10px 15px;
    border-radius: 10px;
    border: 1px solid var(--border);
    margin-bottom: 15px;
    color: var(--muted);
}

/* SEARCH */
.search-box {
    width: 300px;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: var(--card);
    color: var(--text);
    outline: none;
}

.search-box:focus {
    border-color: var(--primary);
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

th {
    background: var(--card2);
    color: var(--primary-light);
    text-align: left;
    padding: 12px;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

td {
    padding: 14px;
    border-bottom: 1px solid var(--border);
    color: var(--muted);
    font-size: 13px;
}

tr:hover td {
    background: rgba(124,58,237,0.06);
}

/* ITEM NAME */
td strong {
    color: var(--text);
}

/* STATUS BADGES */
.status-badge {
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
}

.status-available {
    background: rgba(34,197,94,0.15);
    color: #4ade80;
    border: 1px solid rgba(34,197,94,0.3);
}

.status-unavailable {
    background: rgba(239,68,68,0.15);
    color: #f87171;
    border: 1px solid rgba(239,68,68,0.3);
}

/* ACTION BUTTONS */
.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

/* RESPONSIVE */
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