
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

$query = "SELECT p.*, k.nama AS nama_kategori
          FROM t_pelanggaran p
          LEFT JOIN t_pelanggaran_kategori k
          ON p.pelanggaran_kategori_id = k.id
          ORDER BY p.id ASC";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Gagal mengambil data: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jenis Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">
            <h5 class="text-white mb-4">Sistem Pelanggaran</h5>

            <ul class="nav nav-pills flex-column gap-2">
                <li class="nav-item"><a href="dashboard.php" class="nav-link text-white">Dashboard</a></li>
                <li class="nav-item"><a href="siswa.php" class="nav-link text-white">Kelola Siswa</a></li>
                <li class="nav-item"><a href="guru.php" class="nav-link text-white">Kelola Guru</a></li>
                <li class="nav-item"><a href="kelas.php" class="nav-link text-white">Kelola Kelas</a></li>
                <li class="nav-item"><a href="tahun_ajaran.php" class="nav-link text-white">Tahun Ajaran</a></li>
                <li class="nav-item"><a href="penempatan_siswa.php" class="nav-link text-white">Penempatan Siswa</a></li>
                <li class="nav-item"><a href="wali_kelas.php" class="nav-link text-white">Wali Kelas</a></li>
                <li class="nav-item"><a href="kategori_pelanggaran.php" class="nav-link text-white">Kategori Pelanggaran</a></li>
                <li class="nav-item"><a href="jenis_pelanggaran.php" class="nav-link active">Jenis Pelanggaran</a></li>
                <li class="nav-item"><a href="laporan.php" class="nav-link text-white">Laporan</a></li>

                <hr class="text-secondary">

                <li class="nav-item"><a href="about_me.php" class="nav-link text-white">About Me</a></li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger"
                       onclick="return confirm('Yakin ingin logout?')">Logout</a>
                </li>
            </ul>
        </aside>

        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2 class="mb-4">Data Jenis Pelanggaran</h2>

            <a href="dashboard.php" class="btn btn-secondary mb-3">
                Kembali ke Dashboard
            </a>

            <a href="tambah_jenis_pelanggaran.php" class="btn btn-primary mb-3">
                + Tambah Jenis Pelanggaran
            </a>

            <div class="card shadow-sm">
                <div class="card-body">

                    <?php if (($_GET['pesan'] ?? '') == 'tambah') { ?>
                        <div class="alert alert-success">Data jenis pelanggaran berhasil ditambahkan.</div>
                    <?php } elseif (($_GET['pesan'] ?? '') == 'edit') { ?>
                        <div class="alert alert-success">Data jenis pelanggaran berhasil diubah.</div>
                    <?php } elseif (($_GET['pesan'] ?? '') == 'hapus') { ?>
                        <div class="alert alert-success">Data jenis pelanggaran berhasil dihapus.</div>
                    <?php } ?>

                    <?php if (($_GET['pesan'] ?? '') == 'gagal_hapus') { ?>
                        <div class="alert alert-danger">Data tidak dapat dihapus karena mungkin masih digunakan.</div>
                    <?php } ?>

                    <div class="table-responsive">
                        <table id="tabelPelanggaran" class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kategori</th>
                                    <th>Kode</th>
                                    <th>Jenis Pelanggaran</th>
                                    <th>Poin</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['nama_kategori'] ?? '-'); ?></td>
                                    <td><?= htmlspecialchars($row['kode'] ?? ''); ?></td>
                                    <td><?= htmlspecialchars($row['nama'] ?? ''); ?></td>
                                    <td><?= (int)$row['poin']; ?></td>
                                    <td><?= htmlspecialchars($row['deskripsi'] ?? ''); ?></td>
                                    <td class="text-nowrap">
                                        <a href="edit_jenis_pelanggaran.php?id=<?= (int)$row['id']; ?>"
                                           class="btn btn-primary btn-sm">Edit</a>

                                        <a href="hapus_jenis_pelanggaran.php?id=<?= (int)$row['id']; ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus jenis pelanggaran ini?')">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </body>
                        </table>
                    </div>

                    <p class="text-muted mb-0">
                        Total data jenis pelanggaran:
                        <strong><?= mysqli_num_rows($result); ?></strong>
                    </p>

                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#tabelPelanggaran').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        columnDefs: [{ orderable: false, targets: [0, 6] }],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ data)",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada data jenis pelanggaran",
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
