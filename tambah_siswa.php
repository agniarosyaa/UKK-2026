
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

$error = "";

$nis = "";
$nisn = "";
$nama = "";
$jenis_kelamin = "";
$tanggal_lahir = "";
$alamat = "";
$status_aktif = "1";

if (isset($_POST['simpan'])) {
    $nis = trim($_POST['nis'] ?? '');
    $nisn = trim($_POST['nisn'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $alamat = trim($_POST['alamat'] ?? '');
    $status_aktif = $_POST['status_aktif'] ?? '1';

    if (
        $nis == "" || $nisn == "" || $nama == "" ||
        $jenis_kelamin == "" || $tanggal_lahir == "" || $alamat == ""
    ) {
        $error = "Semua data wajib diisi.";
    } elseif (!in_array($jenis_kelamin, ['L', 'P'], true)) {
        $error = "Jenis kelamin tidak valid.";
    } elseif (!in_array($status_aktif, ['0', '1'], true)) {
        $error = "Status aktif tidak valid.";
    } else {

        $query = "INSERT INTO t_siswa
                  (nis, nisn, nama, jenis_kelamin, tanggal_lahir, alamat, status_aktif)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($koneksi, $query);

        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "ssssssi",
                $nis,
                $nisn,
                $nama,
                $jenis_kelamin,
                $tanggal_lahir,
                $alamat,
                $status_aktif
            );

        if (mysqli_stmt_execute($stmt)) {

    $_SESSION['notifikasi'] = [
        'tipe' => 'success',
        'pesan' => 'Data siswa berhasil ditambahkan!'
    ];

    mysqli_stmt_close($stmt);

    header("Location: siswa.php");
    exit;

} else {
                $error = "Gagal menambahkan data siswa: " .
                         mysqli_stmt_error($stmt);
            }

            mysqli_stmt_close($stmt);

        } else {
            $error = "Query gagal diproses: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Siswa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body>

<div class="container-fluid">
    <div class="row">

<aside class="col-md-3 col-lg-2 bg-dark vh-100 p-3 position-fixed top-0 start-0 overflow-auto">

            <h5 class="text-white mb-4">
                Sistem Pelanggaran
            </h5>

            <ul class="nav nav-pills flex-column gap-2">

                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link text-white">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a href="siswa.php" class="nav-link active">
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

        <main class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">

            <h2 class="mb-4">Tambah Siswa</h2>

            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white">
                    Form Tambah Siswa
                </div>

                <div class="card-body">

                    <?php if ($error != "") { ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error); ?>
                        </div>
                    <?php } ?>

                    <form method="POST">

                        <!-- NIS -->
                        <div class="mb-3">
                            <label for="nis" class="form-label">
                                NIS
                            </label>

                            <input
                                type="text"
                                name="nis"
                                id="nis"
                                class="form-control"
                                value="<?= htmlspecialchars($nis); ?>"
                                required>
                        </div>

                        <!-- NISN -->
                        <div class="mb-3">
                            <label for="nisn" class="form-label">
                                NISN
                            </label>

                            <input
                                type="text"
                                name="nisn"
                                id="nisn"
                                class="form-control"
                                value="<?= htmlspecialchars($nisn); ?>"
                                required>
                        </div>

                        <!-- NAMA SISWA -->
                        <div class="mb-3">
                            <label for="nama" class="form-label">
                                Nama Siswa
                            </label>

                            <input
                                type="text"
                                name="nama"
                                id="nama"
                                class="form-control"
                                value="<?= htmlspecialchars($nama); ?>"
                                required>
                        </div>

                        <!-- JENIS KELAMIN -->
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">
                                Jenis Kelamin
                            </label>

                            <select
                                name="jenis_kelamin"
                                id="jenis_kelamin"
                                class="form-select"
                                required>

                                <option value="">
                                    -- Pilih Jenis Kelamin --
                                </option>

                                <option value="L"
                                    <?= $jenis_kelamin == 'L' ? 'selected' : ''; ?>>
                                    Laki-laki
                                </option>

                                <option value="P"
                                    <?= $jenis_kelamin == 'P' ? 'selected' : ''; ?>>
                                    Perempuan
                                </option>

                            </select>
                        </div>

                        <!-- TANGGAL LAHIR -->
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">
                                Tanggal Lahir
                            </label>

                            <input
                                type="date"
                                name="tanggal_lahir"
                                id="tanggal_lahir"
                                class="form-control"
                                value="<?= htmlspecialchars($tanggal_lahir); ?>"
                                required>
                        </div>

                        <!-- ALAMAT -->
                        <div class="mb-3">
                            <label for="alamat" class="form-label">
                                Alamat
                            </label>

                            <textarea
                                name="alamat"
                                id="alamat"
                                class="form-control"
                                rows="3"
                                required><?= htmlspecialchars($alamat); ?></textarea>
                        </div>

                        <!-- STATUS AKTIF -->
                        <div class="mb-4">
                            <label class="form-label">
                                Status Aktif
                            </label>

                            <select
                                name="status_aktif"
                                class="form-select"
                                required>

                                <option value="1"
                                    <?= $status_aktif === '1' ? 'selected' : ''; ?>>
                                    Aktif
                                </option>

                                <option value="0"
                                    <?= $status_aktif === '0' ? 'selected' : ''; ?>>
                                    Tidak Aktif
                                </option>

                            </select>
                        </div>

                        <!-- TOMBOL -->
                        <button
                            type="submit"
                            name="simpan"
                            class="btn btn-primary">
                            Simpan
                        </button>

                        <a href="siswa.php" class="btn btn-secondary">
                            Kembali
                        </a>

                    </form>

                </div>
            </div>

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
