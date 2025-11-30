<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$cashier = null;

if ($id > 0) {
    $result = mysqli_query($conn, "SELECT * FROM cashier WHERE id = $id");
    if ($result && mysqli_num_rows($result) > 0) {
        $cashier = mysqli_fetch_assoc($result);
    }
}

if (!$cashier) {
    echo '<div class="container"><div class="alert alert-error">Cashier not found.</div></div>';
    include ROOTPATH . "/includes/footer.php";
    exit;
}

$transaction_count = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as total FROM transactions WHERE cashier_id = {$cashier['id']}"))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Cashier - Indomaret</title>
    <link rel="stylesheet" href="/indomaret/assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h2>✏️ Edit Cashier</h2>
        </div>
        
        <div class="form-container">
            <form action="/indomaret/process/cashier_process.php" method="post">
                <input type="hidden" name="action" value="edit" />
                <input type="hidden" name="id" value="<?= htmlspecialchars($cashier['id']) ?>" />
                
                <div class="form-group">
                    <label for="id_display">Cashier ID</label>
                    <input 
                        type="text" 
                        id="id_display" 
                        value="#<?= htmlspecialchars($cashier['id']) ?>" 
                        disabled 
                        class="input-disabled"
                    />
                    <small class="form-hint">🔒 Cashier ID cannot be changed for data integrity</small>
                </div>
                
                <div class="form-group">
                    <label for="name">Cashier Name</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="<?= htmlspecialchars($cashier['name']) ?>" 
                        placeholder="Enter cashier full name"
                        required 
                    />
                    <small class="form-hint">📝 Update the cashier's full name</small>
                </div>
                
                <div class="form-actions">
                    <a href="list.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">💾 Update Cashier</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php include ROOTPATH . "/includes/footer.php"; ?>