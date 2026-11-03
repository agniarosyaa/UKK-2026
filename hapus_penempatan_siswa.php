
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (strtolower($_SESSION['role'] ?? '') != 'admin') {
    echo "Anda tidak memiliki akses";
    exit;
}

include "config/koneksi.php";

// Periksa ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: penempatan_siswa.php?pesan=gagal");
    exit;
}

$id = (int) $_GET['id'];

// Hapus data penempatan siswa
$stmt = mysqli_prepare(
    $koneksi,
    "DELETE FROM t_kelas_siswa WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {

    $terhapus = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    if ($terhapus > 0) {
        header("Location: penempatan_siswa.php?pesan=hapus");
    } else {
        header("Location: penempatan_siswa.php?pesan=gagal");
    }

    exit;

} else {

    mysqli_stmt_close($stmt);

    header("Location: penempatan_siswa.php?pesan=gagal");
    exit;
}
?>
