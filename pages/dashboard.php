<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

//statistik
$total_products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products"))['total'];
$total_cashiers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM cashier"))['total'];
$total_transactions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM transactions"))['total'];
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) as total FROM transactions"))['total'] ?? 0;

$low_stock = mysqli_query($conn, "SELECT * FROM products WHERE stock < 10 ORDER BY stock ASC LIMIT 5");

//transaksi
$recent_transactions = mysqli_query($conn, "
    SELECT t.*, c.name as cashier_name 
    FROM transactions t 
    LEFT JOIN cashier c ON t.cashier_id = c.id 
    ORDER BY t.date DESC 
    LIMIT 5
");

$today_sales = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as count, SUM(total) as total 
    FROM transactions 
    WHERE DATE(date) = CURDATE()
"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Indomaret</title>
    <link rel="stylesheet" href="/indomaret/assets/css/style.css">
    <link rel="stylesheet" href="/indomaret/assets/css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="welcome-section">
            <div class="welcome-content">
                <h1>Welcome Back! 👋</h1>
                <p>Here's what's happening with your store today</p>
            </div>
            <div class="welcome-date">
                <span class="date-label">Today</span>
                <span class="date-value"><?= date('d F Y') ?></span>
            </div>
        </div>
        <!-- status -->
        <div class="stats-grid">
            <div class="stat-card stat-primary">
                <div class="stat-icon">💰</div>
                <div class="stat-content">
                    <h3>Total Revenue</h3>
                    <p class="stat-value">Rp <?= number_format($total_revenue, 0, ',', '.') ?></p>
                    <span class="stat-label">All time</span>
                </div>
            </div>

            <div class="stat-card stat-success">
                <div class="stat-icon">📊</div>
                <div class="stat-content">
                    <h3>Today's Sales</h3>
                    <p class="stat-value"><?= $today_sales['count'] ?? 0 ?> transactions</p>
                    <span class="stat-label">Rp <?= number_format($today_sales['total'] ?? 0, 0, ',', '.') ?></span>
                </div>
            </div>

            <div class="stat-card stat-info">
                <div class="stat-icon">📦</div>
                <div class="stat-content">
                    <h3>Products</h3>
                    <p class="stat-value"><?= $total_products ?></p>
                    <span class="stat-label">Total items</span>
                </div>
            </div>

            <div class="stat-card stat-warning">
                <div class="stat-icon">👥</div>
                <div class="stat-content">
                    <h3>Cashiers</h3>
                    <p class="stat-value"><?= $total_cashiers ?></p>
                    <span class="stat-label">Active staff</span>
                </div>
            </div>
        </div>

        <div class="content-grid">
            <div class="dashboard-card">
                <div class="card-header">
                    <h2>💳 Recent Transactions</h2>
                    <a href="/indomaret/pages/transactions/list.php" class="view-all">View All →</a>
                </div>
                <div class="card-content">
                    <?php if (mysqli_num_rows($recent_transactions) > 0): ?>
                        <div class="transaction-list">
                            <?php while($row = mysqli_fetch_assoc($recent_transactions)): ?>
                            <div class="transaction-item">
                                <div class="transaction-info">
                                    <span class="transaction-id">#<?= $row['cashier_id'] ?></span>
                                    <span class="transaction-cashier">by <?= htmlspecialchars($row['cashier_name']) ?></span>
                                </div>
                                <div class="transaction-details">
                                    <span class="transaction-price">Rp <?= number_format($row['total'], 0, ',', '.') ?></span>
                                    <span class="transaction-date"><?= date('d M, H:i', strtotime($row['date'])) ?></span>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state-small">
                            <p>No transactions yet</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="dashboard-card alert-card">
                <div class="card-header">
                    <h2>⚠️ Low Stock Alert</h2>
                    <a href="/indomaret/pages/products/list.php" class="view-all" style="margin-left: 10px;">Manage →</a>
                </div>
                <div class="card-content">
                    <?php if (mysqli_num_rows($low_stock) > 0): ?>
                        <div class="stock-list">
                            <?php while($row = mysqli_fetch_assoc($low_stock)): ?>
                            <div class="stock-item">
                                <div class="stock-info">
                                    <span class="stock-name"><?= htmlspecialchars($row['name']) ?></span>
                                    <span class="stock-badge <?= $row['stock'] < 5 ? 'badge-critical' : 'badge-warning' ?>">
                                        <?= $row['stock'] ?> left
                                    </span>
                                </div>
                                <a href="/indomaret/pages/products/edit.php?id=<?= $row['id'] ?>" class="stock-action">Restock</a>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state-small">
                            <p>✅ All products are well stocked</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2>⚡ Quick Actions</h2>
            <div class="action-grid">
                <a href="/indomaret/pages/transactions/add.php" class="action-card action-primary">
                    <div class="action-icon">🛒</div>
                    <h3>New Transaction</h3>
                    <p>Start a new sale</p>
                </a>

                <a href="/indomaret/pages/products/add.php" class="action-card action-success">
                    <div class="action-icon">➕</div>
                    <h3>Add Product</h3>
                    <p>Add new item</p>
                </a>

                <a href="/indomaret/pages/products/list.php" class="action-card action-info">
                    <div class="action-icon">📋</div>
                    <h3>View Products</h3>
                    <p>Manage inventory</p>
                </a>

                <a href="/indomaret/pages/cashiers/list.php" class="action-card action-warning">
                    <div class="action-icon">👤</div>
                    <h3>Manage Cashiers</h3>
                    <p>Staff management</p>
                </a>
            </div>
        </div>
    </div>
</body>
</html>

<?php include ROOTPATH . "/includes/footer.php"; ?>