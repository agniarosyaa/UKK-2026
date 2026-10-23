
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body>

<div class="container-fluid">
    <div class="row">

   <!-- SIDEBAR -->
<aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">

            <h5 class="text-white mb-4">
                Sistem Pelanggaran
            </h5>

            <ul class="nav nav-pills flex-column gap-2">

                <li class="nav-item">
                    <a href="dashboard.php"
                       class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : 'text-white'; ?>">
                        Dashboard
                    </a>
                </li>

                <?php if ($role == 'admin') { ?>

                    <li class="nav-item">
                        <a href="siswa.php" class="nav-link text-white">
                            Kelola Siswa
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="guru.php" class="nav-link text-white">
                            Kelola Guru
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="kelas.php" class="nav-link text-white">
                            Kelola Kelas
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="tahun_ajaran.php" class="nav-link text-white">
                            Tahun Ajaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="penempatan_siswa.php" class="nav-link text-white">
                            Penempatan Siswa
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="wali_kelas.php" class="nav-link text-white">
                            Wali Kelas
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="kategori_pelanggaran.php" class="nav-link text-white">
                            Kategori Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="jenis_pelanggaran.php" class="nav-link text-white">
                            Jenis Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="laporan.php" class="nav-link text-white">
                            Laporan
                        </a>
                    </li>

                <?php } elseif ($role == 'guru') { ?>

                    <li class="nav-item">
                        <a href="catat_pelanggaran.php" class="nav-link text-white">
                            Catat Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="tindakan.php" class="nav-link text-white">
                            Tindakan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="riwayat.php" class="nav-link text-white">
                            Riwayat
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="rekap_poin.php" class="nav-link text-white">
                            Rekap Poin
                        </a>
                    </li>

                <?php } ?>

                <hr class="text-secondary">

                <li class="nav-item">
                    <a href="about_me.php" class="nav-link text-white">
                        About Me
                    </a>
                </li>

                <li class="nav-item">
                    <a href="logout.php"
                       class="nav-link text-danger"
                       onclick="return confirm('Yakin ingin logout?')">
                        Logout
                    </a>
                </li>

            </ul>
        </aside>

        <!-- KONTEN UTAMA DASHBOARD -->
        <main class="col-md-9 col-lg-10 p-4">

            <?php if ($role == 'admin') { ?>

                <h2>Dashboard Admin</h2>

                <p>
                    Selamat datang,
                    <b><?= htmlspecialchars($_SESSION['name'] ?? $role); ?></b>
                </p>

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Data Siswa</h5>
                                <p class="card-text">Kelola data siswa.</p>
                                <a href="siswa.php" class="btn btn-primary">Lihat Data</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Data Guru</h5>
                                <p class="card-text">Kelola data guru.</p>
                                <a href="guru.php" class="btn btn-primary">Lihat Data</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Data Kelas</h5>
                                <p class="card-text">Kelola data kelas.</p>
                                <a href="kelas.php" class="btn btn-primary">Lihat Data</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Data Tahun Ajaran</h5>
                                <p class="card-text">Kelola data tahun ajaran.</p>
                                <a href="tahun_ajaran.php" class="btn btn-primary">Lihat Data</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Data Penempatan Siswa</h5>
                                <p class="card-text">Kelola data penempatan siswa.</p>
                                <a href="penempatan_siswa.php" class="btn btn-primary">Lihat Data</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Data Wali Kelas</h5>
                                <p class="card-text">Kelola data wali kelas.</p>
                                <a href="wali_kelas.php" class="btn btn-primary">Lihat Data</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Data Kategori Pelanggaran</h5>
                                <p class="card-text">Kelola data kategori pelanggaran.</p>
                                <a href="kategori_pelanggaran.php" class="btn btn-primary">Lihat Data</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Data Jenis Pelanggaran</h5>
                                <p class="card-text">Kelola data jenis pelanggaran.</p>
                                <a href="jenis_pelanggaran.php" class="btn btn-primary">Lihat Data</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Laporan</h5>
                                <p class="card-text">Melihat laporan data pelanggaran siswa.</p>
                                <a href="laporan.php" class="btn btn-primary">Lihat Data</a>
                            </div>
                        </div>
                    </div>

                </div>

            <?php } elseif ($role == 'guru') { ?>

                <h2>Dashboard Guru</h2>

                <p>
                    Selamat datang,
                    <b><?= htmlspecialchars($_SESSION['name'] ?? $role); ?></b>
                </p>

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Catat Pelanggaran</h5>
                                <p class="card-text">Mencatat pelanggaran siswa.</p>
                                <a href="catat_pelanggaran.php" class="btn btn-primary">Buka</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Tindakan</h5>
                                <p class="card-text">Mengelola tindakan terhadap pelanggaran.</p>
                                <a href="tindakan.php" class="btn btn-primary">Buka</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Riwayat</h5>
                                <p class="card-text">Melihat riwayat pelanggaran siswa.</p>
                                <a href="riwayat.php" class="btn btn-primary">Buka</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Rekap Poin</h5>
                                <p class="card-text">Melihat rekap poin siswa.</p>
                                <a href="rekap_poin.php" class="btn btn-primary">Buka</a>
                            </div>
                        </div>
                    </div>

                </div>

            <?php } ?>

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
