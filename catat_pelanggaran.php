<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "config/koneksi.php";

$role = strtolower($_SESSION['role'] ?? '');

if (!in_array($role, ['admin', 'guru'])) {
    exit("Anda tidak memiliki akses");
}

$query = "SELECT * FROM t_pelanggaran_siswa ORDER BY tanggal DESC";
$result = mysqli_query($koneksi, $query);
$error = $result ? '' : mysqli_error($koneksi);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catatan Pelanggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container-fluid">
<div class="row">

    <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">
       <h5 class="text-white mb-4">
    Sistem Pelanggaran
</h5>
        <nav class="nav nav-pills flex-column gap-2">
            <a href="dashboard.php" class="nav-link text-white">Dashboard</a>

            <?php if ($role == 'admin') { ?>
                <a href="siswa.php" class="nav-link text-white">Kelola Siswa</a>
                <a href="guru.php" class="nav-link text-white">Kelola Guru</a>
                <a href="kelas.php" class="nav-link text-white">Kelola Kelas</a>
                <a href="tahun_ajaran.php" class="nav-link text-white">Tahun Ajaran</a>
                <a href="penempatan_siswa.php" class="nav-link text-white">Penempatan Siswa</a>
                <a href="wali_kelas.php" class="nav-link text-white">Wali Kelas</a>
                <a href="kategori_pelanggaran.php" class="nav-link text-white">Kategori Pelanggaran</a>
                <a href="jenis_pelanggaran.php" class="nav-link text-white">Jenis Pelanggaran</a>
                <a href="catat_pelanggaran.php" class="nav-link active">Catatan Pelanggaran</a>
                <a href="laporan.php" class="nav-link text-white">Laporan</a>
            <?php } ?>

            <?php if ($role == 'guru') { ?>
                <a href="catat_pelanggaran.php" class="nav-link active">Catatan Pelanggaran</a>
                <a href="tindakan.php" class="nav-link text-white">Tindakan</a>
                <a href="riwayat.php" class="nav-link text-white">Riwayat</a>
                <a href="rekap_poin.php" class="nav-link text-white">Rekap Poin</a>
            <?php } ?>

            <hr class="border-secondary">

            <a href="about_me.php" class="nav-link text-white">About Me</a>
            <a href="logout.php" class="nav-link text-danger"
               onclick="return confirm('Yakin ingin logout?')">Logout</a>
        </nav>
    </aside>

    <main class="col-md-9 col-lg-10 p-4">
        <h2 class="mb-2">Catatan Pelanggaran Siswa</h2>
        <p class="text-secondary">Daftar catatan pelanggaran siswa.</p>

        <div class="d-flex gap-2 mb-3">
            <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
            <a href="tambah_catat_pelanggaran.php" class="btn btn-primary">
                + Tambah Catat Pelanggaran
            </a>
        </div>

        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'tambah') { ?>
            <div class="alert alert-success">
                Catatan pelanggaran berhasil ditambahkan.
            </div>
        <?php } ?>

        <?php if ($error != '') { ?>
            <div class="alert alert-danger">
                Gagal mengambil data: <?= htmlspecialchars($error); ?>
            </div>
        <?php } else { ?>

        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Siswa</th>
                            <th>Nama Pelanggaran</th>
                            <th>Poin</th>
                            <th>Tindakan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (mysqli_num_rows($result) > 0) { ?>
                        <?php $no = 1; ?>
                        <?php while ($data = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($data['tanggal'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($data['nama_siswa'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($data['nama_pelanggaran'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars((string)($data['poin'] ?? 0)); ?></td>
                                <td><?= htmlspecialchars($data['tindakan'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($data['status'] ?? '-'); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                Belum ada catatan pelanggaran.
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php } ?>
    </main>

</div>
</div>
</body>
</html>
