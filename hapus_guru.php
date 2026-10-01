<?php

include "config/koneksi.php";

if (!isset($_GET['id'])) {
    header("Location: guru.php");
    exit;
}

$id = $_GET['id'];

$query = "DELETE FROM t_guru WHERE id = '$id'";

$result = mysqli_query($koneksi, $query);

if ($result) {
    header("Location: guru.php");
    exit;
} else {
    echo "Data guru gagal dihapus: " . mysqli_error($koneksi);
}

?>