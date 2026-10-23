<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (strtolower($_SESSION['role'] ?? '') != 'admin') {
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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- DataTables Bootstrap CSS -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR ADMIN -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">

            <h5 class="text-white mb-4">
                Sistem Pelanggaran
            </h5>

            <nav class="nav nav-pills flex-column gap-2">

                <a href="dashboard.php" class="nav-link text-white">
                    Dashboard
                </a>

                <a href="siswa.php" class="nav-link text-white">
                    Kelola Siswa
                </a>

                <a href="guru.php" class="nav-link active">
                    Kelola Guru
                </a>

                <a href="kelas.php" class="nav-link text-white">
                    Kelola Kelas
                </a>

                <a href="tahun_ajaran.php" class="nav-link text-white">
                    Tahun Ajaran
                </a>

                <a href="penempatan_siswa.php" class="nav-link text-white">
                    Penempatan Siswa
                </a>

                <a href="wali_kelas.php" class="nav-link text-white">
                    Wali Kelas
                </a>

                <a href="kategori_pelanggaran.php" class="nav-link text-white">
                    Kategori Pelanggaran
                </a>

                <a href="jenis_pelanggaran.php" class="nav-link text-white">
                    Jenis Pelanggaran
                </a>

                <a href="laporan.php" class="nav-link text-white">
                    Laporan
                </a>

                <!-- GARIS PEMISAH DI BAWAH LAPORAN -->
                <hr class="text-secondary">

                <!-- ABOUT ME DI BAWAH GARIS -->
                <a href="about_me.php" class="nav-link text-white">
                    About Me
                </a>

                <a href="logout.php"
                   class="nav-link text-danger"
                   onclick="return confirm('Yakin ingin logout?')">
                    Logout
                </a>

            </nav>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2 class="mb-3">Data Guru</h2>

            <!-- TOMBOL AKSI -->
            <div class="d-flex flex-wrap gap-2 mb-3">

                <a href="dashboard.php" class="btn btn-secondary">
                    Kembali ke Dashboard
                </a>

                <a href="tambah_guru.php" class="btn btn-primary">
                    + Tambah Guru
                </a>

            </div>

            <!-- TABEL GURU -->
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="table-responsive">

                        <table id="tabelGuru"
                               class="table table-bordered table-hover align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>User ID</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                            <?php if (mysqli_num_rows($result) > 0) { ?>

                                <?php $no = 1; ?>

                                <?php while ($data = mysqli_fetch_assoc($result)) { ?>

                                    <tr>
                                        <td><?= $no++; ?></td>

                                        <td>
                                            <?= htmlspecialchars($data['nip'] ?? '-'); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['nama'] ?? '-'); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['email'] ?? '-'); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['user_id'] ?? '-'); ?>
                                        </td>

                                        <td>
                                            <?php if (($data['status_aktif'] ?? 0) == 1) { ?>
                                                <span class="badge bg-success">
                                                    Aktif
                                                </span>
                                            <?php } else { ?>
                                                <span class="badge bg-danger">
                                                    Tidak Aktif
                                                </span>
                                            <?php } ?>
                                        </td>

                                        <td class="text-nowrap">
                                            <a href="edit_guru.php?id=<?= (int) $data['id']; ?>"
                                               class="btn btn-primary btn-sm">
                                                Edit
                                            </a>

                                            <a href="hapus_guru.php?id=<?= (int) $data['id']; ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Yakin ingin menghapus data guru ini?')">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>

                                <?php } ?>

                            <?php } else { ?>

                                <tr>
                                    <td colspan="7" class="text-center text-secondary py-4">
                                        Belum ada data guru.
                                    </td>
                                </tr>

                            <?php } ?>

                            </tbody>

                        </table>

                    </div>

                </div>
            </div>

        </main>

    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<!-- Pengaturan DataTables -->
<script>
$(document).ready(function () {

    $('#tabelGuru').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        searching: true,
        ordering: true,

        columnDefs: [
            {
                orderable: false,
                targets: [0, 6]
            }
        ],

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ data)",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada data guru",

            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });

});
</script>

</body>
</html>
