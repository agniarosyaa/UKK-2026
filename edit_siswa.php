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

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("ID siswa tidak valid.");
}

/* AMBIL DATA SISWA */
$stmt = mysqli_prepare($koneksi, "SELECT * FROM t_siswa WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data siswa tidak ditemukan.");
}

/* UPDATE DATA SISWA */
if (isset($_POST['update'])) {

    $nis = trim($_POST['nis']);
    $nisn = trim($_POST['nisn']);
    $nama = trim($_POST['nama']);
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = trim($_POST['alamat']);
    $status_aktif = (int) $_POST['status_aktif'];

    $update = mysqli_prepare(
        $koneksi,
        "UPDATE t_siswa SET
            nis = ?,
            nisn = ?,
            nama = ?,
            jenis_kelamin = ?,
            tanggal_lahir = ?,
            alamat = ?,
            status_aktif = ?
         WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $update,
        "ssssssii",
        $nis,
        $nisn,
        $nama,
        $jenis_kelamin,
        $tanggal_lahir,
        $alamat,
        $status_aktif,
        $id
    );

    if (mysqli_stmt_execute($update)) {
        header("Location: siswa.php");
        exit;
    } else {
        $pesan_error = "Gagal mengubah data siswa.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Siswa</title>

    <!-- BOOTSTRAP 5.3.8 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-8 col-lg-7">

            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0">Edit Siswa</h4>
                </div>

                <div class="card-body p-4">

                    <?php if (isset($pesan_error)) { ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($pesan_error); ?>
                        </div>
                    <?php } ?>

                    <form method="POST">

                        <!-- NIS -->
                        <div class="mb-3">
                            <label for="nis" class="form-label">NIS</label>

                            <input type="text"
                                   id="nis"
                                   name="nis"
                                   class="form-control"
                                   value="<?= htmlspecialchars($data['nis'] ?? ''); ?>"
                                   required>
                        </div>

                        <!-- NISN -->
                        <div class="mb-3">
                            <label for="nisn" class="form-label">NISN</label>

                            <input type="text"
                                   id="nisn"
                                   name="nisn"
                                   class="form-control"
                                   value="<?= htmlspecialchars($data['nisn'] ?? ''); ?>"
                                   required>
                        </div>

                        <!-- NAMA -->
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Siswa</label>

                            <input type="text"
                                   id="nama"
                                   name="nama"
                                   class="form-control"
                                   value="<?= htmlspecialchars($data['nama'] ?? ''); ?>"
                                   required>
                        </div>

                        <!-- JENIS KELAMIN -->
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">
                                Jenis Kelamin
                            </label>

                            <select id="jenis_kelamin"
                                    name="jenis_kelamin"
                                    class="form-select"
                                    required>

                                <option value="L"
                                    <?= ($data['jenis_kelamin'] ?? '') == 'L' ? 'selected' : ''; ?>>
                                    Laki-laki
                                </option>

                                <option value="P"
                                    <?= ($data['jenis_kelamin'] ?? '') == 'P' ? 'selected' : ''; ?>>
                                    Perempuan
                                </option>

                            </select>
                        </div>

                        <!-- TANGGAL LAHIR -->
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">
                                Tanggal Lahir
                            </label>

                            <input type="date"
                                   id="tanggal_lahir"
                                   name="tanggal_lahir"
                                   class="form-control"
                                   value="<?= htmlspecialchars($data['tanggal_lahir'] ?? ''); ?>"
                                   required>
                        </div>

                        <!-- ALAMAT -->
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>

                            <textarea id="alamat"
                                      name="alamat"
                                      class="form-control"
                                      rows="3"
                                      required><?= htmlspecialchars($data['alamat'] ?? ''); ?></textarea>
                        </div>

                        <!-- STATUS -->
                        <div class="mb-4">
                            <label for="status_aktif" class="form-label">
                                Status
                            </label>

                            <select id="status_aktif"
                                    name="status_aktif"
                                    class="form-select"
                                    required>

                                <option value="1"
                                    <?= ($data['status_aktif'] ?? 0) == 1 ? 'selected' : ''; ?>>
                                    Aktif
                                </option>

                                <option value="0"
                                    <?= ($data['status_aktif'] ?? 0) == 0 ? 'selected' : ''; ?>>
                                    Tidak Aktif
                                </option>

                            </select>
                        </div>

                        <!-- TOMBOL -->
                        <div class="d-flex gap-2">

                            <button type="submit"
                                    name="update"
                                    class="btn btn-primary">
                                Simpan Perubahan
                            </button>

                            <a href="siswa.php" class="btn btn-secondary">
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
