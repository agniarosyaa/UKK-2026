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
    >

</head>

<body>

<div class="container-fluid p-4">

    <!-- JUDUL -->
    <h1 class="mb-3">
        Kelola Siswa
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

</div>

<!-- BOOTSTRAP JS -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>