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
    <title>Add Cashier - Indomaret</title>
    <link rel="stylesheet" href="/indomaret/assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h2>➕ Add New Cashier</h2>
        </div>
        
        <div class="form-container">
            <form action="/indomaret/process/cashier_process.php" method="post">
                <input type="hidden" name="action" value="add" />
                
                <div class="form-group">
                    <label for="id">Cashier ID</label>
                    <input 
                        type="number" 
                        id="id" 
                        name="id" 
                        placeholder="Enter unique cashier ID (e.g., 1001)" 
                        min="1"
                        required 
                    />
                    <small class="form-hint">💡 This ID used for identification</small>
                </div>
                
                <div class="form-group">
                    <label for="name">Cashier Name</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        placeholder="Enter cashier full name" 
                        required 
                    />
                    <small class="form-hint">📝 Enter the full name of the cashier</small>
                </div>
                
                <div class="form-actions">
                    <a href="list.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">💾 Save Cashier</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php include ROOTPATH . "/includes/footer.php"; ?>