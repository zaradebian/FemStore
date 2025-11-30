<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";
$result = mysqli_query($conn, "SELECT * FROM products ORDER BY id ASC");
?>

<link rel="stylesheet" href="/indomaret/assets/css/style.css">

<div class="container">
    <div class="page-header">
        <h2>Products</h2>
    </div>
    
    <div class="action-buttons">
        <a href="add.php" class="btn btn-primary">+ Add New Product</a>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Product Name</th>                 
                    <th>Price</th>            
                    <th>Voucher</th>
                    <th>Stock</th>
                    <th colspan="2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <?php
                        $voucher_id = $row['voucher_id'];
                        $diskon = mysqli_query($conn, "SELECT discount, max_discount FROM voucher WHERE id = '$voucher_id'");
                        if(mysqli_num_rows($diskon) > 0) {
                            $diskon = mysqli_fetch_assoc($diskon);
                            $harga_diskon = $row['prices'] - ($row['prices'] * $diskon['discount'] / 100);
                            if($diskon['max_discount'] > 0 && ($row['prices'] * $diskon['discount'] / 100) > $diskon['max_discount']) {
                                $harga_diskon = $row['prices'] - $diskon['max_discount'];
                            }             
                    ?>
                    <td>
                        <span class="price-original">Rp <?= number_format($row['prices'], 0, ',', '.') ?></span>
                        <span class="price-discount">Rp <?= number_format($harga_diskon, 0, ',', '.') ?></span>
                    </td>
                    <?php
                        } else {
                    ?>
                    <td><span class="price-discount">Rp <?= number_format($row['prices'], 0, ',', '.') ?></span></td>
                    <?php
                        }
                    ?>

                    <td><?= htmlspecialchars($row['voucher_id']) ?></td>
                    <td><span class="badge badge-stock"><?= htmlspecialchars($row['stock']) ?> pcs</span></td>
                    <td>
                        <div class="table-actions">
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-secondary">Edit</a>
                            
                            <?php
                            $query_cek = mysqli_query($conn, 
                                "SELECT transaction_details.product_id 
                                 FROM products 
                                 JOIN transaction_details 
                                 ON products.id = transaction_details.product_id 
                                 WHERE transaction_details.product_id = {$row['id']}");
                            
                            if(mysqli_num_rows($query_cek) > 0) {
                                echo "<button class='btn btn-danger' disabled>Delete</button>";
                            } else {
                            ?>
                            <form action="/indomaret/process/product_process.php" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this product?')" style="display: inline;">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                            <?php
                            }
                            ?>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include ROOTPATH . "/includes/footer.php"; ?>