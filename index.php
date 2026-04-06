<?php
include_once 'includes/navigation.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Ordering System - Welcome</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .hero {
            text-align: center;
            padding: 60px 20px;
            color: white;
        }
        
        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            animation: fadeInDown 1s ease;
        }
        
        .hero p {
            font-size: 20px;
            margin-bottom: 30px;
            animation: fadeInUp 1s ease;
        }
        
        .features-grid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            animation: fadeIn 1s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .feature-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        
        .feature-card h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 24px;
        }
        
        .feature-card p {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .feature-btn {
            display: inline-block;
            padding: 10px 25px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: transform 0.3s;
        }
        
        .feature-btn:hover {
            transform: scale(1.05);
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 32px;
            }
            
            .hero p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <?php include_once 'includes/navigation.php'; ?>
    
    <div class="hero">
        <h1>Welcome to Food Ordering System</h1>
        <p>Delicious food delivered to your doorstep! Order your favorite meals online.</p>
    </div>
    
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">📋</div>
            <h3>Categories Management</h3>
            <p>Manage food categories, add, edit, or delete categories as needed.</p>
            <a href="categories/index.php" class="feature-btn">Manage Categories →</a>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">🍽️</div>
            <h3>Menu Items</h3>
            <p>Browse and manage all food items with prices and descriptions.</p>
            <a href="menu_items/index.php" class="feature-btn">View Menu →</a>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">👥</div>
            <h3>Customers</h3>
            <p>Manage customer information and order history.</p>
            <a href="customers/index.php" class="feature-btn">Manage Customers →</a>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">🛒</div>
            <h3>Orders</h3>
            <p>Track and manage customer orders efficiently.</p>
            <a href="orders/index.php" class="feature-btn">View Orders →</a>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">📊</div>
            <h3>Statistics</h3>
            <p>View system statistics and performance metrics.</p>
            <a href="stats.php" class="feature-btn">View Stats →</a>
        </div>
    </div>
</body>
</html>