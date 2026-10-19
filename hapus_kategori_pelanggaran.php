
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

// Periksa ID kategori
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: kategori_pelanggaran.php");
    exit;
}

$id = (int) $_GET['id'];

// Periksa apakah kategori tersedia
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM t_pelanggaran_kategori WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$kategori = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$kategori) {
    header("Location: kategori_pelanggaran.php");
    exit;
}

// Hapus kategori
try {
    $stmt = mysqli_prepare(
        $koneksi,
        "DELETE FROM t_pelanggaran_kategori WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $jumlah = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    if ($jumlah > 0) {
        header("Location: kategori_pelanggaran.php?pesan=hapus");
    } else {
        header("Location: kategori_pelanggaran.php?pesan=gagal_hapus");
    }
    exit;

} catch (mysqli_sql_exception $e) {
    if (isset($stmt)) {
        mysqli_stmt_close($stmt);
    }

    header("Location: kategori_pelanggaran.php?pesan=gagal_hapus");
    exit;
}
?>
