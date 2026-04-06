<?php
require_once dirname(__DIR__) . '/config/database.php';
include_once dirname(__DIR__) . '/includes/navigation.php';
include_once dirname(__DIR__) . '/includes/breadcrumb.php';

$database = new Database();
$db = $database->getConnection();

// Fetch all categories
$query = "SELECT * FROM categories ORDER BY category_id DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories - Food Ordering System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f4f4;
        }
        
        .container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
        }
        
        .content-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .btn {
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-primary {
            background: #28a745;
            color: white;
        }
        
        .btn-primary:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        
        .btn-warning {
            background: #ffc107;
            color: #333;
        }
        
        .btn-warning:hover {
            background: #e0a800;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .btn-info {
            background: #17a2b8;
            color: white;
        }
        
        .btn-info:hover {
            background: #138496;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background: #667eea;
            color: white;
            font-weight: 600;
        }
        
        tr:hover {
            background: #f5f5f5;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        
        .status-active {
            background: #28a745;
            color: white;
        }
        
        .status-inactive {
            background: #dc3545;
            color: white;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .search-box {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .stats {
            background: #e9ecef;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .header-actions {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-box {
                width: 100%;
            }
            
            table {
                font-size: 12px;
            }
            
            th, td {
                padding: 8px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <?php include_once dirname(__DIR__) . '/includes/navigation.php'; ?>
    
    <div class="container">
        <?php getBreadcrumb(); ?>
        
        <div class="content-card">
            <div class="header-actions">
                <h2>📋 Categories Management</h2>
                <div>
                    <a href="create.php" class="btn btn-primary">➕ Add New Category</a>
                </div>
            </div>
            
            <div class="stats">
                <strong>Total Categories:</strong> <?= count($categories) ?>
            </div>
            
            <input type="text" class="search-box" placeholder="🔍 Search categories..." id="searchInput" onkeyup="searchTable()">
            
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="dataTable">
                        <?php if(count($categories) > 0): ?>
                            <?php foreach($categories as $category): ?>
                            <tr>
                                <td><?= $category['category_id'] ?></td>
                                <td><strong><?= htmlspecialchars($category['category_name']) ?></strong></td>
                                <td><?= htmlspecialchars(substr($category['category_description'], 0, 50)) ?>...</td>
                                <td><?= $category['category_image'] ? '📷 Yes' : '❌ No' ?></td>
                                <td>
                                    <span class="status-badge status-<?= $category['status'] ?>">
                                        <?= ucfirst($category['status']) ?>
                                    </span>
                                </td>
                                <td class="action-buttons">
                                    <a href="edit.php?id=<?= $category['category_id'] ?>" class="btn btn-warning">✏️ Edit</a>
                                    <a href="delete.php?id=<?= $category['category_id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">🗑️ Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">No categories found. <a href="create.php">Create your first category</a></td>
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