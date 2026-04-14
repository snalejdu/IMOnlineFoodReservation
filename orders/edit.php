<?php
require_once dirname(__DIR__) . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : die('Order ID required');

// Process form submission BEFORE any output
if ($_POST) {
    try {
        $quantity = (int)$_POST['quantity'];
        $price = (float)$_POST['price'];
        $total_amount = $quantity * $price;
        
        $query = "UPDATE orders SET customer_id=:customer_id, item_id=:item_id, quantity=:quantity, total_amount=:total_amount, order_status=:order_status WHERE order_id=:id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':customer_id', $_POST['customer_id']);
        $stmt->bindParam(':item_id', $_POST['item_id']);
        $stmt->bindParam(':quantity', $_POST['quantity']);
        $stmt->bindParam(':total_amount', $total_amount);
        $stmt->bindParam(':order_status', $_POST['order_status']);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        }
    } catch(PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

// Fetch customers for dropdown
$customerQuery = "SELECT customer_id, customer_name, email FROM customers ORDER BY customer_name";
$customerStmt = $db->prepare($customerQuery);
$customerStmt->execute();
$customers = $customerStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch menu items for dropdown
$itemQuery = "SELECT item_id, item_name, price FROM menu_items WHERE availability = 'available' ORDER BY item_name";
$itemStmt = $db->prepare($itemQuery);
$itemStmt->execute();
$items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch order data
$query = "SELECT * FROM orders WHERE order_id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$order) {
    die('Order not found');
}

// Now include navigation after all logic is complete
include_once dirname(__DIR__) . '/includes/navigation.php';
include_once dirname(__DIR__) . '/includes/breadcrumb.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order - Food Ordering System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-base: #0a0a0f;
            --bg-card: #111118;
            --bg-elevated: #1a1a26;
            --bg-hover: #1e1e2e;
            --violet: #7c3aed;
            --violet-light: #a78bfa;
            --violet-glow: rgba(124,58,237,0.18);
            --violet-border: rgba(124,58,237,0.35);
            --text-primary: #f0eeff;
            --text-secondary: #a39fc4;
            --text-muted: #5e5a7a;
            --border: rgba(255,255,255,0.07);
            --border-accent: rgba(124,58,237,0.4);
            --green: #22c55e;
            --red: #ef4444;
            --amber: #f59e0b;
            --blue: #3b82f6;
            --error-bg: rgba(239,68,68,0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* Background grid texture */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: linear-gradient(rgba(124,58,237,0.03) 1px, transparent 1px), 
                              linear-gradient(90deg, rgba(124,58,237,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        .page-wrap {
            position: relative;
            z-index: 1;
            max-width: 900px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        /* Page header */
        .page-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 28px;
        }

        .page-title-group h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.5px;
        }

        .page-title-group p {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Back button */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-back:hover {
            background: var(--bg-hover);
            border-color: var(--violet-border);
            color: var(--violet-light);
        }

        /* Order ID badge */
        .order-badge {
            display: inline-block;
            background: var(--violet-glow);
            border: 1px solid var(--violet-border);
            border-radius: 8px;
            padding: 4px 12px;
            font-size: 13px;
            color: var(--violet-light);
            font-family: monospace;
            margin-left: 10px;
        }

        /* Form card */
        .form-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            margin-top: 20px;
        }

        .form-header {
            padding: 24px 28px;
            border-bottom: 1px solid var(--border-accent);
            background: var(--bg-elevated);
        }

        .form-header h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-body {
            padding: 28px;
        }

        /* Form groups */
        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--violet-light);
            margin-bottom: 8px;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 11px 14px;
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            outline: none;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--violet);
            box-shadow: 0 0 0 3px var(--violet-glow);
        }

        .form-group input[readonly] {
            background: var(--bg-card);
            color: var(--text-muted);
            cursor: not-allowed;
            border-color: var(--border);
        }

        /* Price display */
        .price-display {
            background: var(--violet-glow);
            border: 1px solid var(--violet-border);
            border-radius: 10px;
            padding: 16px 20px;
            margin: 28px 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 18px;
        }

        .price-display strong {
            color: var(--violet-light);
            font-weight: 600;
        }

        .price-display span {
            font-family: 'Space Grotesk', monospace;
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
        }

        /* Buttons */
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--amber);
            color: #333;
            padding: 10px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-submit:hover {
            background: #e0a800;
            transform: translateY(-1px);
        }

        .btn-cancel {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            background: var(--bg-hover);
            border-color: var(--red);
            color: var(--red);
        }

        /* Error message */
        .error-message {
            background: var(--error-bg);
            border-left: 3px solid var(--red);
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 24px;
            color: #f87171;
            font-size: 13px;
            font-weight: 500;
        }

        /* Two column layout for form */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .full-width {
            grid-column: span 2;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-wrap {
                padding: 20px 16px;
            }
            
            .page-title-group h1 {
                font-size: 20px;
            }
            
            .form-body {
                padding: 20px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .full-width {
                grid-column: span 1;
            }
            
            .price-display {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn-submit, .btn-cancel {
                justify-content: center;
            }
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--bg-base);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--violet);
            border-radius: 3px;
        }

        /* Select dropdown arrow styling */
        select {
            appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23a78bfa' stroke-width='2'><polyline points='6 9 12 15 18 9'/></svg>");
            background-repeat: no-repeat;
            background-position: right 12px center;
            cursor: pointer;
        }

        /* Number input spinners */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            opacity: 0.5;
        }
    </style>
    <script>
        function updatePrice() {
            var itemSelect = document.getElementById('item_id');
            var selectedOption = itemSelect.options[itemSelect.selectedIndex];
            var price = selectedOption.getAttribute('data-price');
            document.getElementById('price').value = price ? parseFloat(price).toFixed(2) : '';
            calculateTotal();
        }
        
        function calculateTotal() {
            var quantity = document.getElementById('quantity').value;
            var price = document.getElementById('price').value;
            if(quantity && price && quantity > 0 && price > 0) {
                var total = parseFloat(quantity) * parseFloat(price);
                document.getElementById('total_display').innerHTML = '$' + total.toFixed(2);
            } else {
                document.getElementById('total_display').innerHTML = '$0.00';
            }
        }
        
        window.onload = function() {
            updatePrice();
            document.getElementById('quantity').value = <?= $order['quantity'] ?>;
            calculateTotal();
        }
    </script>
