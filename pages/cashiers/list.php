<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";
$result = mysqli_query($conn, "SELECT * FROM cashier ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Cashiers - Indomaret</title>
    <link rel="stylesheet" href="/indomaret/assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h2>Cashiers</h2>
        </div>
        
        <div class="action-buttons">
            <a href="add.php" class="btn btn-primary">+ Add New Cashier</a>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Cashier ID</th>
                        <th>Cashier Name</th>
                        <th>Status</th>
                        <th colspan="2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)) { 
                            //cek kasiir punya transaksi atau tidak
                            $query_check = mysqli_query($conn, 
                                "SELECT COUNT(*) as total FROM transactions WHERE cashier_id = {$row['id']}");
                            $transaction_count = mysqli_fetch_assoc($query_check)['total'];
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><span class="badge badge-stock">#<?= htmlspecialchars($row['id']) ?></span></td>
                        <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                        <td>
                            <?php if ($transaction_count > 0): ?>
                                <span class="status-badge status-active">Active (<?= $transaction_count ?> trans)</span>
                            <?php else: ?>
                                <span class="status-badge status-inactive">No Activity</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-secondary">Edit</a>
                                
                                <?php if ($transaction_count > 0): ?>
                                    <button class="btn btn-danger" disabled title="Cannot delete cashier with transactions">Delete</button>
                                <?php else: ?>
                                    <form action="/indomaret/process/cashier_process.php" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this cashier?')" style="display: inline;">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                    ?>
                    <tr>
                        <td colspan="5" class="empty-state">
                            <div class="empty-state-content">
                                <div class="empty-icon">👤</div>
                                <p>No cashiers found</p>
                                <a href="add.php" class="btn btn-primary">Add Your First Cashier</a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php include ROOTPATH . "/includes/footer.php"; ?>