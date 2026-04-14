<?php
require_once dirname(__DIR__) . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($_POST) {
    $query = "INSERT INTO categories (category_name, category_description, category_image, status) VALUES (:name, :desc, :image, :status)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':desc', $_POST['description']);
    $stmt->bindParam(':image', $_POST['image']);
    $stmt->bindParam(':status', $_POST['status']);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Error creating category. Please try again.";
    }
}

include_once dirname(__DIR__) . '/includes/navigation.php';
include_once dirname(__DIR__) . '/includes/breadcrumb.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category - Food Ordering System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-base:       #0a0a0f;
            --bg-card:       #111118;
            --bg-elevated:   #1a1a26;
            --bg-input:      #13131e;
            --violet:        #7c3aed;
            --violet-light:  #a78bfa;
            --violet-glow:   rgba(124,58,237,0.15);
            --violet-border: rgba(124,58,237,0.35);
            --text-primary:  #f0eeff;
            --text-secondary:#a39fc4;
            --text-muted:    #5e5a7a;
            --border:        rgba(255,255,255,0.07);
            --border-focus:  rgba(124,58,237,0.6);
            --green:         #22c55e;
            --red:           #ef4444;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
        }

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
            max-width: 700px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        /* ── Breadcrumb / back link ── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13px;
            margin-bottom: 24px;
            transition: color 0.15s;
        }

        .back-link:hover { color: var(--violet-light); }
        .back-link svg { width: 14px; height: 14px; }

        /* ── Page header ── */
        .page-header {
            margin-bottom: 28px;
        }

        .page-header h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.4px;
        }

        .page-header p {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 5px;
        }

        /* ── Accent bar ── */
        .accent-bar {
            width: 40px;
            height: 3px;
            background: var(--violet);
            border-radius: 2px;
            margin-top: 10px;
        }

        /* ── Form card ── */
        .form-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 32px 28px;
        }

        /* ── Error alert ── */
        .alert-error {
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 8px;
            padding: 12px 16px;
            color: #f87171;
            font-size: 13.5px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── Form fields ── */
        .form-grid {
            display: grid;
            gap: 20px;
        }

        .form-group { display: flex; flex-direction: column; gap: 7px; }

        label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        input[type="text"],
        textarea,
        select {
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 11px 14px;
            color: var(--text-primary);
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s, background 0.2s;
            outline: none;
            width: 100%;
        }

        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            border-color: var(--border-focus);
            background: #16162a;
        }

        input::placeholder,
        textarea::placeholder { color: var(--text-muted); }

        textarea { resize: vertical; min-height: 100px; }

        select option { background: #1a1a26; }

        /* Status row */
        .status-options {
            display: flex;
            gap: 12px;
        }

        .status-option {
            flex: 1;
            position: relative;
        }

        .status-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
        }

        .status-option label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 10px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--bg-input);
            cursor: pointer;
            transition: all 0.15s;
            font-size: 13px;
            text-transform: none;
            letter-spacing: 0;
            color: var(--text-muted);
            font-weight: 500;
        }

        .status-option input[type="radio"]:checked + label {
            border-color: var(--violet-border);
            background: var(--violet-glow);
            color: var(--violet-light);
        }

        .status-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
        }

        .dot-active { background: var(--green); }
        .dot-inactive { background: var(--red); }

        /* ── Divider ── */
        .divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 24px 0;
        }

        /* ── Actions ── */
        .form-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--violet);
            color: #fff;
            padding: 11px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
        }

        .btn-submit:hover { background: #6d28d9; transform: translateY(-1px); }
        .btn-submit svg { width: 15px; height: 15px; }

        .btn-cancel {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 11px 18px;
            border: 1px solid var(--border);
            border-radius: 8px;
            transition: all 0.15s;
        }

        .btn-cancel:hover {
            color: var(--text-secondary);
            border-color: rgba(255,255,255,0.15);
            background: var(--bg-elevated);
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-base); }
        ::-webkit-scrollbar-thumb { background: var(--violet); border-radius: 3px; }

        @media (max-width: 640px) {
            .page-wrap { padding: 20px 14px; }
            .form-card { padding: 22px 16px; }
            .status-options { flex-direction: column; }
        }
    </style>
</head>
<body>
    <?php include_once dirname(__DIR__) . '/includes/navigation.php'; ?>

    <div class="page-wrap">
        <?php getBreadcrumb(); ?>

        <a href="index.php" class="back-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Back to Categories
        </a>

        <div class="page-header">
            <h1>Add New Category</h1>
            <p>Fill in the details to create a new food category</p>
            <div class="accent-bar"></div>
        </div>

        <div class="form-card">
            <?php if (isset($error)): ?>
                <div class="alert-error">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-grid">

                    <div class="form-group">
                        <label for="name">Category Name</label>
                        <input type="text" id="name" name="name" placeholder="e.g. Burgers, Desserts…" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" placeholder="Short description of this category…"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image">Image URL</label>
                        <input type="text" id="image" name="image" placeholder="https://example.com/image.jpg">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <div class="status-options">
                            <div class="status-option">
                                <input type="radio" name="status" id="status_active" value="active" checked>
                                <label for="status_active">
                                    <span class="status-dot dot-active"></span>
                                    Active
                                </label>
                            </div>
                            <div class="status-option">
                                <input type="radio" name="status" id="status_inactive" value="inactive">
                                <label for="status_inactive">
                                    <span class="status-dot dot-inactive"></span>
                                    Inactive
                                </label>
                            </div>
                        </div>
                    </div>

                </div>

                <hr class="divider">

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Save Category
                    </button>
                    <a href="index.php" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>