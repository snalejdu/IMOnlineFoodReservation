<?php
require_once dirname(__DIR__) . '/config/database.php';
include_once dirname(__DIR__) . '/includes/navigation.php';
include_once dirname(__DIR__) . '/includes/breadcrumb.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT o.*, c.customer_name, m.item_name FROM orders o 
          LEFT JOIN customers c ON o.customer_id = c.customer_id 
          LEFT JOIN menu_items m ON o.item_id = m.item_id 
          ORDER BY o.order_id DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Orders - Food Ordering System</title>
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
        .status-pending { background: #ffc107; color: #333; }
        .status-preparing { background: #17a2b8; color: white; }
        .status-delivered { background: #28a745; color: white; }
        .status-cancelled { background: #dc3545; color: white; }
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
                <h2>🛒 Orders Management</h2>
                <div>
                    <a href="create.php" class="btn btn-primary">➕ Place New Order</a>
                </div>
            </div>
            
            <div class="stats">
                <strong>Total Orders:</strong> <?= count($orders) ?>
            </div>
            
            <input type="text" class="search-box" placeholder="🔍 Search orders..." id="searchInput" onkeyup="searchTable()">
            
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Order Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="dataTable">
                        <?php if(count($orders) > 0): ?>
                            <?php foreach($orders as $order): ?>
                            <tr>
                                <td>#<?= $order['order_id'] ?></td>
                                <td><strong><?= htmlspecialchars($order['customer_name']) ?></strong></td>
                                <td><?= htmlspecialchars($order['item_name']) ?></td>
                                <td><?= $order['quantity'] ?></td>
                                <td><strong>$<?= number_format($order['total_amount'], 2) ?></strong></td>
                                <td>
                                    <span class="status-badge status-<?= $order['order_status'] ?>">
                                        <?= ucfirst($order['order_status']) ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y h:i A', strtotime($order['order_date'])) ?></td>
                                <td class="action-buttons">
                                    <a href="edit.php?id=<?= $order['order_id'] ?>" class="btn btn-warning">✏️ Edit</a>
                                    <a href="delete.php?id=<?= $order['order_id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align: center;">No orders found. <a href="create.php">Place your first order</a></td>
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