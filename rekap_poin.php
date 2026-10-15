<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (strtolower($_SESSION['role'] ?? '') != 'guru') {
    echo "Anda tidak memiliki akses";
    exit;
}

include "config/koneksi.php";

/* REKAP TOTAL POIN SETIAP SISWA */
$query = "SELECT
            siswa_id,
            nama_siswa,
            nama_kelas,
            COUNT(id) AS jumlah_pelanggaran,
            SUM(poin) AS total_poin
          FROM t_pelanggaran_siswa
          GROUP BY siswa_id, nama_siswa, nama_kelas
          ORDER BY total_poin DESC, nama_siswa ASC";

$data = mysqli_query($koneksi, $query);

if (!$data) {
    die("Gagal mengambil data rekap poin: " . mysqli_error($koneksi));
}

/* RINGKASAN */
$total_siswa = mysqli_num_rows($data);
$total_catatan = 0;
$total_poin_semua = 0;

while ($ringkasan = mysqli_fetch_assoc($data)) {
    $total_catatan += (int) $ringkasan['jumlah_pelanggaran'];
    $total_poin_semua += (int) $ringkasan['total_poin'];
}

/* JALANKAN KEMBALI QUERY UNTUK TABEL */
$data = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rekap Poin Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-body-tertiary">

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR GURU -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">

            <h5 class="text-white mb-4">
                Sistem Pelanggaran
            </h5>

            <nav class="nav nav-pills flex-column gap-2">

                <a href="dashboard.php"
                   class="nav-link text-white">
                    Dashboard
                </a>

                <a href="catat_pelanggaran.php"
                   class="nav-link text-white">
                    Catat Pelanggaran
                </a>

                <a href="tindakan.php"
                   class="nav-link text-white">
                    Tindakan
                </a>

                <a href="riwayat.php"
                   class="nav-link text-white">
                    Riwayat
                </a>

                <a href="rekap_poin.php"
                   class="nav-link active">
                    Rekap Poin
                </a>

                <hr class="text-secondary">

                <a href="about_me.php"
                   class="nav-link text-white">
                    About Me
                </a>

                <a href="logout.php"
                   class="nav-link text-danger">
                    Logout
                </a>

            </nav>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2 class="mb-1">Rekap Poin Siswa</h2>

            <p class="text-secondary mb-4">
                Ringkasan jumlah pelanggaran dan total poin setiap siswa.
            </p>

            <!-- RINGKASAN -->
            <div class="row g-3 mb-4">

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-secondary mb-1">
                                Siswa yang Memiliki Catatan
                            </p>

                            <h3 class="fw-bold mb-0">
                                <?= $total_siswa; ?>
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-secondary mb-1">
                                Total Catatan Pelanggaran
                            </p>

                            <h3 class="fw-bold mb-0">
                                <?= $total_catatan; ?>
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-secondary mb-1">
                                Total Poin Semua Catatan
                            </p>

                            <h3 class="fw-bold mb-0">
                                <?= number_format($total_poin_semua, 0, ',', '.'); ?>
                            </h3>
                        </div>
                    </div>
                </div>

            </div>

            <!-- TABEL REKAP -->
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        Data Rekap Poin
                    </h5>
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jumlah Pelanggaran</th>
                                    <th>Total Poin</th>
                                </tr>
                            </thead>

                            <tbody>

                            <?php if (mysqli_num_rows($data) > 0): ?>

                                <?php $no = 1; ?>

                                <?php while ($row = mysqli_fetch_assoc($data)): ?>

                                    <tr>
                                        <td>
                                            <?= $no++; ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($row['nama_siswa'] ?? '-'); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($row['nama_kelas'] ?? '-'); ?>
                                        </td>

                                        <td>
                                            <?= (int) $row['jumlah_pelanggaran']; ?> kali
                                        </td>

                                        <td>
                                            <span class="badge bg-primary">
                                                <?= number_format(
                                                    (int) $row['total_poin'],
                                                    0,
                                                    ',',
                                                    '.'
                                                ); ?> poin
                                            </span>
                                        </td>
                                    </tr>

                                <?php endwhile; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="5"
                                        class="text-center text-secondary py-4">
                                        Belum ada data poin pelanggaran siswa.
                                    </td>
                                </tr>

                            <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                    <a href="dashboard.php"
                       class="btn btn-secondary mt-2">
                        Kembali ke Dashboard
                    </a>

                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
