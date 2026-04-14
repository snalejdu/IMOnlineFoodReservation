<?php
require_once dirname(__DIR__) . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($_POST) {
    $query = "INSERT INTO customers (customer_name, email, phone, address) VALUES (:name, :email, :phone, :address)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':phone', $_POST['phone']);
    $stmt->bindParam(':address', $_POST['address']);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Error creating customer";
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
    <title>Add Customer - Food Ordering System</title>
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
            max-width: 680px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        /* ── Page header ── */
        .page-header {
            margin-bottom: 28px;
        }

        .page-header h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.5px;
        }

        .page-header p {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* ── Card ── */
        .form-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 32px;
        }

        /* ── Error alert ── */
        .alert-error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.25);
            color: #f87171;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 13px;
            margin-bottom: 24px;
        }

        /* ── Form groups ── */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--violet-light);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 14px;
            color: var(--text-primary);
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            resize: vertical;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: var(--text-muted);
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: var(--violet);
            box-shadow: 0 0 0 3px var(--violet-glow);
        }

        /* ── Divider ── */
        .form-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 28px 0;
        }

        /* ── Buttons ── */
        .form-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-save {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--violet);
            color: #fff;
            padding: 10px 22px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
        }

        .btn-save:hover {
            background: #6d28d9;
            transform: translateY(-1px);
        }

        .btn-cancel {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--bg-elevated);
            color: var(--text-secondary);
            padding: 10px 22px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
        }

        .btn-cancel:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-base); }
        ::-webkit-scrollbar-thumb { background: var(--violet); border-radius: 3px; }

        @media (max-width: 640px) {
            .page-wrap { padding: 20px 14px; }
            .form-card { padding: 20px; }
        }
    </style>
</head>
<body>
    <?php include_once dirname(__DIR__) . '/includes/navigation.php'; ?>

    <div class="page-wrap">
        <?php getBreadcrumb(); ?>

        <div class="page-header">
            <h1>Add New Customer</h1>
            <p>Fill in the details below to register a new customer</p>
        </div>

        <div class="form-card">
            <?php if (isset($error)): ?>
                <div class="alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="e.g. Juan dela Cruz" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="e.g. juan@example.com" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" placeholder="e.g. 09xx-xxx-xxxx" required>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" rows="3" placeholder="Street, Barangay, City, Province"></textarea>
                </div>

                <hr class="form-divider">

                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>
                        </svg>
                        Save Customer
                    </button>
                    <a href="index.php" class="btn-cancel">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>