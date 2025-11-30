<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "database_indomaret";

//buat koneksi
$conn = mysqli_connect($host, $user, $password, $dbname);

//cek apakah connect
if (!$conn) {
    die("Failed to connect: ") . mysqli_connect_error();
}
?>