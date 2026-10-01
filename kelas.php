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

/* TAMBAH DATA */
if (isset($_POST['tambah'])) {

    $nama = $_POST['nama'];
    $tingkat = $_POST['tingkat'];
    $jurusan = $_POST['jurusan'];

    mysqli_query($koneksi, "INSERT INTO t_kelas
        (nama, tingkat, jurusan, status_aktif, created_at, update_at)
        VALUES
        ('$nama', '$tingkat', '$jurusan', 1, NOW(), NOW())");

    header("Location: kelas.php");
    exit;
}

/* HAPUS DATA */
if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    mysqli_query($koneksi, "DELETE FROM t_kelas WHERE id='$id'");

    header("Location: kelas.php");
    exit;
}

/* DATA KELAS */
$data = mysqli_query($koneksi, "SELECT * FROM t_kelas ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Kelas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body>

<div class="container mt-4">

    <h2>Kelola Kelas</h2>

    <a href="dashboard.php" class="btn btn-secondary mb-3">
        Kembali ke Dashboard
    </a>

    <button class="btn btn-primary mb-3"
            data-bs-toggle="modal"
            data-bs-target="#modalTambah">
        + Tambah Kelas
    </button>

    <table class="table table-bordered">

        <thead>
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

        <?php
        $no = 1;

        while ($row = mysqli_fetch_assoc($data)) {
        ?>

            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['nama']); ?></td>
                <td><?= htmlspecialchars($row['tingkat']); ?></td>
                <td><?= htmlspecialchars($row['jurusan']); ?></td>

                <td>
                    <?php if ($row['status_aktif'] == 1) { ?>
                        <span class="badge bg-success">Aktif</span>
                    <?php } else { ?>
                        <span class="badge bg-secondary">Tidak Aktif</span>
                    <?php } ?>
                </td>

                <td>
                    <a href="kelas.php?hapus=<?= $row['id']; ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Yakin ingin menghapus data ini?')">
                        Hapus
                    </a>
                </td>
            </tr>

        <?php } ?>

        </tbody>

    </table>

</div>


<!-- MODAL TAMBAH -->

<div class="modal fade" id="modalTambah">

    <div class="modal-dialog">

        <div class="modal-content">

            <form method="POST">

                <div class="modal-header">
                    <h5>Tambah Kelas</h5>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Nama Kelas</label>
                        <input type="text"
                               name="nama"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Tingkat</label>
                        <input type="number"
                               name="tingkat"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Jurusan</label>
                        <input type="text"
                               name="jurusan"
                               class="form-control"
                               required>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="submit"
                            name="tambah"
                            class="btn btn-primary">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>