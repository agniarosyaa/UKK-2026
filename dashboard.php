<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous">
</head>

<body>

<div class="container-fluid">

    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">

            <h4 class="text-white mb-4">
                Sistem Pelanggaran
            </h4>

            <ul class="nav nav-pills flex-column">

                <!-- DASHBOARD -->
                <li class="nav-item mb-2">
                    <a href="dashboard.php"
                       class="nav-link active">
                        Dashboard
                    </a>
                </li>


                <!-- ========================= -->
                <!-- MENU KHUSUS ADMIN -->
                <!-- ========================= -->

                <?php if ($_SESSION['role'] == 'admin') { ?>

                    <li class="nav-item mb-2">
                        <a href="siswa.php"
                           class="nav-link text-white">
                            Kelola Siswa
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="guru.php"
                           class="nav-link text-white">
                            Kelola Guru
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="kelas.php"
                           class="nav-link text-white">
                            Kelola Kelas
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="tahun_ajaran.php"
                           class="nav-link text-white">
                            Tahun Ajaran
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="penempatan_siswa.php"
                           class="nav-link text-white">
                            Penempatan Siswa
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="wali_kelas.php"
                           class="nav-link text-white">
                            Wali Kelas
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="kategori_pelanggaran.php"
                           class="nav-link text-white">
                            Kategori Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="jenis_pelanggaran.php"
                           class="nav-link text-white">
                            Jenis Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="cetak_export.php"
                           class="nav-link text-white">
                            Cetak / Export
                        </a>
                    </li>

                <?php } ?>


                <!-- ========================= -->
                <!-- MENU KHUSUS GURU -->
                <!-- ========================= -->

                <?php if ($_SESSION['role'] == 'guru') { ?>

                    <li class="nav-item mb-2">
                        <a href="catat_pelanggaran.php"
                           class="nav-link text-white">
                            Catat Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="tindakan.php"
                           class="nav-link text-white">
                            Tindakan
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="riwayat.php"
                           class="nav-link text-white">
                            Riwayat
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="rekap_poin.php"
                           class="nav-link text-white">
                            Rekap Poin
                        </a>
                    </li>

                <?php } ?>


                <hr class="text-secondary">


                <li class="nav-item mb-2">
    <a href="about_me.php" class="nav-link text-white">
        About Me
    </a>
