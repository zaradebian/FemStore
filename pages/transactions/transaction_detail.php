<?php
session_start(); 
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

if(isset($_GET['id'])){
    $id = $_GET['id'];
} else {
    $id = $_SESSION['id_transaksi'];
}

//query header transaksi
$header_query = mysqli_query($conn, "SELECT transactions.*, cashier.name AS cashiername 
FROM transactions 
JOIN cashier ON cashier.id = transactions.cashier_id 
WHERE transactions.id = " . $id);

$detail = mysqli_fetch_assoc($header_query);

//query detail produk
$query = mysqli_query($conn, "SELECT transaction_details.*, products.name AS productname, voucher_id 
FROM transaction_details 
JOIN products ON products.id = transaction_details.product_id
JOIN transactions ON transactions.id = transaction_details.transaction_id
LEFT JOIN voucher ON products.voucher_id = voucher.id
WHERE transaction_details.transaction_id = " . $id);

// cek status transaksi
$status = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status FROM transactions WHERE id = " . $id))['status'];
?>

<div class="container">
    <div class="page-header">
        <h2>Transaction Details</h2>
    </div>

    <?php
        if(isset($_POST['Payment'])){
    ?>
    <div class="form-container">
        <form action="/indomaret/process/transaction_process.php" method="POST">
            <input type="hidden" name="action" value="payment" />
            <input type="hidden" name="transaction_id" value="<?=$id?>" />
            
            <div class="form-group">
                <label for="payment_transaction">Payment Amount</label>
                <input type="number" 
                       id="payment_transaction"
                       name="payment_transaction" 
                       placeholder="Enter Amount" 
                       min="<?=$detail['total']?>"
                       required />
            </div>
            
            <div class="form-actions">
                <a href="transaction_detail.php?id=<?=$id?>" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Pay Now</button>
            </div>
        </form>
    </div>

    <?php
    }elseif($status == 'paid'){
    ?>
        <div class="alert alert-success">
            ✓ This transaction has been paid.
        </div>
    <?php
    }else{
    ?>
    <div class="form-container" style="max-width: 800px;">
        <form action="/indomaret/process/transaction_process.php" method="POST">
            <input type="hidden" name="transaction_id" value="<?=$id?>" />
            <input type="hidden" name="action" value="add" />

            <datalist id="products">
                <?php
            $query_product = mysqli_query($conn, "SELECT * FROM products LEFT JOIN transaction_details ON products.id = transaction_details.product_id AND transaction_details.transaction_id = '$id' WHERE transaction_details.product_id IS NULL AND stock > 0");

            while($product = mysqli_fetch_assoc($query_product)){
            ?>
                <option value="<?=$product['name']?>"><?=$product['stock']?></option>
                <?php
            }
            ?>
            </datalist>

            <div style="display: grid; grid-template-columns: 1fr 150px auto; gap: 12px; align-items: end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="product_name">Product Name</label>
                    <input type="text" 
                           id="product_name"
                           list="products" 
                           name="product_name" 
                           placeholder="Search Products..." 
                           autocomplete="off" 
                           required />
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="qty">Quantity</label>
                    <input type="number" 
                           id="qty"
                           name="qty" 
                           placeholder="Qty" 
                           min="1"
                           autocomplete="off" 
                           required />
                </div>

                <button type="submit" class="btn btn-primary">Add Product</button>
            </div>
        </form>

        <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #fce4ec; text-align: center;">
            <form action="" method="POST">
                <button type="submit" name="Payment" class="btn btn-secondary">Proceed to Payment</button>
            </form>
        </div>
    </div>
    <?php        
        }
    ?>

    <br>

    <div class="table-container">
        <table>
            <thead>
                <tr style="background: linear-gradient(135deg, #d16ba5 0%, #c774b2 100%);">
                    <th style="color: white; border: none;">
                        <?=$detail['date']?>
                    </th>
                    <th colspan="3" style="text-align: right; color: white; border: none;">
                        <?=$detail['code']?> | 
                        <?=$detail['cashiername']?> | 
                        ID: <?=$detail['cashier_id']?>
                    </th>
                </tr>
                <tr>
                    <th>Product Name</th>
                    <th style="text-align: center; width: 100px;">Qty</th>
                    <th style="text-align: right; width: 150px;">Price</th>
                    <th style="text-align: right; width: 150px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while($detail_product = mysqli_fetch_assoc($query)){
                ?>
                <tr>
                    <td>
                        <?=$detail_product['productname']?>
                    </td>

                    <td style="text-align: center;">
                        <?=$detail_product['qty']?>
                    </td>

                    <?php
                        $voucher_id = $detail_product['voucher_id'];
                        $diskon = mysqli_query($conn, "SELECT discount, max_discount FROM voucher WHERE id = '$voucher_id'");
                        if(mysqli_num_rows($diskon) > 0){
                            $diskon = mysqli_fetch_assoc($diskon);
                            $harga_diskon = $detail_product['prices'] - ($detail_product['prices'] * $diskon['discount'] / 100);
                            if($diskon['max_discount'] > 0 && ($detail_product['prices'] * $diskon['discount'] / 100) > $diskon['max_discount']){
                                $harga_diskon = $detail_product['prices'] - $diskon['max_discount'];
                            }             
                        ?>
                    <td style="text-align: right;">
                        <span class="price-original">Rp <?= number_format($detail_product['prices'], 0, ',', '.') ?></span>
                        <span class="price-discount">Rp <?= number_format($harga_diskon, 0, ',', '.') ?></span>
                    </td>
                    <?php
                        }else{
                        ?>
                    <td style="text-align: right;">
                        <span class="price-discount">Rp <?= number_format($detail_product['prices'], 0, ',', '.') ?></span>
                    </td>
                    <?php
                        }
                    ?>
                    <td style="text-align: right;">
                        <span class="price-discount">Rp <?=number_format($detail_product['sub_total'] - ($detail_product['sub_total'] * $detail_product['discount']/100) ,0,',','.')?></span>
                    </td>
                </tr>
                <?php
                } 
                ?>
            </tbody>
            <tfoot>
                <tr style="background: #fef9f3; font-weight: 600;">
                    <td colspan="3" style="text-align: right; color: #d16ba5; padding: 15px;">
                        <strong>Total</strong>
                    </td>
                    <td style="text-align: right; color: #d16ba5; padding: 15px;">
                        <strong>Rp <?=number_format($detail['total'] ,0,',','.')?></strong>
                    </td>
                </tr>

                <?php
                if($status == 'paid'){
                ?>
                <tr style="background: #fef9f3;">
                    <td colspan="3" style="text-align: right; padding: 12px;">
                        <strong>Pay</strong>
                    </td>
                    <td style="text-align: right; padding: 12px;">
                        <strong>Rp <?=number_format($detail['pay'] ,0,',','.')?></strong>
                    </td>
                </tr>
                <tr style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);">
                    <td colspan="3" style="text-align: right; color: #2e7d32; padding: 15px;">
                        <strong>Spare Change</strong>
                    </td>
                    <td style="text-align: right; color: #2e7d32; padding: 15px;">
                        <strong>Rp <?=number_format($detail['spare_change'] ,0,',','.')?></strong>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tfoot>
        </table>
    </div>

    <div class="action-buttons" style="margin-top: 30px; text-align: center;">
        <a href="list.php" class="btn btn-secondary">Back to Transactions</a>
    </div>
</div>

<?php
include ROOTPATH . "/includes/footer.php";
?>