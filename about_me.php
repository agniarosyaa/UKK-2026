<?php
include "auth.php";

$role = strtolower($_SESSION['role'] ?? '');
$nama = "Agnia Rosya Noerillah";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Me</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">

            <h5 class="text-white mb-4">Sistem Pelanggaran</h5>

            <ul class="nav nav-pills flex-column gap-2">

                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link text-white">Dashboard</a>
                </li>

                <?php if ($role == 'admin'): ?>

                    <li class="nav-item">
                        <a href="siswa.php" class="nav-link text-white">Kelola Siswa</a>
                    </li>

                    <li class="nav-item">
                        <a href="guru.php" class="nav-link text-white">Kelola Guru</a>
                    </li>

                    <li class="nav-item">
                        <a href="kelas.php" class="nav-link text-white">Kelola Kelas</a>
                    </li>

                    <li class="nav-item">
                        <a href="tahun_ajaran.php" class="nav-link text-white">Tahun Ajaran</a>
                    </li>

                    <li class="nav-item">
                        <a href="penempatan_siswa.php" class="nav-link text-white">Penempatan Siswa</a>
                    </li>

                    <li class="nav-item">
                        <a href="wali_kelas.php" class="nav-link text-white">Wali Kelas</a>
                    </li>

                    <li class="nav-item">
                        <a href="kategori_pelanggaran.php" class="nav-link text-white">Kategori Pelanggaran</a>
                    </li>

                    <li class="nav-item">
                        <a href="jenis_pelanggaran.php" class="nav-link text-white">Jenis Pelanggaran</a>
                    </li>

                    <li class="nav-item">
                        <a href="laporan.php" class="nav-link text-white">Laporan</a>
                    </li>

                <?php elseif ($role == 'guru'): ?>

                    <li class="nav-item">
                        <a href="catat_pelanggaran.php" class="nav-link text-white">Catat Pelanggaran</a>
                    </li>

                    <li class="nav-item">
                        <a href="tindakan.php" class="nav-link text-white">Tindakan</a>
                    </li>

                    <li class="nav-item">
                        <a href="riwayat.php" class="nav-link text-white">Riwayat</a>
                    </li>

                    <li class="nav-item">
                        <a href="rekap_poin.php" class="nav-link text-white">Rekap Poin</a>
                    </li>

                <?php endif; ?>

                <hr class="text-secondary">

                <li class="nav-item">
                    <a href="about_me.php" class="nav-link active">About Me</a>
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

        <!-- KONTEN ABOUT ME -->
        <main class="col-md-9 col-lg-10 p-4 p-lg-5">

            <div class="mb-5">
                <p class="text-primary fw-semibold mb-1">
                    PROFIL PENGEMBANG
                </p>

                <h2 class="fw-bold text-dark">About Me</h2>

                <p class="text-secondary">
                    Sedikit cerita tentang saya dan proyek yang saya buat.
                </p>
            </div>

            <!-- PROFIL -->
            <div class="row align-items-center g-4 mb-5">

                <div class="col-12 col-lg-4 text-center">
                    <div class="d-inline-block p-2 rounded-circle bg-white shadow">
                        <img src="foto_profil.jpeg"
                             alt="Foto profil"
                             class="rounded-circle"
                             width="220"
                             height="220"
                             style="object-fit: cover;">
                    </div>
                </div>

                <div class="col-12 col-lg-8">
                    <p class="text-primary fw-semibold mb-2">HALO, SAYA</p>

                    <h1 class="fw-bold text-dark mb-2">
                        <?= htmlspecialchars($nama); ?>
                    </h1>

                    <h5 class="text-secondary mb-4">
                        Siswa Rekayasa Perangkat Lunak
                    </h5>

                    <p class="text-secondary">
                        Saya merupakan siswa kelas XII RPL 2 di
                        SMK Muhammadiyah Tasikmalaya. Saya sedang belajar
                        mengembangkan aplikasi berbasis web menggunakan
                        PHP Native dan MySQL.
                    </p>

                    <a href="dashboard.php"
                       class="btn btn-primary rounded-pill px-4 mt-2">
                        ← Kembali ke Dashboard
                    </a>
                </div>

            </div>

            <hr class="text-secondary mb-5">

            <!-- INFORMASI DIRI -->
            <h4 class="fw-bold mb-4">Informasi Saya</h4>

            <div class="row g-3 mb-5">

                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <p class="text-primary fw-semibold mb-2">PENDIDIKAN</p>
                            <h6 class="fw-bold">XII RPL 2</h6>
                            <p class="text-secondary mb-0">
                                SMK Muhammadiyah Tasikmalaya
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <p class="text-primary fw-semibold mb-2">KEAHLIAN</p>
                            <h6 class="fw-bold">Rekayasa Perangkat Lunak</h6>
                            <p class="text-secondary mb-0">
                                Pengembangan aplikasi web
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- PROYEK -->
            <div class="card border-0 shadow rounded-4 text-white mb-4"
                 style="background: linear-gradient(120deg, #4338ca, #2563eb);">

                <div class="card-body p-4 p-md-5">

                    <p class="text-white-50 fw-semibold mb-2">
                        PROYEK YANG SEDANG DIKERJAKAN
                    </p>

                    <h3 class="fw-bold mb-3">
                        Sistem Informasi Pelanggaran Siswa
                    </h3>

                    <p class="mb-4">
                        Aplikasi berbasis web untuk membantu pengelolaan
                        data dan informasi pelanggaran siswa secara terstruktur.
                    </p>

                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-light text-primary px-3 py-2">PHP Native</span>
                        <span class="badge bg-light text-primary px-3 py-2">MySQL</span>
                        <span class="badge bg-light text-primary px-3 py-2">HTML</span>
                        <span class="badge bg-light text-primary px-3 py-2">CSS</span>
                        <span class="badge bg-light text-primary px-3 py-2">JavaScript</span>
                    </div>

                </div>
            </div>

            <div class="text-center text-secondary mt-5">
                <small>Sistem Informasi Pelanggaran Siswa</small>
            </div>

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
