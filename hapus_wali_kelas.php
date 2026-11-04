
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

// Periksa ID wali kelas
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    $_SESSION['notifikasi'] = [
        'tipe' => 'danger',
        'pesan' => 'Data wali kelas gagal dihapus!'
    ];

    header("Location: wali_kelas.php");
    exit;
}

$id = (int) $_GET['id'];

// Hapus data wali kelas
$stmt = mysqli_prepare(
    $koneksi,
    "DELETE FROM t_wali_kelas WHERE id = ?"
);

if (!$stmt) {

    $_SESSION['notifikasi'] = [
        'tipe' => 'danger',
        'pesan' => 'Data wali kelas gagal dihapus!'
    ];

    header("Location: wali_kelas.php");
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {

    $jumlah = mysqli_stmt_affected_rows($stmt);

    if ($jumlah > 0) {
        $_SESSION['notifikasi'] = [
            'tipe' => 'danger',
            'pesan' => 'Data wali kelas berhasil dihapus!'
        ];
    } else {
        $_SESSION['notifikasi'] = [
            'tipe' => 'warning',
            'pesan' => 'Data wali kelas tidak ditemukan!'
        ];
    }

} else {

    $_SESSION['notifikasi'] = [
        'tipe' => 'danger',
        'pesan' => 'Data wali kelas gagal dihapus!'
    ];

}

mysqli_stmt_close($stmt);

header("Location: wali_kelas.php");
exit;
?>
