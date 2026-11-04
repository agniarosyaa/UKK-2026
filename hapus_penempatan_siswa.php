
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

    $_SESSION['notifikasi'] = [
        'tipe' => 'danger',
        'pesan' => 'Data penempatan siswa gagal dihapus!'
    ];

    header("Location: penempatan_siswa.php");
    exit;
}

$id = (int) $_GET['id'];

// Hapus data penempatan siswa
$stmt = mysqli_prepare(
    $koneksi,
    "DELETE FROM t_kelas_siswa WHERE id = ?"
);

if (!$stmt) {
    $_SESSION['notifikasi'] = [
        'tipe' => 'danger',
        'pesan' => 'Data penempatan siswa gagal dihapus!'
    ];

    header("Location: penempatan_siswa.php");
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {

    $terhapus = mysqli_stmt_affected_rows($stmt);

    if ($terhapus > 0) {
        $_SESSION['notifikasi'] = [
            'tipe' => 'danger',
            'pesan' => 'Data penempatan siswa berhasil dihapus!'
        ];
    } else {
        $_SESSION['notifikasi'] = [
            'tipe' => 'warning',
            'pesan' => 'Data penempatan siswa tidak ditemukan!'
        ];
    }

} else {

    $_SESSION['notifikasi'] = [
        'tipe' => 'danger',
        'pesan' => 'Data penempatan siswa gagal dihapus!'
    ];

}

mysqli_stmt_close($stmt);

header("Location: penempatan_siswa.php");
exit;
?>
