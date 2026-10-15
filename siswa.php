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

$query = "SELECT * FROM t_siswa ORDER BY id ASC";
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

    <title>Kelola Siswa</title>

    <!-- BOOTSTRAP -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous"
    >

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
                       class="nav-link text-white">

                        Dashboard

                    </a>

                </li>

                <!-- MENU KHUSUS ADMIN -->
                <?php if ($_SESSION['role'] == 'admin') { ?>

                    <li class="nav-item mb-2">

                        <a href="siswa.php"
                           class="nav-link active">

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

                        <a href="laporan.php"
                           class="nav-link text-white">

                          Laporan

                              <li class="nav-item mb-2">

                        <a href="about_me.php"
                           class="nav-link text-white">

                        About Me

                        </a>

                    </li>
                        </a>

                    </li>

                <?php } ?>

                <!-- MENU KHUSUS GURU -->
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

          

                <!-- LOGOUT -->
                <li class="nav-item">

                    <a href="logout.php"
                       class="nav-link text-danger"
                       onclick="return confirm('Yakin ingin logout?')">

                        Logout

                    </a>

                </li>

            </ul>

        </div>


        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <!-- JUDUL -->
            <h1 class="mb-3">
                Data Siswa
            </h1>

            <!-- TOMBOL -->
            <div class="mb-3">

                <a
                    href="dashboard.php"
                    class="btn btn-secondary"
                >
                    Kembali ke Dashboard
                </a>

                <a
                    href="tambah_siswa.php"
                    class="btn btn-primary"
                >
                    + Tambah Siswa
                </a>

            </div>

            <!-- TABEL -->
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>No</th>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Lahir</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <th style="width: 150px;">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php

                    $no = 1;

                    while ($data = mysqli_fetch_assoc($result)) {

                    ?>

                        <tr>

                            <!-- NO -->
                            <td>
                                <?= $no++; ?>
                            </td>

                            <!-- NIS -->
                            <td>
                                <?= htmlspecialchars($data['nis']); ?>
                            </td>

                            <!-- NISN -->
                            <td>
                                <?= htmlspecialchars($data['nisn']); ?>
                            </td>

                            <!-- NAMA -->
                            <td>
                                <?= htmlspecialchars($data['nama']); ?>
                            </td>

                            <!-- JENIS KELAMIN -->
                            <td>
                                <?= htmlspecialchars($data['jenis_kelamin']); ?>
                            </td>

                            <!-- TANGGAL LAHIR -->
                            <td>
                                <?= htmlspecialchars($data['tanggal_lahir']); ?>
                            </td>

                            <!-- ALAMAT -->
                            <td>
                                <?= htmlspecialchars($data['alamat']); ?>
                            </td>

                            <!-- STATUS -->
                            <td>

                                <?php if ($data['status_aktif'] == 1) { ?>

                                    <span class="badge bg-success">
                                        Aktif
                                    </span>

                                <?php } else { ?>

                                    <span class="badge bg-danger">
                                        Tidak Aktif
                                    </span>

                                <?php } ?>

                            </td>

                            <!-- AKSI -->
                            <td>

                                <a
                                    href="edit_siswa.php?id=<?= $data['id']; ?>"
                                    class="btn btn-primary btn-sm"
                                >
                                    Edit
                                </a>

                                <a
                                    href="hapus_siswa.php?id=<?= $data['id']; ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus data siswa ini?')"
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
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>
