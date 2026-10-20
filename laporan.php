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

$query = "SELECT * FROM t_pelanggaran_siswa ORDER BY tanggal DESC";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Gagal mengambil data laporan: " . mysqli_error($koneksi));
}

/* AMBIL DATA DAN HITUNG RINGKASAN */
$rows = [];
$total_data = 0;
$total_selesai = 0;
$total_diproses = 0;
$total_poin = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
    $total_data++;

    $status = strtolower(trim($row['status'] ?? ''));

    if ($status === 'selesai') {
        $total_selesai++;
    } elseif ($status === 'diproses') {
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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #263238;
        }

        .sidebar {
            min-height: 100vh;
        }

        .sidebar .nav-link {
            font-size: 14px;
            padding: 10px 12px;
            border-radius: 7px;
        }

        .page-title {
            font-weight: 700;
            color: #212529;
        }

        .summary-card {
            border: 0;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .summary-label {
            color: #6c757d;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .summary-number {
            font-size: 27px;
            font-weight: 700;
            margin-bottom: 0;
        }

        .summary-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            flex-shrink: 0;
        }

        .report-card {
            border: 0;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        #tabelLaporan {
            width: 100% !important;
            font-size: 13px;
        }

        #tabelLaporan thead th {
            white-space: nowrap;
            vertical-align: middle;
            padding: 13px 10px;
        }

        #tabelLaporan tbody td {
            padding: 12px 10px;
            vertical-align: middle;
        }

        .filter-label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .table-responsive {
            border-radius: 8px;
        }

        .report-subtitle {
            color: #6c757d;
            font-size: 14px;
        }

        @media print {
            @page {
                size: landscape;
                margin: 10mm;
            }

            body {
                background: #fff !important;
                font-size: 10px;
                color: #000;
            }

            .sidebar,
            .no-print,
            .dataTables_length,
            .dataTables_filter,
            .dataTables_info,
            .dataTables_paginate {
                display: none !important;
            }

            main {
                width: 100% !important;
                max-width: 100% !important;
                flex: 0 0 100% !important;
                padding: 0 !important;
            }

            .container-fluid,
            .row {
                display: block !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .report-card,
            .summary-card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }

            .summary-card {
                padding: 10px !important;
            }

            .summary-number {
                font-size: 18px;
            }

            .table-responsive {
                overflow: visible !important;
            }

            #tabelLaporan {
                width: 100% !important;
                font-size: 8px !important;
            }

            #tabelLaporan thead th,
            #tabelLaporan tbody td {
                padding: 5px !important;
                white-space: normal !important;
            }

            #tabelLaporan thead {
                display: table-header-group;
            }

            #tabelLaporan tr {
                break-inside: avoid;
            }

            .badge {
                color: #000 !important;
                background: transparent !important;
                border: 1px solid #777;
            }

            .print-only {
                display: block !important;
            }
        }

        .print-only {
            display: none;
        }
    </style>
</head>

