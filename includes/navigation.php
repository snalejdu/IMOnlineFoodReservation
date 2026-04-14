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

:root {
    --bg-nav: #0f0f1a;
    --bg-nav-light: #181825;
    --bg-hover: #232336;
    --primary: #7c3aed;
    --primary-light: #a78bfa;
    --text-main: #f1f1ff;
    --text-muted: #a1a1c2;
    --border: rgba(255,255,255,0.08);
}

/* NAVBAR */
.main-nav {
    background: var(--bg-nav);
    border-bottom: 1px solid var(--border);
    position: sticky;
    top: 0;
    z-index: 1000;
}

/* CONTAINER */
.nav-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

/* HEADER */
.nav-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
}

/* LOGO */
.logo {
    color: var(--primary-light);
    font-size: 22px;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
}

.logo:hover {
    color: #c4b5fd;
}

/* MENU */
.nav-menu {
    display: flex;
    list-style: none;
    gap: 8px;
}

/* ITEMS */
.nav-item {
    position: relative;
}

/* LINKS */
.nav-link {
    color: var(--text-main);
    text-decoration: none;
    padding: 10px 16px;
    display: block;
    border-radius: 8px;
    transition: all 0.2s ease;
    font-size: 14px;
}

.nav-link:hover {
    background: var(--bg-hover);
    color: var(--primary-light);
}

/* ACTIVE */
.nav-link.active {
    background: var(--primary);
    color: #fff;
}

/* DROPDOWN */
.dropdown-content {
    display: none;
    position: absolute;
    background: var(--bg-nav-light);
    min-width: 200px;
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
    top: 110%;
    left: 0;
}

/* SHOW DROPDOWN */
.dropdown:hover .dropdown-content {
    display: block;
}

/* DROPDOWN LINKS */
.dropdown-content a {
    color: var(--text-main);
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    transition: 0.2s;
    font-size: 13px;
}

.dropdown-content a:hover {
    background: var(--bg-hover);
    color: var(--primary-light);
    padding-left: 20px;
}

/* MOBILE BUTTON */
.mobile-menu-btn {
    display: none;
    background: none;
    border: none;
    color: var(--text-main);
    font-size: 22px;
    cursor: pointer;
}

/* MOBILE */
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
        background: var(--bg-nav);
        padding: 15px;
        border-top: 1px solid var(--border);
    }

    .nav-menu.active {
        display: flex;
    }

    .dropdown-content {
        position: static;
        background: var(--bg-nav-light);
        margin-top: 5px;
        border-radius: 8px;
    }

    .nav-item {
        width: 100%;
    }
}

/* OPTIONAL UI ELEMENTS */
.breadcrumb {
    background: var(--bg-nav-light);
    padding: 10px 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    color: var(--text-muted);
}

.breadcrumb a {
    color: var(--primary-light);
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.page-title {
    margin-bottom: 20px;
    color: var(--text-main);
    border-left: 4px solid var(--primary);
    padding-left: 12px;
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