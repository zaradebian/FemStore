<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

$result = mysqli_query($conn, "SELECT * FROM transactions ORDER BY id ASC");
?>

<div class="container">
    <div class="page-header">
        <h2>Transactions</h2>
    </div>
    
    <div class="action-buttons">
        <a href="add.php" class="btn btn-primary">+Add Transactions</a>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Date</th>                 
                    <th>Transaction Code</th>            
                    <th>Cashier Id</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)){ ?>
                <tr>
                    <td><?= $no++?></td>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= htmlspecialchars($row['code']) ?></td>
                    <td><?= htmlspecialchars($row['cashier_id']) ?></td>
                    <td><span class="price-discount">Rp <?= number_format($row['total'], 0, ',', '.') ?></span></td>
                    <td>
                        <div class="table-actions">
                            <a href="transaction_detail.php?id=<?= $row['id'] ?>" class="btn btn-secondary">Details</a>

                            <?php if ($row['status'] === 'pending') { ?>
                                <form action="/indomaret/process/transaction_process.php"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this transaction?')">

                                    <input type="hidden" name="transaction_id" value="<?= $row['id'] ?>">
                                    <input type="hidden" name="action" value="delete">

                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            <?php } else { ?>
                                <button class="btn btn-danger" disabled>Delete</button>
                            <?php } ?>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include ROOTPATH . "/includes/footer.php"; ?>
