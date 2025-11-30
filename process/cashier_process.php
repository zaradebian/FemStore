<?php
define ('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //ambil data dari form edit, add
    $id = $_POST['id']; 
    $action = $_POST['action']; 
    $name = $_POST['name']; 

if ($action == 'add') {
    $query = "INSERT INTO cashier VALUES ('$id', '$name')";
    mysqli_query($conn, $query);

} elseif ($action == 'edit') {
    $query = "UPDATE cashier SET name='$name' WHERE id=$id";
    mysqli_query($conn, $query);

} elseif ($action == 'delete') {
    $query = "DELETE FROM cashier WHERE id=$id";
    mysqli_query($conn, $query);
}

header("Location: ../pages/cashiers/list.php");
exit;
}