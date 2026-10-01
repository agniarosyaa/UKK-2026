<?php

include "config/koneksi.php";

// Ambil data tahun ajaran
$query = "SELECT * FROM t_tahun_ajaran ORDER BY id ASC";

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

    <title>Kelola Tahun Ajaran</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, Helvetica, sans-serif;
            background-color: white;
            color: #111827;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            font-size: 30px;
            margin: 0 0 10px 0;
            font-weight: 500;
        }

        .button-area {
            margin-bottom: 14px;
        }

        .btn {
            display: inline-block;
            padding: 9px 14px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .btn-dashboard {
            background-color: #6c757d;
        }

        .btn-dashboard:hover {
            background-color: #5c636a;
        }

        .btn-tambah {
            background-color: #0d6efd;
        }

        .btn-tambah:hover {
            background-color: #0b5ed7;
        }

        .btn-hapus {
            background-color: #dc3545;
            padding: 7px 10px;
            font-size: 13px;
        }

        .btn-hapus:hover {
            background-color: #bb2d3b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th,
        td {
            border: 1px solid #dee2e6;
            padding: 14px 9px;
            text-align: left;
            font-size: 14px;
        }

        th {
            font-weight: bold;
            background-color: white;
        }

        .status {
            display: inline-block;
            padding: 3px 7px;
            border-radius: 5px;
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        .aktif {
            background-color: #198754;
        }

        .tidak-aktif {
            background-color: #dc3545;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Kelola Tahun Ajaran</h1>

    <div class="button-area">

        <a href="dashboard.php" class="btn btn-dashboard">
            Kembali ke Dashboard
        </a>

        <a href="tambah_tahun_ajaran.php" class="btn btn-tambah">
            + Tambah Tahun Ajaran
        </a>

    </div>

    <table>

        <thead>

            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

        </thead>

        <tbody>

        <?php

        $no = 1;

        while ($data = mysqli_fetch_assoc($result)) {

        ?>

            <tr>

                <td>
                    <?= $no++; ?>
                </td>

                <td>
                    <?= htmlspecialchars($data['nama']); ?>
                </td>

                <td>
                    <?= htmlspecialchars($data['tanggal_mulai']); ?>
                </td>

                <td>
                    <?= htmlspecialchars($data['tanggal_selesai']); ?>
                </td>

                <td>

                    <?php if ($data['status_aktif'] == 1) { ?>

                        <span class="status aktif">
                            Aktif
                        </span>

                    <?php } else { ?>

                        <span class="status tidak-aktif">
                            Tidak Aktif
                        </span>

                    <?php } ?>

                </td>

                <td>

                    <a
                        href="hapus_tahun_ajaran.php?id=<?= $data['id']; ?>"
                        class="btn btn-hapus"
                        onclick="return confirm('Yakin ingin menghapus tahun ajaran ini?')">

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

</body>

</html>