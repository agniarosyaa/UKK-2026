
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

// AMBIL DATA KELAS
$data = mysqli_query(
    $koneksi,
    "SELECT * FROM t_kelas ORDER BY id ASC"
);

if (!$data) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Kelas</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">

            <h4 class="text-white mb-4">Sistem Pelanggaran</h4>

            <nav class="nav nav-pills flex-column gap-2">
                <a href="dashboard.php" class="nav-link text-white">Dashboard</a>
                <a href="siswa.php" class="nav-link text-white">Kelola Siswa</a>
                <a href="guru.php" class="nav-link text-white">Kelola Guru</a>
                <a href="kelas.php" class="nav-link active">Kelola Kelas</a>
                <a href="tahun_ajaran.php" class="nav-link text-white">Tahun Ajaran</a>
                <a href="penempatan_siswa.php" class="nav-link text-white">Penempatan Siswa</a>
                <a href="wali_kelas.php" class="nav-link text-white">Wali Kelas</a>
                <a href="kategori_pelanggaran.php" class="nav-link text-white">Kategori Pelanggaran</a>
                <a href="jenis_pelanggaran.php" class="nav-link text-white">Jenis Pelanggaran</a>
                <a href="laporan.php" class="nav-link text-white">Laporan</a>

                <hr class="text-secondary">

                <a href="logout.php" class="nav-link text-danger"
                   onclick="return confirm('Yakin ingin logout?')">
                    Logout
                </a>
            </nav>

        </aside>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2 class="mb-3">Data Kelas</h2>

            <!-- NOTIFIKASI -->
            <?php if (isset($_GET['pesan'])) { ?>

                <?php if ($_GET['pesan'] == 'tambah') { ?>
                    <div class="alert alert-success">
                        Kelas berhasil ditambahkan.
                    </div>
                <?php } ?>

                <?php if ($_GET['pesan'] == 'edit') { ?>
                    <div class="alert alert-success">
                        Data kelas berhasil diperbarui.
                    </div>
                <?php } ?>

                <?php if ($_GET['pesan'] == 'hapus') { ?>
                    <div class="alert alert-success">
                        Data kelas berhasil dihapus.
                    </div>
                <?php } ?>

                <?php if ($_GET['pesan'] == 'gagal_hapus') { ?>
                    <div class="alert alert-danger">
                        Kelas tidak bisa dihapus karena masih digunakan
                        pada penempatan siswa atau tabel lain.
                    </div>
                <?php } ?>

            <?php } ?>

            <!-- TOMBOL -->
            <div class="d-flex flex-wrap gap-2 mb-3">

                <a href="dashboard.php" class="btn btn-secondary">
                    Kembali ke Dashboard
                </a>

                <a href="tambah_kelas.php" class="btn btn-primary">
                    + Tambah Kelas
                </a>

            </div>

            <!-- TABEL -->
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="table-responsive">

                        <table id="tabelKelas"
                               class="table table-bordered table-hover align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Tingkat</th>
                                    <th>Jurusan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php $no = 1; ?>

                            <?php while ($row = mysqli_fetch_assoc($data)) { ?>
                                <tr>
                                    <td><?= $no++; ?></td>

                                    <td>
                                        <?= htmlspecialchars($row['nama'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['tingkat'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['jurusan'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?php if (($row['status_aktif'] ?? 0) == 1) { ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php } else { ?>
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        <?php } ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <a href="edit_kelas.php?id=<?= (int) $row['id']; ?>"
                                           class="btn btn-primary btn-sm">
                                            Edit
                                        </a>

                                        <a href="hapus_kelas.php?id=<?= (int) $row['id']; ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus kelas ini?')">
                                            Hapus
                                        </a>
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

<script>
$(document).ready(function () {
    $('#tabelKelas').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        searching: true,
        ordering: true,

        columnDefs: [
            {
                orderable: false,
                targets: [0, 5]
            }
        ],

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ data)",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada data kelas",

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
