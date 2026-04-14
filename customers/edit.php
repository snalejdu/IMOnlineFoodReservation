<?php
require_once dirname(__DIR__) . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

$id = $_GET['id'];

if ($_POST) {
    $query = "UPDATE customers SET customer_name=:name, email=:email, phone=:phone, address=:address WHERE customer_id=:id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':phone', $_POST['phone']);
    $stmt->bindParam(':address', $_POST['address']);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    }
}

$query = "SELECT * FROM customers WHERE customer_id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

include_once dirname(__DIR__) . '/includes/navigation.php';
include_once dirname(__DIR__) . '/includes/breadcrumb.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer - Food Ordering System</title>
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
            --amber:         #f59e0b;
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

        /* ── Customer meta badge ── */
        .customer-meta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--violet-glow);
            border: 1px solid var(--violet-border);
            border-radius: 999px;
            padding: 4px 12px;
            font-size: 12px;
            color: var(--violet-light);
            font-weight: 500;
            margin-bottom: 20px;
        }

        .form-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 32px;
        }

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

        .form-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 28px 0;
        }

        .form-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-update {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(245,158,11,0.15);
            color: #fbbf24;
            padding: 10px 22px;
            border: 1px solid rgba(245,158,11,0.3);
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-update:hover {
            background: rgba(245,158,11,0.25);
            border-color: rgba(245,158,11,0.5);
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
            <h1>Edit Customer</h1>
            <p>Update the information for this customer record</p>
        </div>

        <div class="customer-meta">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
            </svg>
            Customer #<?= htmlspecialchars($id) ?> — <?= htmlspecialchars($customer['customer_name']) ?>
        </div>

        <div class="form-card">
            <form method="POST">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name"
                           value="<?= htmlspecialchars($customer['customer_name']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email"
                           value="<?= htmlspecialchars($customer['email']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone"
                           value="<?= htmlspecialchars($customer['phone']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" rows="3"><?= htmlspecialchars($customer['address']) ?></textarea>
                </div>

                <hr class="form-divider">

                <div class="form-actions">
                    <button type="submit" class="btn-update">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Update Customer
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