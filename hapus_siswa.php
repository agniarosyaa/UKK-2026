
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
    // Hapus penempatan siswa terlebih dahulu
    $stmt1 = mysqli_prepare(
        $koneksi,
        "DELETE FROM t_kelas_siswa WHERE siswa_id = ?"
    );

    mysqli_stmt_bind_param($stmt1, "i", $id);
    mysqli_stmt_execute($stmt1);
    mysqli_stmt_close($stmt1);

    // Kemudian hapus data siswa
    $stmt2 = mysqli_prepare(
        $koneksi,
        "DELETE FROM t_siswa WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt2, "i", $id);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);

    mysqli_commit($koneksi);

    header("Location: siswa.php");
    exit;

} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    die("Gagal menghapus siswa. Data mungkin masih digunakan pada tabel lain.");
}
?>
