
<?php
include "config/koneksi.php";

// DATA AKUN WALI KELAS ALDI
$email = "user32@sekolah.test";
$password_baru = "12345678";

// Membuat password terenkripsi
$hash = password_hash($password_baru, PASSWORD_DEFAULT);

// Update password berdasarkan email
$stmt = mysqli_prepare(
    $koneksi,
    "UPDATE t_users SET password = ? WHERE email = ?"
);

if (!$stmt) {
    die("Gagal menyiapkan query: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "ss", $hash, $email);

// Jalankan update
if (mysqli_stmt_execute($stmt)) {

    if (mysqli_stmt_affected_rows($stmt) >= 0) {
        echo "Password Aldi berhasil diatur menjadi 12345678.";
    } else {
        echo "Gagal mengubah password.";
    }

} else {
    echo "Gagal mengubah password: " . mysqli_stmt_error($stmt);
}

mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>
