
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

// Periksa apakah kelas tersedia
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM t_kelas WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$kelas = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

if (!$kelas) {
    header("Location: kelas.php");
    exit;
}

// Hapus kelas
try {
    $stmt = mysqli_prepare(
        $koneksi,
        "DELETE FROM t_kelas WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $terhapus = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    if ($terhapus > 0) {
        header("Location: kelas.php?pesan=hapus");
    } else {
        header("Location: kelas.php?pesan=gagal_hapus");
    }
    exit;

} catch (mysqli_sql_exception $e) {
    if (isset($stmt)) {
        mysqli_stmt_close($stmt);
    }

    header("Location: kelas.php?pesan=gagal_hapus");
    exit;
}
?>
