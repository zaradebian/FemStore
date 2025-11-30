<?php
ob_start();

session_start(); 
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";
if (@$_POST['selanjutnya']) {
    $query = mysqli_query($conn, "SELECT id, code FROM transactions ORDER BY id DESC LIMIT 1");
    $data = mysqli_fetch_assoc($query);
    if ($data) {
        $kode_terakhir = $data['code'];
        $urutan = (int) substr($kode_terakhir, 3, 4);
        $urutan++;
        $kode_transaksi = "TRX" . str_pad($urutan, 4, "0", STR_PAD_LEFT); // TRX0006
        
        $id_terakhir = $data['id'];
        $id = $id_terakhir + 1;
    } else {
        $kode_transaksi = "TRX0001";
    }
    $nama_kasir = $_POST['nama_kasir'];
    $kasir = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM cashier WHERE name='$nama_kasir'"));
    $cashier_id = $kasir['id'];
    date_default_timezone_set('Asia/Makassar');
    $tanggal = date("Y-m-d H:i:s");
    $total = 0;
    $insert = mysqli_query($conn, "INSERT INTO transactions (id, date, code, cashier_id, total) 
                                   VALUES ('$id', '$tanggal', '$kode_transaksi', '$cashier_id', '$total')");
    $_SESSION['id_transaksi'] = $id;
    if (!$insert) {
        echo "<p>Gagal menyimpan transaksi: " . mysqli_error($conn) . "</p>";
    } else {
        header('Location: transaction_detail.php');
        exit;
    }
}

?>
<div class="container">
    <div class="page-header">
        <h2>Add New Transaction</h2>
    </div>
    
    <div class="form-container">
        <form action="" method="POST">
            <div class="form-group">
                <label for="nama_kasir">Select Cashier</label>
                <input type="text" 
                       id="nama_kasir"
                       name="nama_kasir" 
                       placeholder="Type cashier name..." 
                       list="kasirList"
                       required 
                       autocomplete="off">
                
                <!-- data nama kasir -->
                <datalist id="kasirList">
                    <?php
                    $qKasir = mysqli_query($conn, "SELECT name FROM cashier ORDER BY name ASC");
                    while($k = mysqli_fetch_assoc($qKasir)) {
                        echo "<option value='" . htmlspecialchars($k['name']) . "'></option>";
                    }
                    ?>
                </datalist>
                <small style="display: block; margin-top: 8px; color: #999; font-size: 13px;">
                    Start typing to see available cashiers
                </small>
            </div>

            <div style="background: #fef9f3; padding: 20px; border-radius: 12px; margin-bottom: 25px; border-left: 4px solid #d16ba5;">
                <p style="margin: 0; color: #666; font-size: 14px;">
                    <strong style="color: #d16ba5;">ℹ️ Note:</strong> After clicking "Next", you will be redirected to the transaction details page where you can add products to this transaction.
                </p>
            </div>

            <div class="form-actions">
                <a href="list.php" class="btn btn-secondary">Cancel</a>
                <input type="submit" name="selanjutnya" class="btn btn-primary" value="Next →">
            </div>
        </form>
    </div>

    <!-- preview -->
    <div style="max-width: 600px; margin: 30px auto 0; background: linear-gradient(135deg, #ffeef8 0%, #fff0f6 100%); padding: 25px; border-radius: 15px; text-align: center;">
        <h3 style="color: #d16ba5; font-size: 18px; margin-bottom: 15px; font-weight: 600;">
            📋 Transaction Flow
        </h3>
        <div style="display: flex; justify-content: space-around; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="flex: 1; min-width: 120px;">
                <div style="background: #d16ba5; color: white; padding: 10px; border-radius: 8px; font-size: 13px; font-weight: 500;">
                    1. Select Cashier
                </div>
            </div>
            <span style="color: #d16ba5; font-size: 20px;">→</span>
            <div style="flex: 1; min-width: 120px;">
                <div style="background: white; color: #d16ba5; padding: 10px; border-radius: 8px; font-size: 13px; font-weight: 500; border: 2px solid #d16ba5;">
                    2. Add Products
                </div>
            </div>
            <span style="color: #d16ba5; font-size: 20px;">→</span>
            <div style="flex: 1; min-width: 120px;">
                <div style="background: white; color: #d16ba5; padding: 10px; border-radius: 8px; font-size: 13px; font-weight: 500; border: 2px solid #d16ba5;">
                    3. Payment
                </div>
            </div>
        </div>
    </div>

    <!-- Cashier List Preview -->
    <div style="max-width: 600px; margin: 20px auto 0;">
        <details style="background: white; padding: 20px; border-radius: 15px; border: 2px solid #fce4ec;">
            <summary style="cursor: pointer; color: #d16ba5; font-weight: 600; font-size: 14px; user-select: none;">
                👥 View Available Cashiers (<?php 
                    $count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM cashier");
                    $count = mysqli_fetch_assoc($count_query);
                    echo $count['total'];
                ?>)
            </summary>
            <div style="margin-top: 15px; max-height: 200px; overflow-y: auto;">
                <?php
                $qKasir2 = mysqli_query($conn, "SELECT id, name FROM cashier ORDER BY name ASC");
                if (mysqli_num_rows($qKasir2) > 0) {
                    echo "<ul style='list-style: none; padding: 0; margin: 0;'>";
                    while($k = mysqli_fetch_assoc($qKasir2)) {
                        echo "<li style='padding: 8px 12px; margin: 4px 0; background: #fef9f3; border-radius: 8px; color: #666; font-size: 14px;'>";
                        echo "<span class='badge badge-stock' style='margin-right: 8px;'>ID: " . $k['id'] . "</span>";
                        echo htmlspecialchars($k['name']);
                        echo "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p style='text-align: center; color: #999; padding: 20px;'>No cashiers available. Please add cashiers first.</p>";
                }
                ?>
            </div>
        </details>
    </div>
</div>

</div>
<?php include ROOTPATH . "/includes/footer.php"; ?>