
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

// Periksa ID kelas
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: kelas.php");
    exit;
}

$id = (int) $_GET['id'];

mysqli_begin_transaction($koneksi);

try {

    // Periksa penempatan siswa
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT COUNT(*) AS total
         FROM t_kelas_siswa
         WHERE kelas_id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    $penempatan = $data['total'];

    // Periksa riwayat pelanggaran
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT COUNT(*) AS total
         FROM t_pelanggaran_siswa
         WHERE kelas_id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    $pelanggaran = $data['total'];

    // Periksa wali kelas
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT COUNT(*) AS total
         FROM t_wali_kelas
         WHERE kelas_id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    $wali = $data['total'];

    // Jika masih memiliki relasi, nonaktifkan kelas
    if ($penempatan > 0 || $pelanggaran > 0 || $wali > 0) {

        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE t_kelas SET status_aktif = 0 WHERE id = ?"
        );

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Gagal menonaktifkan kelas.");
        }

        mysqli_stmt_close($stmt);
        mysqli_commit($koneksi);

        $_SESSION['notifikasi'] = [
            'tipe' => 'danger',
            'pesan' => 'Kelas berhasil dihapus!'
        ];

        header("Location: kelas.php");
        exit;
    }

    // Jika tidak memiliki relasi, hapus permanen
    $stmt = mysqli_prepare(
        $koneksi,
        "DELETE FROM t_kelas WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Gagal menghapus data kelas.");
    }

    if (mysqli_stmt_affected_rows($stmt) > 0) {

        mysqli_stmt_close($stmt);
        mysqli_commit($koneksi);

        $_SESSION['notifikasi'] = [
            'tipe' => 'danger',
            'pesan' => 'Data kelas berhasil dihapus!'
        ];

        header("Location: kelas.php");
        exit;

    } else {
        mysqli_stmt_close($stmt);
        throw new Exception("Data kelas tidak ditemukan.");
    }

} catch (Throwable $e) {

    mysqli_rollback($koneksi);

    $_SESSION['notifikasi'] = [
        'tipe' => 'warning',
        'pesan' => $e->getMessage()
    ];

    header("Location: kelas.php");
    exit;
}
?>
