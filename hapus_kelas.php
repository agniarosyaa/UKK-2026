
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

    // Periksa apakah kelas masih digunakan di penempatan siswa
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

    if ($data['total'] > 0) {
        throw new Exception("Kelas masih digunakan oleh penempatan siswa.");
    }

    // Periksa apakah kelas masih digunakan di riwayat pelanggaran
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

    if ($data['total'] > 0) {
        throw new Exception("Kelas masih memiliki riwayat pelanggaran.");
    }

    // Periksa apakah kelas masih digunakan oleh wali kelas
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

    if ($data['total'] > 0) {
        throw new Exception("Kelas masih memiliki data wali kelas.");
    }

    // Hapus kelas jika tidak memiliki relasi
    $stmt = mysqli_prepare(
        $koneksi,
        "DELETE FROM t_kelas WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {

        mysqli_stmt_close($stmt);
        mysqli_commit($koneksi);

        header("Location: kelas.php?pesan=hapus");
        exit;

    } else {
        mysqli_stmt_close($stmt);
        throw new Exception("Data kelas tidak ditemukan.");
    }

} catch (Throwable $e) {

    mysqli_rollback($koneksi);

    $_SESSION['pesan_error'] = $e->getMessage();

    header("Location: kelas.php?pesan=gagal_hapus");
    exit;
}
?>
