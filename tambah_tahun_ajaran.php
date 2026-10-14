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

$error = "";

if (isset($_POST['simpan'])) {

    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $tanggal_mulai = mysqli_real_escape_string($koneksi, $_POST['tanggal_mulai']);
    $tanggal_selesai = mysqli_real_escape_string($koneksi, $_POST['tanggal_selesai']);
    $status_aktif = isset($_POST['status_aktif']) ? 1 : 0;

    if ($tanggal_selesai < $tanggal_mulai) {
        $error = "Tanggal selesai tidak boleh lebih awal dari tanggal mulai.";
    } else {

        $query = "INSERT INTO t_tahun_ajaran
                  (nama, tanggal_mulai, tanggal_selesai, status_aktif, created_at, update_at)
                  VALUES
                  ('$nama', '$tanggal_mulai', '$tanggal_selesai', '$status_aktif', NOW(), NOW())";

        if (mysqli_query($koneksi, $query)) {
            header("Location: tahun_ajaran.php");
            exit;
        } else {
            $error = "Gagal menambahkan tahun ajaran: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Tahun Ajaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: var(--bs-body-font-family);
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
        }

        .sidebar .nav-link {
            color: white;
            border-radius: 5px;
        }

        .sidebar .nav-link:hover {
            background-color: #495057;
        }

        .sidebar .nav-link.active {
            background-color: #0d6efd;
        }

        .content-area {
            padding: 30px;
        }

        .form-card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            max-width: 800px;
        }

        @media (max-width: 767px) {
            .content-area {
                padding: 20px 12px;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 bg-dark sidebar p-3">

            <h4 class="text-white mb-4">Sistem Pelanggaran</h4>

            <ul class="nav nav-pills flex-column">

                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link text-white">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="siswa.php" class="nav-link text-white">
                        Kelola Siswa
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="guru.php" class="nav-link text-white">
                        Kelola Guru
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelas.php" class="nav-link text-white">
                        Kelola Kelas
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="tahun_ajaran.php" class="nav-link active">
                        Tahun Ajaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="penempatan_siswa.php" class="nav-link text-white">
                        Penempatan Siswa
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="wali_kelas.php" class="nav-link text-white">
                        Wali Kelas
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kategori_pelanggaran.php" class="nav-link text-white">
                        Kategori Pelanggaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="jenis_pelanggaran.php" class="nav-link text-white">
                        Jenis Pelanggaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="laporan.php" class="nav-link text-white">
                        Laporan
                    </a>
                </li>

                <hr class="text-secondary">

                <li class="nav-item mb-2">
                    <a href="about_me.php" class="nav-link text-white">
                        About Me
                    </a>
                </li>

                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">
                        Logout
                    </a>
                </li>

            </ul>
        </div>

        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 content-area">

            <h2 class="mb-4">Tambah Tahun Ajaran</h2>

            <div class="form-card">

                <?php if ($error != "") { ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error); ?>
                    </div>
                <?php } ?>

                <form method="POST">

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Tahun Ajaran</label>
                        <input
                            type="text"
                            name="nama"
                            id="nama"
                            class="form-control"
                            placeholder="Contoh: 2026/2027"
                            value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input
                            type="date"
                            name="tanggal_mulai"
                            id="tanggal_mulai"
                            class="form-control"
                            value="<?= isset($_POST['tanggal_mulai']) ? htmlspecialchars($_POST['tanggal_mulai']) : ''; ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input
                            type="date"
                            name="tanggal_selesai"
                            id="tanggal_selesai"
                            class="form-control"
                            value="<?= isset($_POST['tanggal_selesai']) ? htmlspecialchars($_POST['tanggal_selesai']) : ''; ?>"
                            required>
                    </div>

                    <div class="form-check mb-4">
                        <input
                            type="checkbox"
                            name="status_aktif"
                            id="status_aktif"
                            class="form-check-input"
                            value="1"
                            <?= !isset($_POST['simpan']) || isset($_POST['status_aktif']) ? 'checked' : ''; ?>>

                        <label for="status_aktif" class="form-check-label">
                            Aktif
                        </label>
                    </div>

                    <button type="submit" name="simpan" class="btn btn-primary">
                        Simpan
                    </button>

                    <a href="tahun_ajaran.php" class="btn btn-secondary">
                        Batal
                    </a>

                </form>

            </div>

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>