<body class="bg-light">

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="sidebar col-md-3 col-lg-2 bg-dark p-3 no-print">

            <h5 class="text-white mb-4">
                Sistem Pelanggaran
            </h5>

            <nav class="nav nav-pills flex-column gap-2">

                <a class="nav-link text-white" href="dashboard.php">
                    Dashboard
                </a>

                <a class="nav-link text-white" href="siswa.php">
                    Kelola Siswa
                </a>

                <a class="nav-link text-white" href="guru.php">
                    Kelola Guru
                </a>

                <a class="nav-link text-white" href="kelas.php">
                    Kelola Kelas
                </a>

                <a class="nav-link text-white" href="tahun_ajaran.php">
                    Tahun Ajaran
                </a>

                <a class="nav-link text-white" href="penempatan_siswa.php">
                    Penempatan Siswa
                </a>

                <a class="nav-link text-white" href="wali_kelas.php">
                    Wali Kelas
                </a>

                <a class="nav-link text-white" href="kategori_pelanggaran.php">
                    Kategori Pelanggaran
                </a>

                <a class="nav-link text-white" href="jenis_pelanggaran.php">
                    Jenis Pelanggaran
                </a>

                <a class="nav-link active" href="laporan.php">
                    Laporan
                </a>

                <a class="nav-link text-white" href="about_me.php">
                    About Me
                </a>

                <hr class="border-secondary">

                <a class="nav-link text-danger"
                   href="logout.php"
                   onclick="return confirm('Yakin ingin logout?')">
                    Logout
                </a>

            </nav>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-3 p-md-4 p-lg-4">

            <!-- HEADER LAPORAN -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

                <div>
                    <h3 class="page-title mb-1">
                        Laporan Pelanggaran Siswa
                    </h3>

                    <p class="report-subtitle mb-0">
                        Rekap dan pemantauan data pelanggaran siswa
                    </p>
                </div>

                <div class="d-flex gap-2 no-print">

                    <button type="button"
                            onclick="resetFilter()"
                            class="btn btn-outline-secondary">
                        Reset Filter
                    </button>

                    <button type="button"
                            onclick="window.print()"
                            class="btn btn-primary px-4">
                        Cetak Laporan
                    </button>

                </div>
            </div>

            <!-- JUDUL KHUSUS CETAK -->
            <div class="print-only mb-3">
                <h4 class="fw-bold">Laporan Pelanggaran Siswa</h4>
                <p>Dicetak pada: <?= date('d-m-Y H:i'); ?></p>
            </div>

            <!-- KARTU RINGKASAN -->
            <div class="row g-3 mb-4">

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card summary-card">
                        <div class="card-body d-flex align-items-center justify-content-between">

                            <div>
                                <p class="summary-label">Total Pelanggaran</p>
                                <h4 class="summary-number">
                                    <?= number_format($total_data, 0, ',', '.'); ?>
                                </h4>
                                <small class="text-muted">Seluruh data</small>
                            </div>

                            <div class="summary-icon bg-primary-subtle text-primary">
                                <span>📋</span>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card summary-card">
                        <div class="card-body d-flex align-items-center justify-content-between">

                            <div>
                                <p class="summary-label">Pelanggaran Selesai</p>
                                <h4 class="summary-number text-success">
                                    <?= number_format($total_selesai, 0, ',', '.'); ?>
                                </h4>
                                <small class="text-muted">Status selesai</small>
                            </div>

                            <div class="summary-icon bg-success-subtle text-success">
                                <span>✓</span>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card summary-card">
                        <div class="card-body d-flex align-items-center justify-content-between">

                            <div>
                                <p class="summary-label">Sedang Diproses</p>
                                <h4 class="summary-number text-warning">
                                    <?= number_format($total_diproses, 0, ',', '.'); ?>
                                </h4>
                                <small class="text-muted">Perlu ditindaklanjuti</small>
                            </div>

                            <div class="summary-icon bg-warning-subtle text-warning">
                                <span>◷</span>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card summary-card">
                        <div class="card-body d-flex align-items-center justify-content-between">

                            <div>
                                <p class="summary-label">Total Poin</p>
                                <h4 class="summary-number text-primary">
                                    <?= number_format($total_poin, 0, ',', '.'); ?>
                                </h4>
                                <small class="text-muted">Akumulasi poin</small>
                            </div>

                            <div class="summary-icon bg-info-subtle text-info">
                                <span>★</span>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <!-- TABEL LAPORAN -->
            <div class="card report-card">
                <div class="card-body p-3 p-md-4">

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">

                        <div>
                            <h5 class="fw-bold mb-1">
                                Data Laporan
                            </h5>

                            <p class="text-muted small mb-0">
                                Gunakan filter untuk mencari data tertentu.
                            </p>
                        </div>

                        <span class="badge bg-primary-subtle text-primary px-3 py-2">
                            <?= number_format($total_data, 0, ',', '.'); ?> data
                        </span>

                    </div>

                    <!-- FILTER -->
                    <div class="row g-3 mb-4 no-print">

                        <div class="col-12 col-md-4 col-lg-3">
                            <label for="filterStatus" class="filter-label">
                                Status Pelanggaran
                            </label>

                            <select id="filterStatus" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Diproses">Diproses</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <label for="tanggalMulai" class="filter-label">
                                Dari Tanggal
                            </label>

                            <input type="date"
                                   id="tanggalMulai"
                                   class="form-control">
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <label for="tanggalAkhir" class="filter-label">
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

                            <?php foreach ($rows as $row) { ?>

                                <tr>
                                    <td></td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_siswa'] ?? ''); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_kelas'] ?? ''); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_guru'] ?? ''); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_pelanggaran'] ?? ''); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['pelanggaran_kategori_id'] ?? ''); ?>
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
                                        <?= htmlspecialchars($row['keterangan'] ?? ''); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['poin'] ?? '0'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['tindakan'] ?? ''); ?>
                                    </td>

                                    <td>
                                        <?php
                                        $status = $row['status'] ?? '';

                                        if (strtolower(trim($status)) === 'selesai') {
                                            echo '<span class="badge bg-success">Selesai</span>';
                                        } elseif (strtolower(trim($status)) === 'diproses') {
                                            echo '<span class="badge bg-warning text-dark">Diproses</span>';
                                        } else {
                                            echo '<span class="badge bg-secondary">'
                                                . htmlspecialchars($status ?: '-')
                                                . '</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>

                            <?php } ?>

                            </tbody>

                        </table>

                    </div>

                    <div class="d-flex flex-wrap justify-content-between gap-2 mt-3">
                        <small class="text-muted">
                            Total seluruh data:
                            <strong><?= number_format($total_data, 0, ',', '.'); ?></strong>
                        </small>

                        <small class="text-muted">
                            Total poin seluruh laporan:
                            <strong><?= number_format($total_poin, 0, ',', '.'); ?></strong>
                        </small>
                    </div>

                </div>
            </div>

            <!-- FOOTER -->
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

    // Ubah format tanggal dari dd-mm-yyyy menjadi yyyy-mm-dd
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

    // Filter status dan tanggal
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
        searching: true,
        ordering: true,
        order: [[6, 'desc']],
        autoWidth: false,

        columnDefs: [
            {
                orderable: false,
                searchable: false,
                targets: 0
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