</head>
<body>
    <?php include_once dirname(__DIR__) . '/includes/navigation.php'; ?>
    
    <div class="page-wrap">
        <?php getBreadcrumb(); ?>
        
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title-group">
                <h1>
                    Edit Order 
                    <span class="order-badge">#<?= $order['order_id'] ?></span>
                </h1>
                <p>Update order details and status</p>
            </div>
            <a href="index.php" class="btn-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back to Orders
            </a>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <div class="form-header">
                <h2>
                    <span>✏️</span> 
                    Order Information
                </h2>
            </div>
            
            <div class="form-body">
                <?php if(isset($error)): ?>
                    <div class="error-message">
                        ⚠️ <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>👤 Customer</label>
                            <select name="customer_id" required>
                                <?php foreach($customers as $customer): ?>
                                <option value="<?= $customer['customer_id'] ?>" <?= $customer['customer_id'] == $order['customer_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($customer['customer_name']) ?> (<?= htmlspecialchars($customer['email']) ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>🍽️ Menu Item</label>
                            <select id="item_id" name="item_id" required onchange="updatePrice()">
                                <?php foreach($items as $item): ?>
                                <option value="<?= $item['item_id'] ?>" data-price="<?= $item['price'] ?>" <?= $item['item_id'] == $order['item_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($item['item_name']) ?> - $<?= number_format($item['price'], 2) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>🔢 Quantity</label>
                            <input type="number" id="quantity" name="quantity" required min="1" value="<?= $order['quantity'] ?>" onchange="calculateTotal()" onkeyup="calculateTotal()">
                        </div>
                        
                        <div class="form-group">
                            <label>💰 Price per item</label>
                            <input type="text" id="price" name="price" readonly>
                        </div>
                        
                        <div class="form-group full-width">
                            <label>📊 Order Status</label>
                            <select name="order_status" required>
    <option value="pending" <?= $order['order_status'] == 'pending' ? 'selected' : '' ?>>⏳ Pending</option>
    <option value="preparing" <?= $order['order_status'] == 'preparing' ? 'selected' : '' ?>>🔪 Preparing</option>
    <option value="delivered" <?= $order['order_status'] == 'delivered' ? 'selected' : '' ?>>✅ Delivered</option>
    <option value="cancelled" <?= $order['order_status'] == 'cancelled' ? 'selected' : '' ?>>❌ Cancelled</option>
</select>
                        </div>
                    </div>
                    
                    <div class="price-display">
                        <strong>💰 Total Amount:</strong>
                        <span id="total_display">$<?= number_format($order['total_amount'], 2) ?></span>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 14.66V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.34"/>
                                <polygon points="18 2 22 6 12 16 8 16 8 12 18 2"/>
                            </svg>
                            Update Order
                        </button>
                        <a href="index.php" class="btn-cancel">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"/>
                                <line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>