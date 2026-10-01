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

$id = $_GET['id'];

$query = mysqli_query($koneksi, "SELECT * FROM t_siswa WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data siswa tidak ditemukan";
    exit;
}

if (isset($_POST['update'])) {

    $nis = $_POST['nis'];
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $status_aktif = $_POST['status_aktif'];

    $query = "UPDATE t_siswa SET
                nis='$nis',
                nisn='$nisn',
                nama='$nama',
                jenis_kelamin='$jenis_kelamin',
                tanggal_lahir='$tanggal_lahir',
                alamat='$alamat',
                status_aktif='$status_aktif'
              WHERE id='$id'";

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: siswa.php");
        exit;
    } else {
        echo "Gagal mengubah data: " . mysqli_error($koneksi);
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body>

<div class="container mt-4">

    <h2>Edit Siswa</h2>

    <form method="POST">

        <div class="mb-3">
            <label>NIS</label>
            <input type="text"
                   name="nis"
                   class="form-control"
                   value="<?= htmlspecialchars($data['nis']); ?>"
                   required>
        </div>

        <div class="mb-3">
            <label>NISN</label>
            <input type="text"
                   name="nisn"
                   class="form-control"
                   value="<?= htmlspecialchars($data['nisn']); ?>"
                   required>
        </div>

        <div class="mb-3">
            <label>Nama</label>
            <input type="text"
                   name="nama"
                   class="form-control"
                   value="<?= htmlspecialchars($data['nama']); ?>"
                   required>
        </div>

        <div class="mb-3">
            <label>Jenis Kelamin</label>

            <select name="jenis_kelamin"
                    class="form-control"
                    required>

                <option value="L"
                    <?= $data['jenis_kelamin'] == 'L' ? 'selected' : ''; ?>>
                    Laki-laki
                </option>

                <option value="P"
                    <?= $data['jenis_kelamin'] == 'P' ? 'selected' : ''; ?>>
                    Perempuan
                </option>

            </select>
        </div>

        <div class="mb-3">
            <label>Tanggal Lahir</label>

            <input type="date"
                   name="tanggal_lahir"
                   class="form-control"
                   value="<?= $data['tanggal_lahir']; ?>"
                   required>
        </div>

        <div class="mb-3">
            <label>Alamat</label>

            <textarea name="alamat"
                      class="form-control"
                      required><?= htmlspecialchars($data['alamat']); ?></textarea>
        </div>

        <div class="mb-3">
            <label>Status</label>

            <select name="status_aktif"
                    class="form-control"
                    required>

                <option value="1"
                    <?= $data['status_aktif'] == 1 ? 'selected' : ''; ?>>
                    Aktif
                </option>

                <option value="0"
                    <?= $data['status_aktif'] == 0 ? 'selected' : ''; ?>>
                    Tidak Aktif
                </option>

            </select>
        </div>

        <button type="submit"
                name="update"
                class="btn btn-primary">
            Simpan Perubahan
        </button>

        <a href="siswa.php"
           class="btn btn-secondary">
            Kembali
        </a>

    </form>

</div>

</body>
</html>