<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] != 'admin') {
    echo "Anda tidak memiliki akses";
    exit;
}

include "config/koneksi.php";

$id = $_GET['id'];

mysqli_query($koneksi, "DELETE FROM t_siswa WHERE id='$id'");

header("Location: siswa.php");
exit;

?>