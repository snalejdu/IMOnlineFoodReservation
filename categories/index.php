<?php
require_once dirname(__DIR__) . '/config/database.php';
include_once dirname(__DIR__) . '/includes/navigation.php';
include_once dirname(__DIR__) . '/includes/breadcrumb.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM categories ORDER BY category_id DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - Food Ordering System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-base:       #0a0a0f;
            --bg-card:       #111118;
            --bg-elevated:   #1a1a26;
            --bg-hover:      #1e1e2e;
            --violet:        #7c3aed;
            --violet-light:  #a78bfa;
            --violet-glow:   rgba(124,58,237,0.18);
            --violet-border: rgba(124,58,237,0.35);
            --text-primary:  #f0eeff;
            --text-secondary:#a39fc4;
            --text-muted:    #5e5a7a;
            --border:        rgba(255,255,255,0.07);
            --border-accent: rgba(124,58,237,0.4);
            --green:         #22c55e;
            --red:           #ef4444;
            --amber:         #f59e0b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* ── Background grid texture ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(124,58,237,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(124,58,237,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        .page-wrap {
            position: relative;
            z-index: 1;
            max-width: 1300px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        /* ── Page header ── */
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

        /* ── Stat pill ── */
        .stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--violet-glow);
            border: 1px solid var(--violet-border);
            border-radius: 999px;
            padding: 5px 14px;
            font-size: 13px;
            color: var(--violet-light);
            font-weight: 500;
        }

        .stat-pill .dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--violet-light);
        }

        /* ── Add button ── */
        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--violet);
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.2s, transform 0.15s;
        }

        .btn-add:hover {
            background: #6d28d9;
            transform: translateY(-1px);
        }

        .btn-add svg { width: 16px; height: 16px; }

        /* ── Toolbar ── */
        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .search-wrap {
            position: relative;
            flex: 1;
            max-width: 320px;
        }

        .search-wrap svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 15px; height: 15px;
            color: var(--text-muted);
        }

        .search-input {
            width: 100%;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 9px 12px 9px 36px;
            color: var(--text-primary);
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s;
            outline: none;
        }

        .search-input::placeholder { color: var(--text-muted); }
        .search-input:focus { border-color: var(--violet); }

        /* ── Card / Table ── */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
        }

        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 620px;
        }

        thead tr {
            background: var(--bg-elevated);
            border-bottom: 1px solid var(--border-accent);
        }

        th {
            padding: 13px 16px;
            font-size: 11px;
            font-weight: 600;
            color: var(--violet-light);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            text-align: left;
            white-space: nowrap;
        }

        td {
            padding: 14px 16px;
            font-size: 13.5px;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        tbody tr:last-child td { border-bottom: none; }

        tbody tr:hover td {
            background: var(--bg-hover);
        }

        .id-badge {
            display: inline-block;
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            border-radius: 5px;
            padding: 2px 8px;
            font-size: 12px;
            color: var(--text-muted);
            font-family: monospace;
        }

        .cat-name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
        }

        .desc-text {
            color: var(--text-muted);
            font-size: 13px;
        }

        /* Image indicator */
        .img-yes {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: var(--green);
        }

        .img-no {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Status badges */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.04em;
        }

        .badge-active {
            background: rgba(34,197,94,0.12);
            color: #4ade80;
            border: 1px solid rgba(34,197,94,0.25);
        }

        .badge-inactive {
            background: rgba(239,68,68,0.1);
            color: #f87171;
            border: 1px solid rgba(239,68,68,0.2);
        }

        /* Action buttons */
        .actions { display: flex; gap: 8px; align-items: center; }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .btn-edit {
            background: rgba(245,158,11,0.1);
            color: #fbbf24;
            border-color: rgba(245,158,11,0.2);
        }

        .btn-edit:hover {
            background: rgba(245,158,11,0.2);
            border-color: rgba(245,158,11,0.4);
        }

        .btn-delete {
            background: rgba(239,68,68,0.1);
            color: #f87171;
            border-color: rgba(239,68,68,0.2);
        }

        .btn-delete:hover {
            background: rgba(239,68,68,0.2);
            border-color: rgba(239,68,68,0.4);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 24px;
        }

        .empty-state p {
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 16px;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-base); }
        ::-webkit-scrollbar-thumb { background: var(--violet); border-radius: 3px; }

        @media (max-width: 640px) {
            .page-wrap { padding: 20px 14px; }
            .page-header { flex-direction: column; align-items: flex-start; }
            .page-title-group h1 { font-size: 20px; }
        }
    </style>
</head>
<body>
    <?php include_once dirname(__DIR__) . '/includes/navigation.php'; ?>

    <div class="page-wrap">
        <?php getBreadcrumb(); ?>

        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title-group">
                <h1>Categories</h1>
                <p>Manage your food menu categories</p>
            </div>
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                <span class="stat-pill">
                    <span class="dot"></span>
                    <?= count($categories) ?> total
                </span>
                <a href="create.php" class="btn-add">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                    Add Category
                </a>
            </div>
        </div>

        <!-- Toolbar -->
        <div class="toolbar">
            <div class="search-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input type="text" class="search-input" id="searchInput" placeholder="Search categories..." onkeyup="searchTable()">
            </div>
        </div>

        <!-- Table Card -->
        <div class="card">
            <div class="table-wrap">
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
                        <?php if (count($categories) > 0): ?>
                            <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td><span class="id-badge">#<?= $cat['category_id'] ?></span></td>
                                <td><span class="cat-name"><?= htmlspecialchars($cat['category_name']) ?></span></td>
                                <td><span class="desc-text"><?= htmlspecialchars(substr($cat['category_description'], 0, 55)) ?>…</span></td>
                                <td>
                                    <?php if ($cat['category_image']): ?>
                                        <span class="img-yes">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2ZM8.5 13.5l2.5 3 3.5-4.5 4.5 6H5l3.5-4.5Z"/></svg>
                                            Yes
                                        </span>
                                    <?php else: ?>
                                        <span class="img-no">— None</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $cat['status'] ?>">
                                        <?= ucfirst($cat['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="edit.php?id=<?= $cat['category_id'] ?>" class="btn-icon btn-edit">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            Edit
                                        </a>
                                        <a href="delete.php?id=<?= $cat['category_id'] ?>" class="btn-icon btn-delete"
                                           onclick="return confirm('Delete this category?')">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                            Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <p>No categories found yet.</p>
                                        <a href="create.php" class="btn-add" style="margin:0 auto;">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                                            Create First Category
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
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