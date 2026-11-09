<?php
include "config/koneksi.php";

$id = 28;
$password_baru = "12345678";

$hash = password_hash($password_baru, PASSWORD_DEFAULT);

$sql = "UPDATE t_users SET password = ? WHERE id = ?";

$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "si", $hash, $id);

if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) >= 0) {
        echo "Password Ari Sandriana berhasil diperbarui.";
    }
} else {
    echo "Gagal memperbarui password.";
}

mysqli_stmt_close($stmt);
?>