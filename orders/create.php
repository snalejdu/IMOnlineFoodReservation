<?php
require_once dirname(__DIR__) . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

// Fetch customers for dropdown
$customerQuery = "SELECT customer_id, customer_name, email, phone FROM customers ORDER BY customer_name";
$customerStmt = $db->prepare($customerQuery);
$customerStmt->execute();
$customers = $customerStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch menu items for dropdown
$itemQuery = "SELECT item_id, item_name, price, availability FROM menu_items WHERE availability = 'available' ORDER BY item_name";
$itemStmt = $db->prepare($itemQuery);
$itemStmt->execute();
$items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);

$error = '';

// Process form submission BEFORE any output
if ($_POST) {
    try {
        $quantity = (int)$_POST['quantity'];
        $price = (float)$_POST['price'];
        $total_amount = $quantity * $price;
        
        $query = "INSERT INTO orders (customer_id, item_id, quantity, total_amount, order_status) 
                  VALUES (:customer_id, :item_id, :quantity, :total_amount, :order_status)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':customer_id', $_POST['customer_id']);
        $stmt->bindParam(':item_id', $_POST['item_id']);
        $stmt->bindParam(':quantity', $_POST['quantity']);
        $stmt->bindParam(':total_amount', $total_amount);
        $stmt->bindParam(':order_status', $_POST['order_status']);
        
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Error placing order. Please try again.";
        }
    } catch(PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

// Now include navigation after all logic is complete
include_once dirname(__DIR__) . '/includes/navigation.php';
include_once dirname(__DIR__) . '/includes/breadcrumb.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Place New Order - Food Ordering System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 800px; margin: 20px auto; padding: 20px; }
        .form-card { background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        button { background: #28a745; color: white; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; }
        button:hover { background: #218838; }
        .btn-cancel { background: #6c757d; margin-left: 10px; text-decoration: none; display: inline-block; text-align: center; padding: 12px 25px; border-radius: 5px; color: white; }
        .price-display { background: #e9ecef; padding: 15px; border-radius: 5px; margin-top: 20px; text-align: center; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        h2 { margin-bottom: 20px; color: #333; }
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
        }
    </script>
</head>
<body>
    <?php include_once dirname(__DIR__) . '/includes/navigation.php'; ?>
    
    <div class="container">
        <?php getBreadcrumb(); ?>
        
        <div class="form-card">
            <h2>🛒 Place New Order</h2>
            
            <?php if($error): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Select Customer:</label>
                    <select name="customer_id" required>
                        <option value="">-- Select Customer --</option>
                        <?php foreach($customers as $customer): ?>
                        <option value="<?= $customer['customer_id'] ?>">
                            <?= htmlspecialchars($customer['customer_name']) ?> (<?= htmlspecialchars($customer['email']) ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Select Food Item:</label>
                    <select id="item_id" name="item_id" required onchange="updatePrice()">
                        <option value="">-- Select Item --</option>
                        <?php foreach($items as $item): ?>
                        <option value="<?= $item['item_id'] ?>" data-price="<?= $item['price'] ?>">
                            <?= htmlspecialchars($item['item_name']) ?> - $<?= number_format($item['price'], 2) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Quantity:</label>
                    <input type="number" id="quantity" name="quantity" required min="1" value="1" onchange="calculateTotal()" onkeyup="calculateTotal()">
                </div>
                
                <div class="form-group">
                    <label>Price per item:</label>
                    <input type="text" id="price" name="price" readonly style="background: #e9ecef;">
                </div>
                
                <div class="form-group">
                    <label>Order Status:</label>
                    <select name="order_status" required>
                        <option value="pending">Pending</option>
                        <option value="preparing">Preparing</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                
                <div class="price-display">
                    <strong>💰 Total Amount: </strong> <span id="total_display">$0.00</span>
                </div>
                
                <div style="margin-top: 30px;">
                    <button type="submit">✅ Place Order</button>
                    <a href="index.php" class="btn-cancel">❌ Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>