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

if (isset($_POST['tambah'])) {
    $nama = trim($_POST['nama']);
    $tingkat = trim($_POST['tingkat']);
    $jurusan = trim($_POST['jurusan']);

    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO t_kelas
         (nama, tingkat, jurusan, status_aktif, created_at, update_at)
         VALUES (?, ?, ?, 1, NOW(), NOW())"
    );

    mysqli_stmt_bind_param($stmt, "sss", $nama, $tingkat, $jurusan);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: kelas.php?pesan=tambah");
        exit;
    } else {
        $pesan_error = "Gagal menambahkan kelas.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kelas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0">Tambah Kelas</h4>
                </div>

                <div class="card-body p-4">

                    <?php if (isset($pesan_error)) { ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($pesan_error); ?>
                        </div>
                    <?php } ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Kelas</label>
                            <input type="text" id="nama" name="nama"
                                   class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="tingkat" class="form-label">Tingkat</label>
                            <select id="tingkat" name="tingkat"
                                    class="form-select" required>
                                <option value="">Pilih tingkat</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="jurusan" class="form-label">Jurusan</label>
                            <input type="text" id="jurusan" name="jurusan"
                                   class="form-control" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" name="tambah"
                                    class="btn btn-primary">
                                Simpan
                            </button>

                            <a href="kelas.php" class="btn btn-secondary">
                                Kembali
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
