
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
    header("Location: wali_kelas.php");
    exit;
}

$id = (int) $_GET['id'];

// Periksa apakah data wali kelas tersedia
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM t_wali_kelas WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$data) {
    header("Location: wali_kelas.php");
    exit;
}

// Hapus data wali kelas
try {
    $stmt = mysqli_prepare(
        $koneksi,
        "DELETE FROM t_wali_kelas WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $jumlah = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    if ($jumlah > 0) {
        header("Location: wali_kelas.php?pesan=hapus");
    } else {
        header("Location: wali_kelas.php?pesan=gagal_hapus");
    }
    exit;

} catch (mysqli_sql_exception $e) {
    if (isset($stmt)) {
        mysqli_stmt_close($stmt);
    }

    header("Location: wali_kelas.php?pesan=gagal_hapus");
    exit;
}
?>
