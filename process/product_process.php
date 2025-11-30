<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; 
    $name = $_POST['name'];
    $prices = $_POST['prices'];
    $stock = $_POST['stock'];
    $action = $_POST['action'];

    // voucher boleh null
    if ($_POST['voucher_id'] === '' || $_POST['voucher_id'] === null) {
        $voucher = "NULL";
    } else {
        $voucher_id = mysqli_real_escape_string($conn, $_POST['voucher_id']);
        $voucher = "'$voucher_id'";
    }

    if ($action == 'add') { 
        $query = "INSERT INTO products (id, name, prices, voucher_id, stock) 
                  VALUES ('$id', '$name', '$prices', $voucher, '$stock')";
        mysqli_query($conn, $query);

    } elseif ($action == 'edit') {
        $query = "UPDATE products 
                  SET name='$name', prices='$prices', voucher_id=$voucher, stock='$stock' 
                  WHERE id='$id'";
        mysqli_query($conn, $query);

    } elseif ($action == 'delete') {
        $query = "DELETE FROM products WHERE id='$id'";
        mysqli_query($conn, $query);
    }

    header("Location: ../pages/products/list.php");
    exit;
}
?>
