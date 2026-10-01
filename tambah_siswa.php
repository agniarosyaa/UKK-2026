<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] != 'admin') {
    echo "Anda tidak memiliki akses";
    exit;
}

include "config/koneksi.php";

if (isset($_POST['simpan'])) {

    $nis = $_POST['nis'];
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];

    $query = "INSERT INTO t_siswa
        (nis, nisn, nama, jenis_kelamin, tanggal_lahir, alamat, status_aktif)
        VALUES
        ('$nis', '$nisn', '$nama', '$jenis_kelamin', '$tanggal_lahir', '$alamat', 1)";

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: siswa.php");
        exit;
    } else {
        echo "Gagal menambahkan data: " . mysqli_error($koneksi);
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body>

<div class="container mt-4">

    <h2>Tambah Siswa</h2>

    <form method="POST">

        <div class="mb-3">
            <label>NIS</label>
            <input type="text"
                   name="nis"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label>NISN</label>
            <input type="text"
                   name="nisn"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label>Nama</label>
            <input type="text"
                   name="nama"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label>Jenis Kelamin</label>

            <select name="jenis_kelamin"
                    class="form-control"
                    required>

                <option value="">-- Pilih --</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>

            </select>
        </div>

        <div class="mb-3">
            <label>Tanggal Lahir</label>
            <input type="date"
                   name="tanggal_lahir"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat"
                      class="form-control"
                      required></textarea>
        </div>

        <button type="submit"
                name="simpan"
                class="btn btn-primary">
            Simpan
        </button>

        <a href="siswa.php"
           class="btn btn-secondary">
            Kembali
        </a>

    </form>

</div>

</body>
</html>