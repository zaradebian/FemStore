<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Indomaret</title>
    <link rel="stylesheet" href="/indomaret/assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h2>➕ Add New Product</h2>
        </div>
        
        <div class="form-container">
            <form action="/indomaret/process/product_process.php" method="post">
                <input type="hidden" name="action" value="add" />
                
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter product name" required />
                </div>
                
                <div class="form-group">
                    <label for="prices">Price</label>
                    <input type="number" id="prices" name="prices" placeholder="Enter price" min="0" required />
                </div>
                
                <div class="form-group">
                    <label for="voucher_id">Voucher (Optional)</label>
                    <input type="text" id="voucher_id" list="voucher_list" name="voucher_id" placeholder="Select voucher" />
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
                    <input type="number" id="stock" name="stock" placeholder="Enter stock quantity" min="0" required />
                </div>
                
                <div class="form-actions">
                    <a href="list.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php include ROOTPATH . "/includes/footer.php"; ?>