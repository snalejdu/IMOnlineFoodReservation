<?php
// Get current page filename
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        .main-nav {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .nav-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }
        
        .logo {
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo:hover {
            color: #ffd700;
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
            gap: 5px;
        }
        
        .nav-item {
            position: relative;
        }
        
        .nav-link {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            display: block;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }
        
        .nav-link.active {
            background: #ffd700;
            color: #333;
        }
        
        .dropdown {
            position: relative;
        }
        
        .dropdown-content {
            display: none;
            position: absolute;
            background: white;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            border-radius: 5px;
            z-index: 1;
            top: 100%;
            left: 0;
        }
        
        .dropdown:hover .dropdown-content {
            display: block;
        }
        
        .dropdown-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: 0.3s;
        }
        
        .dropdown-content a:hover {
            background: #f4f4f4;
            padding-left: 25px;
        }
        
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }
            
            .nav-menu {
                display: none;
                flex-direction: column;
                width: 100%;
                position: absolute;
                top: 100%;
                left: 0;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                padding: 20px;
            }
            
            .nav-menu.active {
                display: flex;
            }
            
            .dropdown-content {
                position: static;
                background: rgba(255,255,255,0.1);
                margin-left: 20px;
            }
            
            .dropdown-content a {
                color: white;
            }
            
            .nav-item {
                width: 100%;
            }
        }
        
        /* Breadcrumb */
        .breadcrumb {
            background: #f4f4f4;
            padding: 10px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .breadcrumb a {
            color: #667eea;
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        
        /* Page Title */
        .page-title {
            margin-bottom: 20px;
            color: #333;
            border-left: 4px solid #667eea;
            padding-left: 15px;
        }
    </style>
</head>
<body>
    <nav class="main-nav">
        <div class="nav-container">
            <div class="nav-header">
                <a href="/food/index.php" class="logo">
                    🍕 Food Ordering System
                </a>
                <button class="mobile-menu-btn" onclick="toggleMenu()">☰</button>
            </div>
            <ul class="nav-menu" id="navMenu">
                <li class="nav-item">
                    <a href="/food/index.php" class="nav-link <?= ($current_page == 'index.php' && $current_dir == 'food') ? 'active' : '' ?>">
                        🏠 Home
                    </a>
                </li>
                
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link <?= ($current_dir == 'categories') ? 'active' : '' ?>">
                        📋 Categories ▼
                    </a>
                    <div class="dropdown-content">
                        <a href="/food/categories/index.php">📊 View Categories</a>
                        <a href="/food/categories/create.php">➕ Add Category</a>
                    </div>
                </li>
                
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link <?= ($current_dir == 'menu_items') ? 'active' : '' ?>">
                        🍽️ Menu Items ▼
                    </a>
                    <div class="dropdown-content">
                        <a href="/food/menu_items/index.php">📊 View Menu Items</a>
                        <a href="/food/menu_items/create.php">➕ Add Menu Item</a>
                    </div>
                </li>
                
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link <?= ($current_dir == 'customers') ? 'active' : '' ?>">
                        👥 Customers ▼
                    </a>
                    <div class="dropdown-content">
                        <a href="/food/customers/index.php">📊 View Customers</a>
                        <a href="/food/customers/create.php">➕ Add Customer</a>
                    </div>
                </li>
                
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link <?= ($current_dir == 'orders') ? 'active' : '' ?>">
                        🛒 Orders ▼
                    </a>
                    <div class="dropdown-content">
                        <a href="/food/orders/index.php">📊 View Orders</a>
                        <a href="/food/orders/create.php">➕ Place Order</a>
                    </div>
                </li>
                
                <li class="nav-item">
                    <a href="/food/stats.php" class="nav-link">
                        📊 Statistics
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    
    <script>
        function toggleMenu() {
            var menu = document.getElementById('navMenu');
            menu.classList.toggle('active');
        }
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            var menu = document.getElementById('navMenu');
            var btn = document.querySelector('.mobile-menu-btn');
            if (!menu.contains(event.target) && !btn.contains(event.target)) {
                menu.classList.remove('active');
            }
        });
    </script>
</body>
</html>