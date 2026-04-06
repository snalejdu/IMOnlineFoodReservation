<?php
require_once 'config/database.php';
include_once 'includes/navigation.php';

$database = new Database();
$db = $database->getConnection();

// Get statistics
$stats = [];

// Total categories
$query = "SELECT COUNT(*) as total FROM categories";
$stmt = $db->prepare($query);
$stmt->execute();
$stats['categories'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Total menu items
$query = "SELECT COUNT(*) as total FROM menu_items";
$stmt = $db->prepare($query);
$stmt->execute();
$stats['menu_items'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Total customers
$query = "SELECT COUNT(*) as total FROM customers";
$stmt = $db->prepare($query);
$stmt->execute();
$stats['customers'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Total orders
$query = "SELECT COUNT(*) as total FROM orders";
$stmt = $db->prepare($query);
$stmt->execute();
$stats['orders'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Total revenue
$query = "SELECT SUM(total_amount) as revenue FROM orders WHERE order_status = 'delivered'";
$stmt = $db->prepare($query);
$stmt->execute();
$stats['revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'] ?? 0;

// Orders by status
$query = "SELECT order_status, COUNT(*) as count FROM orders GROUP BY order_status";
$stmt = $db->prepare($query);
$stmt->execute();
$order_status = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Recent orders
$query = "SELECT o.*, c.customer_name, m.item_name FROM orders o 
          LEFT JOIN customers c ON o.customer_id = c.customer_id 
          LEFT JOIN menu_items m ON o.item_id = m.item_id 
          ORDER BY o.order_date DESC LIMIT 5";
$stmt = $db->prepare($query);
$stmt->execute();
$recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Statistics - Food Ordering System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 1400px; margin: 20px auto; padding: 20px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 25px; border-radius: 10px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-card h3 { color: #667eea; margin-bottom: 10px; }
        .stat-number { font-size: 36px; font-weight: bold; color: #333; }
        .content-card { background: white; border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #667eea; color: white; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .status-pending { background: #ffc107; }
        .status-preparing { background: #17a2b8; color: white; }
        .status-delivered { background: #28a745; color: white; }
        .status-cancelled { background: #dc3545; color: white; }
    </style>
</head>
<body>
    <?php include_once 'includes/navigation.php'; ?>
    
    <div class="container">
        <div class="content-card">
            <h2>📊 System Statistics</h2>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>📋 Categories</h3>
                <div class="stat-number"><?= $stats['categories'] ?></div>
            </div>
            <div class="stat-card">
                <h3>🍽️ Menu Items</h3>
                <div class="stat-number"><?= $stats['menu_items'] ?></div>
            </div>
            <div class="stat-card">
                <h3>👥 Customers</h3>
                <div class="stat-number"><?= $stats['customers'] ?></div>
            </div>
            <div class="stat-card">
                <h3>🛒 Orders</h3>
                <div class="stat-number"><?= $stats['orders'] ?></div>
            </div>
            <div class="stat-card">
                <h3>💰 Total Revenue</h3>
                <div class="stat-number">$<?= number_format($stats['revenue'], 2) ?></div>
            </div>
        </div>
        
        <div class="content-card">
            <h3>📈 Orders by Status</h3>
            <div style="display: flex; gap: 20px; margin-top: 20px; flex-wrap: wrap;">
                <?php foreach($order_status as $status): ?>
                <div style="text-align: center;">
                    <span class="status-badge status-<?= $status['order_status'] ?>" style="font-size: 16px; padding: 8px 15px;">
                        <?= ucfirst($status['order_status']) ?>: <?= $status['count'] ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="content-card">
            <h3>🕒 Recent Orders</h3>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr><th>Order ID</th><th>Customer</th><th>Item</th><th>Quantity</th><th>Total</th><th>Status</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($recent_orders as $order): ?>
                        <tr>
                            <td>#<?= $order['order_id'] ?></td>
                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td><?= htmlspecialchars($order['item_name']) ?></td>
                            <td><?= $order['quantity'] ?></td>
                            <td>$<?= number_format($order['total_amount'], 2) ?></td>
                            <td><span class="status-badge status-<?= $order['order_status'] ?>"><?= ucfirst($order['order_status']) ?></span></td>
                            <td><?= date('M d, Y', strtotime($order['order_date'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>