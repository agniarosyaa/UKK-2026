<?php

$koneksi = mysqli_connect(
    "localhost",
    "root",
    "",
    "db_ukk_2026"
);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($koneksi, "utf8mb4");

?>