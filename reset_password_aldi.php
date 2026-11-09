<?php
include "config/koneksi.php";

$email = "user32@sekolah.test";
$password_baru = "12345678";

$hash = password_hash($password_baru, PASSWORD_DEFAULT);

$stmt = mysqli_prepare(
    $koneksi,
    "UPDATE t_users SET password = ? WHERE email = ?"
);

mysqli_stmt_bind_param($stmt, "ss", $hash, $email);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) >= 0) {
    echo "Password Aldi berhasil diatur menjadi 12345678.";
} else {
    echo "Gagal mengubah password: " . mysqli_error($koneksi);
}

mysqli_stmt_close($stmt);
?>