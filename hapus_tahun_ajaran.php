
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

// Periksa ID tahun ajaran
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: tahun_ajaran.php");
    exit;
}

$id = (int) $_GET['id'];

// Siapkan query hapus
$stmt = mysqli_prepare(
    $koneksi,
    "DELETE FROM t_tahun_ajaran WHERE id = ?"
);

if (!$stmt) {
    $_SESSION['notifikasi'] = [
        'tipe' => 'danger',
        'pesan' => 'Gagal menyiapkan query hapus tahun ajaran.'
    ];

    header("Location: tahun_ajaran.php");
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $id);

try {
    if (mysqli_stmt_execute($stmt)) {

        if (mysqli_stmt_affected_rows($stmt) > 0) {

            $_SESSION['notifikasi'] = [
                'tipe' => 'danger',
                'pesan' => 'Data tahun ajaran berhasil dihapus!'
            ];

        } else {

            $_SESSION['notifikasi'] = [
                'tipe' => 'warning',
                'pesan' => 'Data tahun ajaran tidak ditemukan.'
            ];
        }

        mysqli_stmt_close($stmt);

        header("Location: tahun_ajaran.php");
        exit;

    } else {
        throw new Exception("Gagal menghapus data tahun ajaran.");
    }

} catch (Throwable $e) {

    mysqli_stmt_close($stmt);

    $_SESSION['notifikasi'] = [
        'tipe' => 'danger',
        'pesan' => 'Data tahun ajaran gagal dihapus. Pastikan data tidak sedang digunakan.'
    ];

    header("Location: tahun_ajaran.php");
    exit;
}
?>