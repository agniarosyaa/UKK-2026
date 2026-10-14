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

// Ambil data guru
$query = "SELECT * FROM t_guru ORDER BY id ASC";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query gagal: " . mysqli_error($koneksi));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Guru</title>

    <!-- BOOTSTRAP -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .button-area {
            margin-bottom: 14px;
        }

        .btn-dashboard {
            background-color: #6c757d;
            color: white;
        }

        .btn-dashboard:hover {
            background-color: #5c636a;
            color: white;
        }

        .btn-tambah {
            background-color: #0d6efd;
            color: white;
        }

        .btn-tambah:hover {
            background-color: #0b5ed7;
            color: white;
        }

        .btn-edit {
            background-color: #0d6efd;
            color: white;
            padding: 7px 12px;
            font-size: 13px;
        }

        .btn-edit:hover {
            background-color: #0b5ed7;
            color: white;
        }

        .btn-hapus {
            background-color: #dc3545;
            color: white;
            padding: 7px 12px;
            font-size: 13px;
        }

        .btn-hapus:hover {
            background-color: #bb2d3b;
            color: white;
        }

        .table-responsive {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th,
        td {
            border: 1px solid #dee2e6;
            padding: 14px 9px;
            text-align: left;
            font-size: 14px;
        }

        th {
            font-weight: bold;
            background-color: #ffffff;
        }

        .status {
            display: inline-block;
            padding: 3px 7px;
            border-radius: 5px;
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        .aktif {
            background-color: #198754;
        }

        .tidak-aktif {
            background-color: #dc3545;
        }

        .aksi {
            width: 150px;
            white-space: nowrap;
        }

    </style>

</head>

<body>

<div class="container-fluid">

    <div class="row">

        <!-- SIDEBAR SAMA SEPERTI DASHBOARD -->
        <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">

            <h4 class="text-white mb-4">
                Sistem Pelanggaran
            </h4>

            <ul class="nav nav-pills flex-column">

                <!-- DASHBOARD -->
                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link text-white">
                        Dashboard
                    </a>
                </li>

                <!-- MENU KHUSUS ADMIN -->
                <?php if ($_SESSION['role'] == 'admin') { ?>

                    <li class="nav-item mb-2">
                        <a href="siswa.php" class="nav-link text-white">
                            Kelola Siswa
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="guru.php" class="nav-link active">
                            Kelola Guru
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="kelas.php" class="nav-link text-white">
                            Kelola Kelas
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="tahun_ajaran.php" class="nav-link text-white">
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

                <?php } ?>

                <!-- MENU KHUSUS GURU -->
                <?php if ($_SESSION['role'] == 'guru') { ?>

                    <li class="nav-item mb-2">
                        <a href="catat_pelanggaran.php" class="nav-link text-white">
                            Catat Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="tindakan.php" class="nav-link text-white">
                            Tindakan
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="riwayat.php" class="nav-link text-white">
                            Riwayat
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="rekap_poin.php" class="nav-link text-white">
                            Rekap Poin
                        </a>
                    </li>

                <?php } ?>

                <hr class="text-secondary">

                <!-- ABOUT ME -->
                <li class="nav-item mb-2">
                    <a href="about_me.php" class="nav-link text-white">
                        About Me
                    </a>
                </li>

                <!-- LOGOUT -->
                <li class="nav-item">
                    <a
                        href="logout.php"
                        class="nav-link text-danger"
                        onclick="return confirm('Yakin ingin logout?')"
                    >
                        Logout
                    </a>
                </li>

            </ul>

        </div>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <h1 class="mb-3">Data Guru</h1>

            <!-- TOMBOL -->
            <div class="button-area">

                <a href="dashboard.php" class="btn btn-dashboard">
                    Kembali ke Dashboard
                </a>

                <a href="tambah_guru.php" class="btn btn-tambah">
                    + Tambah Guru
                </a>

            </div>

            <!-- TABEL GURU -->
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>No</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>User ID</th>
                            <th>Status</th>
                            <th class="aksi">Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php
                    $no = 1;

                    while ($data = mysqli_fetch_assoc($result)) {
                    ?>

                        <tr>

                            <td><?= $no++; ?></td>

                            <td>
                                <?= htmlspecialchars($data['nip']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($data['nama']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($data['email']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($data['user_id']); ?>
                            </td>

                            <td>

                                <?php if ($data['status_aktif'] == 1) { ?>

                                    <span class="status aktif">
                                        Aktif
                                    </span>

                                <?php } else { ?>

                                    <span class="status tidak-aktif">
                                        Tidak Aktif
                                    </span>

                                <?php } ?>

                            </td>

                            <td class="aksi">

                                <a
                                    href="edit_guru.php?id=<?= $data['id']; ?>"
                                    class="btn btn-edit btn-sm"
                                >
                                    Edit
                                </a>

                                <a
                                    href="hapus_guru.php?id=<?= $data['id']; ?>"
                                    class="btn btn-hapus btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus data guru ini?')"
                                >
                                    Hapus
                                </a>

                            </td>

                        </tr>

                    <?php
                    }
                    ?>

                    </tbody>

                </table>

            </div>

        </main>

    </div>

</div>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
