<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $action         = $_POST['action'];
    $transaction_id = $_POST['transaction_id'] ?? null;

    //add product
    if ($action == 'add') {

    $product_name = $_POST['product_name'];
    $qty = $_POST['qty'];

    $product = mysqli_fetch_assoc(mysqli_query(
        $conn,
        "SELECT products.*, products.id AS product_id, voucher.discount, voucher.max_discount
         FROM products 
         LEFT JOIN voucher ON products.voucher_id = voucher.id 
         WHERE products.name = '$product_name'"
    ));

    if (!$product) {
        die("Product not found.");
    }

    $product_id = $product['product_id'];
    $unit_price = $product['prices'];
    $discount   = $product['discount'] ?? 0;

    $sub_total = $unit_price * $qty;

    $sum_total = mysqli_fetch_assoc(mysqli_query(
        $conn,
        "SELECT SUM(sub_total - (sub_total * discount/100)) AS total 
         FROM transaction_details 
         WHERE transaction_id = $transaction_id"
    ))['total'] ?? 0;

    $new_total = ($discount > 0)
        ? $sum_total + ($sub_total - ($sub_total * $discount / 100))
        : $sum_total + $sub_total;

    mysqli_query($conn,
        "INSERT INTO transaction_details 
        (transaction_id, product_id, prices, discount, qty, sub_total)
        VALUES 
        ('$transaction_id', '$product_id', '$unit_price', '$discount', '$qty', '$sub_total')"
    );

    mysqli_query($conn,
        "UPDATE transactions SET total = '$new_total' WHERE id = $transaction_id"
    );

    mysqli_query($conn,
        "UPDATE products SET stock = stock - $qty WHERE id = $product_id"
    );

    header("Location: /indomaret/pages/transactions/transaction_detail.php?id=" . $transaction_id);
    exit();

}

//payment process
    elseif ($action == 'payment') {

        $payment_amount = $_POST['payment_transaction'];

        $total = mysqli_fetch_assoc(mysqli_query(
            $conn,
            "SELECT total FROM transactions WHERE id = $transaction_id"
        ))['total'];

        $spare_change = $payment_amount - $total;

        mysqli_query($conn,
            "UPDATE transactions 
             SET status = 'paid', pay = '$payment_amount', spare_change = '$spare_change'
             WHERE id = $transaction_id"
        );
    }

    //delete transaction
    elseif ($action == 'delete') {

    // delete detail transaksi
    mysqli_query($conn, "DELETE FROM transaction_details WHERE transaction_id = $transaction_id");

    // delete transaksi utama
    mysqli_query($conn, "DELETE FROM transactions WHERE id = $transaction_id");
    header("Location: /indomaret/pages/transactions/list.php");
    exit();
}
    // redirect default
    header("Location: ../pages/transactions/transaction_detail.php?id=" . $transaction_id);
    exit;
}
?>
