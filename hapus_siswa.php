
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

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: siswa.php");
    exit;
}

$id = (int) $_GET['id'];

mysqli_begin_transaction($koneksi);

try {

    // Hapus data pelanggaran siswa terlebih dahulu
    $stmt1 = mysqli_prepare(
        $koneksi,
        "DELETE FROM t_pelanggaran_siswa WHERE siswa_id = ?"
    );

    mysqli_stmt_bind_param($stmt1, "i", $id);
    mysqli_stmt_execute($stmt1);
    mysqli_stmt_close($stmt1);

    // Hapus penempatan siswa
    $stmt2 = mysqli_prepare(
        $koneksi,
        "DELETE FROM t_kelas_siswa WHERE siswa_id = ?"
    );

    mysqli_stmt_bind_param($stmt2, "i", $id);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);

    // Hapus data siswa
    $stmt3 = mysqli_prepare(
        $koneksi,
        "DELETE FROM t_siswa WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt3, "i", $id);
    mysqli_stmt_execute($stmt3);
    mysqli_stmt_close($stmt3);

 mysqli_commit($koneksi);

$_SESSION['notifikasi'] = [
    'tipe' => 'danger',
    'pesan' => 'Data siswa berhasil dihapus!'
];

header("Location: siswa.php");
exit;
} catch (Throwable $e) {

    mysqli_rollback($koneksi);

    die("Gagal menghapus siswa: " . $e->getMessage());
}
?>
