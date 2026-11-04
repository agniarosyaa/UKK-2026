<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (strtolower($_SESSION['role'] ?? '') != 'admin') {
    exit("Anda tidak memiliki akses");
}

include "config/koneksi.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: kategori_pelanggaran.php");
    exit;
}

$id = (int) $_GET['id'];

try {

    // NONAKTIFKAN KATEGORI
    $query = "UPDATE t_pelanggaran_kategori
              SET status_aktif = 0
              WHERE id = ?";

    $stmt = mysqli_prepare($koneksi, $query);

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    // NOTIFIKASI BERHASIL
    $_SESSION['notifikasi'] = [
        'tipe' => 'danger',
        'pesan' => 'Kategori pelanggaran berhasil dihapus!'
    ];

    // KEMBALI KE DAFTAR KATEGORI
    header("Location: kategori_pelanggaran.php");
    exit;

} catch (mysqli_sql_exception $e) {

    error_log("Gagal menonaktifkan kategori: " . $e->getMessage());

    $_SESSION['notifikasi'] = [
        'tipe' => 'danger',
        'pesan' => 'Gagal menonaktifkan kategori pelanggaran!'
    ];

    header("Location: kategori_pelanggaran.php");
    exit;
}
?>