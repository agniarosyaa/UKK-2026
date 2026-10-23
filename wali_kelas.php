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

/* AMBIL DATA WALI KELAS */
$query = "SELECT
            wk.*,
            ta.nama AS nama_tahun_ajaran,
            k.nama AS nama_kelas,
            g.nama AS nama_guru
          FROM t_wali_kelas wk
          LEFT JOIN t_tahun_ajaran ta ON wk.tahun_ajaran_id = ta.id
          LEFT JOIN t_kelas k ON wk.kelas_id = k.id
          LEFT JOIN t_guru g ON wk.guru_id = g.id
          ORDER BY wk.id ASC";

$data = mysqli_query($koneksi, $query);

if (!$data) {
    die("Gagal mengambil data wali kelas: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Wali Kelas</title>

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

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">

            <h5 class="text-white mb-4">Sistem Pelanggaran</h5>

            <ul class="nav nav-pills flex-column gap-2">

                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link text-white">
                        Dashboard
                    </a>
                </li>

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
                    <a href="wali_kelas.php" class="nav-link active">
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

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2 class="mb-4">Data Wali Kelas</h2>

            <!-- TOMBOL -->
            <a href="dashboard.php" class="btn btn-secondary mb-3">
                Kembali ke Dashboard
            </a>

            <a href="tambah_wali_kelas.php" class="btn btn-primary mb-3">
                + Tambah Wali Kelas
            </a>

            <!-- TABEL WALI KELAS -->
            <div class="card shadow-sm">
                <div class="card-body">

                    <!-- PESAN BERHASIL -->
                    <?php if (isset($_GET['pesan'])) { ?>

                        <?php if ($_GET['pesan'] == 'tambah') { ?>
                            <div class="alert alert-success">
                                Data wali kelas berhasil ditambahkan.
                            </div>

                        <?php } elseif ($_GET['pesan'] == 'edit') { ?>
                            <div class="alert alert-success">
                                Data wali kelas berhasil diubah.
                            </div>

                        <?php } elseif ($_GET['pesan'] == 'hapus') { ?>
                            <div class="alert alert-success">
                                Data wali kelas berhasil dihapus.
                            </div>
                        <?php } ?>

                    <?php } ?>

                    <div class="table-responsive">

                        <table id="tabelWaliKelas"
                               class="table table-bordered table-hover align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Kelas</th>
                                    <th>Guru</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php
                            $no = 1;

                            if (mysqli_num_rows($data) > 0) {
                                while ($row = mysqli_fetch_assoc($data)) {
                            ?>

                                <tr>
                                    <td><?= $no++; ?></td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_tahun_ajaran'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_kelas'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_guru'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?= !empty($row['tanggal_mulai'])
                                            ? date('d-m-Y', strtotime($row['tanggal_mulai']))
                                            : '-'; ?>
                                    </td>

                                    <td>
                                        <?= !empty($row['tanggal_selesai'])
                                            ? date('d-m-Y', strtotime($row['tanggal_selesai']))
                                            : '-'; ?>
                                    </td>

                                    <td>
                                        <?php if ((int)($row['status_aktif'] ?? 0) === 1) { ?>
                                            <span class="badge bg-success">
                                                Aktif
                                            </span>
                                        <?php } else { ?>
                                            <span class="badge bg-secondary">
                                                Tidak Aktif
                                            </span>
                                        <?php } ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <a href="edit_wali_kelas.php?id=<?= (int)$row['id']; ?>"
                                           class="btn btn-primary btn-sm">
                                            Edit
                                        </a>

                                        <a href="hapus_wali_kelas.php?id=<?= (int)$row['id']; ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus data wali kelas ini?')">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>

                            <?php
                                }
                            } else {
                            ?>

                                <tr>
                                    <td colspan="8" class="text-center text-secondary py-4">
                                        Belum ada data wali kelas.
                                    </td>
                                </tr>

                            <?php } ?>
                            </tbody>

                        </table>

                    </div>

                    <p class="text-muted mb-0">
                        Total data wali kelas:
                        <strong><?= mysqli_num_rows($data); ?></strong>
                    </p>

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

<!-- AKTIFKAN DATATABLES -->
<script>
$(document).ready(function () {
    $('#tabelWaliKelas').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        searching: true,
        ordering: true,

        columnDefs: [
            {
                orderable: false,
                targets: [0, 7]
            }
        ],

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ data)",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada data wali kelas",

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