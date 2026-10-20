
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (strtolower($_SESSION['role'] ?? '') != 'guru') {
    exit("Anda tidak memiliki akses");
}

include "config/koneksi.php";

$data = mysqli_query($koneksi, "SELECT id, nama_siswa, nama_kelas,
    nama_pelanggaran, tanggal, poin, tindakan, status
    FROM t_pelanggaran_siswa
    ORDER BY tanggal DESC, id DESC");

if (!$data) {
    die("Gagal mengambil data: " . mysqli_error($koneksi));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tindakan Pelanggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary">

<div class="container-fluid">
<div class="row">

    <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">
        <h5 class="text-white mb-4">Sistem Pelanggaran</h5>
        <nav class="nav nav-pills flex-column gap-1">
            <a href="dashboard.php" class="nav-link text-white">Dashboard</a>
            <a href="catat_pelanggaran.php" class="nav-link text-white">Catat Pelanggaran</a>
            <a href="tindakan.php" class="nav-link active">Tindakan</a>
            <a href="riwayat.php" class="nav-link text-white">Riwayat</a>
            <a href="rekap_poin.php" class="nav-link text-white">Rekap Poin</a>
            <hr class="text-secondary">
            <a href="about_me.php" class="nav-link text-white">About Me</a>
            <a href="logout.php" class="nav-link text-danger"
               onclick="return confirm('Yakin ingin logout?')">Logout</a>
        </nav>
    </aside>

    <main class="col-md-9 col-lg-10 p-4">
        <h1 class="mb-2">Tindakan Pelanggaran</h1>
        <p class="text-secondary mb-4">Kelola tindakan dan status pelanggaran siswa.</p>

        <?php if (isset($_GET['berhasil'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                Tindakan berhasil diperbarui.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white py-3">
                Data Tindakan Pelanggaran
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Pelanggaran</th>
                                <th>Tanggal</th>
                                <th>Poin</th>
                                <th>Tindakan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (mysqli_num_rows($data) > 0): ?>
                            <?php $no = 1; ?>
                            <?php while ($row = mysqli_fetch_assoc($data)): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama_siswa'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['nama_kelas'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['nama_pelanggaran'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['tanggal'] ?? '') ?></td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <?= (int)$row['poin'] ?> poin
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($row['tindakan'] ?? '') ?: '-' ?></td>
                                    <td>
                                        <?php if (($row['status'] ?? '') == 'Selesai'): ?>
                                            <span class="badge bg-success">Selesai</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">
                                                <?= htmlspecialchars($row['status'] ?? 'Diproses') ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="edit_tindakan.php?id=<?= (int)$row['id'] ?>"
                                           class="btn btn-primary btn-sm">Edit</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-secondary py-4">
                                    Belum ada data pelanggaran siswa.
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
