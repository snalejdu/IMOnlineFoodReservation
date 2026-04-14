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

body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: var(--bg-main);
    color: var(--text-main);
    min-height: 100vh;
}

/* subtle grid background */
body::before {
    content: '';
    position: fixed;
    inset: 0;
    background-image:
        linear-gradient(rgba(124,58,237,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(124,58,237,0.04) 1px, transparent 1px);
    background-size: 40px 40px;
    z-index: -1;
}

/* HERO */
.hero {
    text-align: center;
    padding: 80px 20px;
}

.hero h1 {
    font-size: 42px;
    margin-bottom: 15px;
    color: var(--primary-light);
}

.hero p {
    font-size: 18px;
    color: var(--text-muted);
}

/* GRID */
.features-grid {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 25px;
}

/* CARD */
.feature-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 30px;
    text-align: center;
    transition: all 0.25s ease;
}

.feature-card:hover {
    transform: translateY(-8px);
    background: var(--card-hover);
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
}

/* ICON */
.feature-icon {
    font-size: 42px;
    margin-bottom: 15px;
}

/* TEXT */
.feature-card h3 {
    color: var(--primary-light);
    margin-bottom: 10px;
    font-size: 22px;
}

.feature-card p {
    color: var(--text-muted);
    font-size: 14px;
    margin-bottom: 20px;
}

/* BUTTON */
.feature-btn {
    display: inline-block;
    padding: 10px 22px;
    background: var(--primary);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 13px;
    transition: all 0.2s ease;
}

.feature-btn:hover {
    background: #6d28d9;
    transform: scale(1.05);
}

/* MOBILE */
@media (max-width: 768px) {
    .hero h1 {
        font-size: 30px;
    }

    .hero p {
        font-size: 15px;
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