</li>

                <!-- LOGOUT -->
                <li class="nav-item">
                    <a href="logout.php"
                       class="nav-link text-danger">
                        Logout
                    </a>
                </li>

            </ul>

        </div>


        <!-- ========================= -->
        <!-- KONTEN -->
        <!-- ========================= -->

        <main class="col-md-9 col-lg-10 p-4">

            <?php if ($_SESSION['role'] == 'admin') { ?>

                <!-- DASHBOARD ADMIN -->

                <h2>Dashboard Admin</h2>

                <p>
                    Selamat datang,
                    <b><?= $_SESSION['role']; ?></b>
                </p>

                <div class="row">

                    <!-- SISWA -->
                    <div class="col-md-4 mb-3">

                        <div class="card shadow-sm">

                            <div class="card-body">

                                <h5 class="card-title">
                                   Siswa
                                </h5>

                                <p class="card-text">
                                    Kelola data siswa.
                                </p>

                                <a href="siswa.php"
                                   class="btn btn-primary">
                                    Lihat Data
                                </a>
                                

                            </div>

                        </div>

                    </div>


                    <!-- GURU -->
                    <div class="col-md-4 mb-3">

                        <div class="card shadow-sm">

                            <div class="card-body">

                                <h5 class="card-title">
                                     Guru
                                </h5>

                                <p class="card-text">
                                    Kelola data guru.
                                </p>

                                <a href="guru.php"
                                   class="btn btn-primary">
                                    Lihat Data
                                </a>

                            </div>

                        </div>

                    </div>


                    <!-- KELAS -->
                    <div class="col-md-4 mb-3">

                        <div class="card shadow-sm">

                            <div class="card-body">

                                <h5 class="card-title">
                                 Kelas
                                </h5>

                                <p class="card-text">
                                    Kelola data kelas.
                                </p>

                                <a href="kelas.php"
                                   class="btn btn-primary">
                                    Lihat Data
                                </a>

                            </div>

                        </div>

                    </div>

                        <!-- Tahun Ajaran -->
                    <div class="col-md-4 mb-3">

                        <div class="card shadow-sm">

                            <div class="card-body">

                                <h5 class="card-title">
                               Data tahun ajaran
                                </h5>

                                <p class="card-text">
                                    Kelola data tahun ajaran.
                                </p>

                                <a href="tahun_ajaran.php"
                                   class="btn btn-primary">
                                    Lihat Data
                                </a>

                            </div>

                        </div>

                    </div>

                        <!-- Penempatan siswa -->
                    <div class="col-md-4 mb-3">

                        <div class="card shadow-sm">

                            <div class="card-body">

                                <h5 class="card-title">
                                Data penempatan siswa
                                </h5>

                                <p class="card-text">
                                    Kelola data penempatan siswa.
                                </p>

                                <a href="penempatan_siswa.php"
                                   class="btn btn-primary">
                                    Lihat Data
                                </a>

                            </div>

                        </div>

                    </div>
                         <!-- Wali KELAS -->
                    <div class="col-md-4 mb-3">

                        <div class="card shadow-sm">

                            <div class="card-body">

                                <h5 class="card-title">
                                 Data wali kelas
                                </h5>

                                <p class="card-text">
                                    Kelola data wali kelas.
                                </p>

                                <a href="wali_kelas.php"
                                   class="btn btn-primary">
                                    Lihat Data
                                </a>

                            </div>

                        </div>

                    </div>

                        <!-- Kategori pelanggaran -->
                    <div class="col-md-4 mb-3">

                        <div class="card shadow-sm">

                            <div class="card-body">

                                <h5 class="card-title">
                                 Data kategori pelanggaran
                                </h5>

                                <p class="card-text">
                                    Kelola data kategori pelanggaran.
                                </p>

                                <a href="kategori_pelanggaran.php"
                                   class="btn btn-primary">
                                    Lihat Data
                                </a>

                            </div>

                        </div>

                    </div>

                        <!-- Jenis Pelanggran -->
                    <div class="col-md-4 mb-3">

                        <div class="card shadow-sm">

                            <div class="card-body">

                                <h5 class="card-title">
                                 Data jenis pelanggaran
                                </h5>

                                <p class="card-text">
                                    Kelola data jenis pelanggaran.
                                </p>

                                <a href="jenis_pelanggaran.php"
                                   class="btn btn-primary">
                                    Lihat Data
                                </a>

                            </div>

                        </div>

                    </div>
                    
                </div>



            <?php } elseif ($_SESSION['role'] == 'guru') { ?>

                <!-- DASHBOARD GURU -->

                <h2>Dashboard Guru</h2>

                <p>
                    Selamat datang,
                    <b><?= $_SESSION['role']; ?></b>
                </p>

                <div class="row">

                    <!-- CATAT PELANGGARAN -->
                    <div class="col-md-4 mb-3">

                        <div class="card shadow-sm">

                            <div class="card-body">

                                <h5 class="card-title">
                                    Catat Pelanggaran
                                </h5>

                                <p class="card-text">
                                    Mencatat pelanggaran siswa.
                                </p>

                                <a href="catat_pelanggaran.php"
                                   class="btn btn-primary">
                                    Buka
                                </a>

                            </div>

                        </div>

                    </div>


                    <!-- TINDAKAN -->
                    <div class="col-md-4 mb-3">

                        <div class="card shadow-sm">

                            <div class="card-body">

                                <h5 class="card-title">
                                    Tindakan
                                </h5>

                                <p class="card-text">
                                    Mengelola tindakan terhadap pelanggaran.
                                </p>

                                <a href="tindakan.php"
                                   class="btn btn-primary">
                                    Buka
                                </a>

                            </div>

                        </div>

                    </div>


                    <!-- RIWAYAT -->
                    <div class="col-md-4 mb-3">

                        <div class="card shadow-sm">

                            <div class="card-body">

                                <h5 class="card-title">
                                    Riwayat
                                </h5>

                                <p class="card-text">
                                    Melihat riwayat pelanggaran siswa.
                                </p>

                                <a href="riwayat.php"
                                   class="btn btn-primary">
                                    Buka
                                </a>

                            </div>

                        </div>

                    </div>


                    <!-- REKAP POIN -->
                    <div class="col-md-4 mb-3">

                        <div class="card shadow-sm">

                            <div class="card-body">

                                <h5 class="card-title">
                                    Rekap Poin
                                </h5>

                                <p class="card-text">
                                    Melihat rekap poin siswa.
                                </p>

                                <a href="rekap_poin.php"
                                   class="btn btn-primary">
                                    Buka
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            <?php } ?>

        </main>

    </div>

</div>

</body>

</html>