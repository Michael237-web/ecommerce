// In admin-panel.php, change these lines:

// Change from:
$totalRevenue = $pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE status != 'cancelled'")->fetchColumn();

// To:
$totalRevenue = $pdo->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status != 'cancelled'")->fetchColumn();

// Change from:
$ordersStmt = $pdo->query("
    SELECT o.*, u.fullname as customer_name, u.email as customer_email 
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.id 
    ORDER BY o.id DESC
");

// Make sure the orders table has 'total' column, not 'total_amount'