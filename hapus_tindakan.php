
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (strtolower($_SESSION['role'] ?? '') != 'guru') {
    exit("Anda tidak memiliki akses");
}

include "config/koneksi.php";

/* AMBIL ID TINDAKAN */
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

/* VALIDASI ID */
if ($id <= 0) {
    $_SESSION['notifikasi'] = [
        'tipe' => 'danger',
        'pesan' => 'ID tindakan tidak valid.'
    ];

    header("Location: tindakan.php");
    exit;
}

/* CEK DATA TINDAKAN */
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM t_pelanggaran_siswa WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$data = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($data) == 0) {
    $_SESSION['notifikasi'] = [
        'tipe' => 'warning',
        'pesan' => 'Data tindakan tidak ditemukan.'
    ];

    header("Location: tindakan.php");
    exit;
}

/* PROSES HAPUS */
$stmtHapus = mysqli_prepare(
    $koneksi,
    "DELETE FROM t_pelanggaran_siswa WHERE id = ?"
);

mysqli_stmt_bind_param($stmtHapus, "i", $id);

if (mysqli_stmt_execute($stmtHapus)) {

    $_SESSION['notifikasi'] = [
        'tipe' => 'success',
        'pesan' => 'Data tindakan berhasil dihapus.'
    ];

} else {

    $_SESSION['notifikasi'] = [
        'tipe' => 'danger',
        'pesan' => 'Data tindakan gagal dihapus.'
    ];
}

/* KEMBALI KE HALAMAN TINDAKAN */
header("Location: tindakan.php");
exit;
?>
