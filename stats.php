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

    --green: #22c55e;
    --red: #ef4444;
    --amber: #f59e0b;
    --blue: #38bdf8;
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
    max-width: 1400px;
    margin: 30px auto;
    padding: 20px;
}

/* SECTION CARD */
.content-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
}

/* TITLE */
h2, h3 {
    color: var(--primary-light);
}

/* STATS GRID */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 18px;
    margin-bottom: 30px;
}

/* STAT CARD */
.stat-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 25px;
    text-align: center;
    transition: 0.2s;
    position: relative;
    overflow: hidden;
}

/* HOVER EFFECT */
.stat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(124,58,237,0.15);
}

/* STAT TITLE */
.stat-card h3 {
    font-size: 14px;
    color: var(--muted);
    margin-bottom: 10px;
}

/* BIG NUMBER */
.stat-number {
    font-size: 34px;
    font-weight: 700;
    color: var(--text);
}

/* TOP BORDER GLOW */
.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), var(--primary-light));
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
}

td {
    padding: 12px;
    border-bottom: 1px solid var(--border);
    color: var(--muted);
    font-size: 13px;
}

tr:hover td {
    background: rgba(124,58,237,0.06);
}

/* STATUS BADGES */
.status-badge {
    padding: 5px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}

/* STATUS COLORS */
.status-pending {
    background: rgba(245,158,11,0.15);
    color: #fbbf24;
    border: 1px solid rgba(245,158,11,0.3);
}

.status-preparing {
    background: rgba(56,189,248,0.15);
    color: #38bdf8;
    border: 1px solid rgba(56,189,248,0.3);
}

.status-delivered {
    background: rgba(34,197,94,0.15);
    color: #4ade80;
    border: 1px solid rgba(34,197,94,0.3);
}

.status-cancelled {
    background: rgba(239,68,68,0.15);
    color: #f87171;
    border: 1px solid rgba(239,68,68,0.3);
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .container { padding: 10px; }
    table { font-size: 12px; }
    th, td { padding: 8px; }
}
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