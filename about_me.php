<?php

include "auth.php";

$role = $_SESSION['role'];
$nama = "Agnia Rosya Noerillah";

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>About Me</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">

            <h4 class="text-white fw-bold mb-4">
                Sistem Pelanggaran
            </h4>

            <p class="text-secondary">
                Halo, <?= htmlspecialchars($nama); ?>
            </p>

            <ul class="nav nav-pills flex-column gap-2">

                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link text-white">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a href="data_siswa.php" class="nav-link text-white">
                        Data Siswa
                    </a>
                </li>

                <li class="nav-item">
                    <a href="data_guru.php" class="nav-link text-white">
                        Data Guru
                    </a>
                </li>

                <li class="nav-item">
                    <a href="data_kelas.php" class="nav-link text-white">
                        Kelas
                    </a>
                </li>

                <li class="nav-item">
                    <a href="data_tahun_ajaran.php" class="nav-link text-white">
                        Tahun Ajaran
                    </a>
                </li>

                <li class="nav-item">
                    <a href="data_penempatan_siswa.php" class="nav-link text-white">
                        Penempatan Siswa
                    </a>
                </li>

                <li class="nav-item">
                    <a href="data_wali_kelas.php" class="nav-link text-white">
                        Wali Kelas
                    </a>
                </li>

                <li class="nav-item">
                    <a href="data_kategori_pelanggaran.php" class="nav-link text-white">
                        Kategori Pelanggaran
                    </a>
                </li>

                <li class="nav-item">
                    <a href="data_jenis_pelanggaran.php" class="nav-link text-white">
                        Jenis Pelanggaran
                    </a>
                </li>

                <li class="nav-item">
                    <a href="data_laporan.php" class="nav-link text-white">
                        Laporan
                    </a>
                </li>

                <li class="nav-item">
                    <a href="about_me.php" class="nav-link active">
                        About Me
                    </a>
                </li>

                <hr class="text-secondary">

                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">
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

                <h2 class="fw-bold text-dark">
                    About Me
                </h2>

                <p class="text-secondary">
                    Sedikit cerita tentang saya dan proyek yang saya buat.
                </p>
            </div>


            <!-- PROFIL UTAMA -->
            <div class="row align-items-center g-4 mb-5">

                <!-- FOTO -->
                <div class="col-12 col-lg-4 text-center">

                    <div class="d-inline-block p-2 rounded-circle bg-white shadow">

                        <img src="foto_profil.jpeg"
                             alt="Foto profil"
                             class="rounded-circle"
                             style="width: 220px; height: 220px; object-fit: cover;">

                    </div>

                </div>


                <!-- IDENTITAS -->
                <div class="col-12 col-lg-8">

                    <p class="text-primary fw-semibold mb-2">
                        HALO, SAYA
                    </p>

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


            <!-- GARIS PEMBATAS -->
            <hr class="text-secondary mb-5">


            <!-- INFORMASI DIRI -->
            <h4 class="fw-bold mb-4">
                Informasi Saya
            </h4>

            <div class="row g-3 mb-5">

                <div class="col-12 col-md-6">
                    <div class="bg-white p-4 rounded-3 shadow-sm h-100">
                        <p class="text-primary fw-semibold mb-2">
                            PENDIDIKAN
                        </p>

                        <h6 class="fw-bold">
                            XII RPL 2
                        </h6>

                        <p class="text-secondary mb-0">
                            SMK Muhammadiyah Tasikmalaya
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="bg-white p-4 rounded-3 shadow-sm h-100">
                        <p class="text-primary fw-semibold mb-2">
                            KEAHLIAN
                        </p>

                        <h6 class="fw-bold">
                            Rekayasa Perangkat Lunak
                        </h6>

                        <p class="text-secondary mb-0">
                            Pengembangan aplikasi web
                        </p>
                    </div>
                </div>

            </div>


            <!-- PROYEK -->
            <div class="p-4 p-md-5 rounded-4 text-white shadow-sm"
                 style="background: linear-gradient(120deg, #4338ca, #2563eb);">

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
                    <span class="badge bg-light text-primary px-3 py-2">
                        PHP Native
                    </span>

                    <span class="badge bg-light text-primary px-3 py-2">
                        MySQL
                    </span>

                    <span class="badge bg-light text-primary px-3 py-2">
                        HTML
                    </span>

                    <span class="badge bg-light text-primary px-3 py-2">
                        CSS
                    </span>

                    <span class="badge bg-light text-primary px-3 py-2">
                        JavaScript
                    </span>
                </div>

            </div>


            <!-- FOOTER -->
            <div class="text-center text-secondary mt-5">
                <small>Sistem Informasi Pelanggaran Siswa</small>
            </div>

        </main>

    </div>
</div>

</body>
</html>