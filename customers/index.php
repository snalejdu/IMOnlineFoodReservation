<?php
require_once dirname(__DIR__) . '/config/database.php';
include_once dirname(__DIR__) . '/includes/breadcrumb.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM customers ORDER BY customer_id DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Customers - Food Ordering System</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

<style>
/* (UNCHANGED DESIGN — same as yours) */
:root {
    --bg-base:#0a0a0f;
    --bg-card:#111118;
    --bg-elevated:#1a1a26;
    --bg-hover:#1e1e2e;
    --violet:#7c3aed;
    --violet-light:#a78bfa;
    --violet-glow:rgba(124,58,237,0.18);
    --violet-border:rgba(124,58,237,0.35);
    --text-primary:#f0eeff;
    --text-secondary:#a39fc4;
    --text-muted:#5e5a7a;
    --border:rgba(255,255,255,0.07);
    --red:#ef4444;
}
*{margin:0;padding:0;box-sizing:border-box;}
body { font-family:'Inter',sans-serif; background:var(--bg-base); color:var(--text-primary); }
.page-wrap { max-width:1300px; margin:auto; padding:30px; }
.page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; flex-wrap:wrap; gap:15px; }
.btn-add { background:var(--violet); color:#fff; padding:10px 18px; border-radius:8px; text-decoration:none; }
.search-input { width:300px; padding:10px; border-radius:8px; border:1px solid var(--border); background:var(--bg-card); color:#fff; }
.card { background:var(--bg-card); border-radius:14px; border:1px solid var(--border); overflow:hidden; }
table { width:100%; border-collapse:collapse; }
th { background:var(--bg-elevated); color:var(--violet-light); padding:12px; font-size:12px; text-transform:uppercase; }
td { padding:14px; border-bottom:1px solid var(--border); color:var(--text-secondary); }
tr:hover td { background:var(--bg-hover); }
.btn { padding:6px 12px; border-radius:6px; font-size:12px; text-decoration:none; border:none; cursor:pointer; }
.btn-edit { background:rgba(245,158,11,0.1); color:#fbbf24; }
.btn-delete { background:rgba(239,68,68,0.1); color:#f87171; }
.stat-pill { background:var(--violet-glow); border:1px solid var(--violet-border); padding:5px 12px; border-radius:999px; font-size:13px; color:var(--violet-light); }
</style>
</head>

<body>

<?php include_once dirname(__DIR__) . '/includes/navigation.php'; ?>

<div class="page-wrap">
<?php getBreadcrumb(); ?>

<div class="page-header">
    <h1>Customers</h1>

    <div style="display:flex;gap:10px;align-items:center;">
        <span class="stat-pill"><?= count($customers) ?> total</span>
        <a href="create.php" class="btn-add">+ Add Customer</a>
    </div>
</div>

<input type="text" id="searchInput" class="search-input" placeholder="Search..." onkeyup="searchTable()">

<br><br>

<div class="card">
<table>
<thead>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Address</th>
    <th>Registered</th>
    <th>Actions</th>
</tr>
</thead>

<tbody id="dataTable">
<?php if(count($customers) > 0): ?>
<?php foreach($customers as $c): ?>
<tr>
    <td>#<?= intval($c['customer_id']) ?></td>
    <td><strong><?= htmlspecialchars($c['customer_name'] ?? '') ?></strong></td>
    <td><?= htmlspecialchars($c['email'] ?? '') ?></td>
    <td><?= htmlspecialchars($c['phone'] ?? '') ?></td>
    <td>
        <?= htmlspecialchars(strlen($c['address'] ?? '') > 40 
            ? substr($c['address'],0,40).'...' 
            : $c['address']) ?>
    </td>
    <td>
        <?= !empty($c['registered_date']) 
            ? date('M d, Y', strtotime($c['registered_date'])) 
            : '-' ?>
    </td>
    <td style="display:flex;gap:6px;">
        
        <!-- EDIT -->
        <a href="edit.php?id=<?= intval($c['customer_id']) ?>" class="btn btn-edit">
            Edit
        </a>

        <!-- DELETE (SECURE) -->
        <form method="POST" action="delete.php" onsubmit="return confirm('Delete this customer?')">
            <input type="hidden" name="id" value="<?= intval($c['customer_id']) ?>">
            <button type="submit" class="btn btn-delete">Delete</button>
        </form>

    </td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
    <td colspan="7" style="text-align:center;">No customers found.</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>

</div>

<script>
function searchTable() {
    const filter = document.getElementById('searchInput').value.toUpperCase();
    const rows = document.querySelectorAll('#dataTable tr');

    rows.forEach(row => {
        const text = row.textContent.toUpperCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
}
</script>

</body>
</html>