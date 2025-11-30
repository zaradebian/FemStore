<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null;

if ($id > 0) {
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    }
}

if (!$product) {
    echo '<div class="container"><div class="alert alert-error">Product not found.</div></div>';
    include ROOTPATH . "/includes/footer.php";
    exit;
}
?>

<link rel="stylesheet" href="/indomaret/assets/css/style.css">

<div class="container">
    <div class="page-header">
        <h2>✏️ Edit Product</h2>
    </div>
    
    <div class="form-container">
        <form action="/indomaret/process/product_process.php" method="post">
            <input type="hidden" name="action" value="edit" />
            <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>" />
            
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required />
            </div>
            
            <div class="form-group">
                <label for="prices">Price</label>
                <input type="number" id="prices" name="prices" value="<?= htmlspecialchars($product['prices']) ?>" min="0" required />
            </div>
            
            <div class="form-group">
                <label for="voucher_id">Voucher</label>
                <input type="text" id="voucher_id" list="voucher_list" name="voucher_id" value="<?= htmlspecialchars($product['voucher_id']) ?>" />
                <datalist id="voucher_list">
                    <?php
                    $query = "SELECT * FROM voucher";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['name']) . " - " . $row['discount'] . "%</option>";
                    }
                    ?>
                </datalist>
            </div>
            
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" min="0" required />
            </div>
            
            <div class="form-actions">
                <a href="list.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Product</button>
            </div>
        </form>
    </div>
</div>

<?php include ROOTPATH . "/includes/footer.php"; ?>