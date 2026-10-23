<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (strtolower($_SESSION['role'] ?? '') != 'admin') {
    exit("Anda tidak memiliki akses");
}

include "config/koneksi.php";

$query = "SELECT * FROM t_pelanggaran_siswa ORDER BY tanggal DESC";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Gagal mengambil data laporan: " . mysqli_error($koneksi));
}

$rows = [];
$total_data = 0;
$total_selesai = 0;
$total_diproses = 0;
$total_poin = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
    $total_data++;

    $status = strtolower(trim($row['status'] ?? ''));

    if ($status == 'selesai') {
        $total_selesai++;
    } elseif ($status == 'diproses') {
        $total_diproses++;
    }

    $total_poin += (int)($row['poin'] ?? 0);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan Pelanggaran Siswa</title>

    <!-- BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- DATATABLES CSS -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR: DISAMAKAN DENGAN SISWA.PHP -->
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
                    <a href="laporan.php" class="nav-link active">
                        Laporan
                    </a>
                </li>

                <!-- GARIS PEMISAH DI BAWAH LAPORAN -->
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

            <!-- HEADER -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

                <div>
                    <h3 class="fw-bold mb-1">
                        Laporan Pelanggaran Siswa
                    </h3>

                    <p class="text-muted mb-0">
                        Rekap dan pemantauan data pelanggaran siswa
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <button type="button"
                            class="btn btn-outline-secondary"
                            onclick="resetFilter()">
                        Reset Filter
                    </button>

                    <button type="button"
                            class="btn btn-primary"
                            onclick="window.print()">
                        Cetak Laporan
                    </button>
                </div>

            </div>

            <!-- RINGKASAN -->
            <div class="row g-3 mb-4">

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-muted mb-2">Total Pelanggaran</p>
                            <h3 class="fw-bold text-primary">
                                <?= number_format($total_data, 0, ',', '.'); ?>
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-muted mb-2">Pelanggaran Selesai</p>
                            <h3 class="fw-bold text-success">
                                <?= number_format($total_selesai, 0, ',', '.'); ?>
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-muted mb-2">Sedang Diproses</p>
                            <h3 class="fw-bold text-warning">
                                <?= number_format($total_diproses, 0, ',', '.'); ?>
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-muted mb-2">Total Poin</p>
                            <h3 class="fw-bold text-info">
                                <?= number_format($total_poin, 0, ',', '.'); ?>
                            </h3>
                        </div>
                    </div>
                </div>

            </div>

            <!-- TABEL LAPORAN -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 p-md-4">

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">Data Laporan</h5>
                            <p class="text-muted small mb-0">
                                Cari dan filter data pelanggaran siswa.
                            </p>
                        </div>

                        <span class="badge bg-primary">
                            <?= number_format($total_data, 0, ',', '.'); ?> data
                        </span>
                    </div>

                    <!-- FILTER -->
                    <div class="row g-3 mb-4">

                        <div class="col-12 col-md-4">
                            <label for="filterStatus" class="form-label">
                                Status Pelanggaran
                            </label>

                            <select id="filterStatus" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Diproses">Diproses</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="tanggalMulai" class="form-label">
                                Dari Tanggal
                            </label>

                            <input type="date"
                                   id="tanggalMulai"
                                   class="form-control">
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="tanggalAkhir" class="form-label">
                                Sampai Tanggal
                            </label>

                            <input type="date"
                                   id="tanggalAkhir"
                                   class="form-control">
                        </div>

                    </div>

                    <!-- TABEL -->
                    <div class="table-responsive">
                        <table id="tabelLaporan"
                               class="table table-bordered table-hover align-middle">

                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Nama Guru</th>
                                    <th>Jenis Pelanggaran</th>
                                    <th>Kategori ID</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Poin</th>
                                    <th>Tindakan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php foreach ($rows as $row): ?>
                                <tr>
                                    <td></td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_siswa'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_kelas'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_guru'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_pelanggaran'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['pelanggaran_kategori_id'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?php
                                        if (!empty($row['tanggal'])) {
                                            echo date('d-m-Y', strtotime($row['tanggal']));
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['keterangan'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?= (int)($row['poin'] ?? 0); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['tindakan'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?php
                                        $status = trim($row['status'] ?? '');

                                        if (strtolower($status) == 'selesai') {
                                            echo '<span class="badge bg-success">Selesai</span>';
                                        } elseif (strtolower($status) == 'diproses') {
                                            echo '<span class="badge bg-warning text-dark">Diproses</span>';
                                        } else {
                                            echo '<span class="badge bg-secondary">'
                                                . htmlspecialchars($status ?: '-')
                                                . '</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between gap-2 mt-3">
                        <small class="text-muted">
                            Total data:
                            <strong>
                                <?= number_format($total_data, 0, ',', '.'); ?>
                            </strong>
                        </small>

                        <small class="text-muted">
                            Total poin:
                            <strong>
                                <?= number_format($total_poin, 0, ',', '.'); ?>
                            </strong>
                        </small>
                    </div>

                </div>
            </div>

            <div class="text-center text-muted small mt-4">
                Sistem Informasi Pelanggaran Siswa
            </div>

        </main>

    </div>
</div>

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {

    function ubahTanggal(tanggal) {
        if (!tanggal || tanggal === '-') {
            return '';
        }

        const bagian = tanggal.split('-');

        if (bagian.length !== 3) {
            return '';
        }

        return bagian[2] + '-' + bagian[1] + '-' + bagian[0];
    }

    $.fn.dataTable.ext.search.push(function (settings, data) {

        if (settings.nTable.id !== 'tabelLaporan') {
            return true;
        }

        const statusDipilih = $('#filterStatus').val();
        const tanggalMulai = $('#tanggalMulai').val();
        const tanggalAkhir = $('#tanggalAkhir').val();

        const tanggalBaris = ubahTanggal(data[6]);
        const statusBaris = $('<div>').html(data[10]).text().trim();

        if (statusDipilih && statusBaris !== statusDipilih) {
            return false;
        }

        if (tanggalMulai || tanggalAkhir) {
            if (!tanggalBaris) {
                return false;
            }

            if (tanggalMulai && tanggalBaris < tanggalMulai) {
                return false;
            }

            if (tanggalAkhir && tanggalBaris > tanggalAkhir) {
                return false;
            }
        }

        return true;
    });

    const tabel = $('#tabelLaporan').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        order: [[6, 'desc']],
        autoWidth: false,

        columnDefs: [
            {
                targets: 0,
                orderable: false,
                searchable: false
            },
            {
                targets: [0, 5, 6, 8, 10],
                className: 'text-center'
            }
        ],

        language: {
            search: "Cari:",
            searchPlaceholder: "Cari laporan...",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ data)",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada data pelanggaran.",

            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        },

        drawCallback: function () {
            const api = this.api();
            const info = api.page.info();

            api.column(0, { page: 'current' }).nodes().each(function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            });
        }
    });

    $('#filterStatus, #tanggalMulai, #tanggalAkhir').on('change', function () {
        tabel.draw();
    });

    window.resetFilter = function () {
        $('#filterStatus').val('');
        $('#tanggalMulai').val('');
        $('#tanggalAkhir').val('');
        tabel.search('').draw();
    };

});
</script>

</body>
</html>