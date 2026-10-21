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

$error = "";

$kategori_id = "";
$kode = "";
$nama = "";
$poin = "";
$deskripsi = "";

/* AMBIL DATA KATEGORI */
$kategori = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_pelanggaran_kategori
     ORDER BY nama ASC"
);

if (!$kategori) {
    die("Gagal mengambil kategori: " . mysqli_error($koneksi));
}

/* PROSES SIMPAN DATA BARU */
if (isset($_POST['simpan'])) {
    $kategori_id = (int) ($_POST['pelanggaran_kategori_id'] ?? 0);
    $kode = trim($_POST['kode'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $poinInput = trim($_POST['poin'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    $poinValid = filter_var($poinInput, FILTER_VALIDATE_INT);

    if (
        $kategori_id <= 0 ||
        $kode === '' ||
        $nama === '' ||
        $poinInput === '' ||
        $poinValid === false ||
        $poinValid < 0
    ) {
        $error = "Lengkapi semua data wajib dengan benar.";
    } else {
        /* PASTIKAN KATEGORI ADA */
        $cekKategori = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_pelanggaran_kategori WHERE id = ?"
        );

        mysqli_stmt_bind_param($cekKategori, "i", $kategori_id);
        mysqli_stmt_execute($cekKategori);
        $hasilKategori = mysqli_stmt_get_result($cekKategori);
        $kategoriAda = mysqli_num_rows($hasilKategori) > 0;
        mysqli_stmt_close($cekKategori);

        if (!$kategoriAda) {
            $error = "Kategori pelanggaran tidak ditemukan.";
        } else {
            $poin = $poinValid;

            /* SIMPAN JENIS PELANGGARAN */
            $stmt = mysqli_prepare(
                $koneksi,
                "INSERT INTO t_pelanggaran
                (pelanggaran_kategori_id, kode, nama, poin, deskripsi)
                VALUES (?, ?, ?, ?, ?)"
            );

            if ($stmt) {
                mysqli_stmt_bind_param(
                    $stmt,
                    "issis",
                    $kategori_id,
                    $kode,
                    $nama,
                    $poin,
                    $deskripsi
                );

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_close($stmt);
                    header("Location: jenis_pelanggaran.php?pesan=tambah");
                    exit;
                } else {
                    if (mysqli_stmt_errno($stmt) == 1062) {
                        $error = "Kode pelanggaran sudah digunakan.";
                    } else {
                        $error = "Gagal menyimpan data: " .
                                 mysqli_stmt_error($stmt);
                    }
                }

                mysqli_stmt_close($stmt);
            } else {
                $error = "Gagal menyiapkan query: " .
                         mysqli_error($koneksi);
            }
        }
    }

    $poin = $poinInput;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jenis Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
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
                <a href="kelas.php" class="nav-link text-white">Kelola Kelas</a>
                <a href="tahun_ajaran.php" class="nav-link text-white">Tahun Ajaran</a>
                <a href="penempatan_siswa.php" class="nav-link text-white">Penempatan Siswa</a>
                <a href="wali_kelas.php" class="nav-link text-white">Wali Kelas</a>
                <a href="kategori_pelanggaran.php" class="nav-link text-white">Kategori Pelanggaran</a>
                <a href="jenis_pelanggaran.php" class="nav-link active">Jenis Pelanggaran</a>
                <a href="laporan.php" class="nav-link text-white">Laporan</a>

                <hr class="text-secondary">

                <a href="about_me.php" class="nav-link text-white">About Me</a>

                <a href="logout.php"
                   class="nav-link text-danger"
                   onclick="return confirm('Yakin ingin logout?')">
                    Logout
                </a>
            </nav>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2 class="mb-3">Tambah Jenis Pelanggaran</h2>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">Form Tambah Jenis Pelanggaran</h5>
                </div>

                <div class="card-body p-4">

                    <?php if ($error !== ''): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

                        <!-- KATEGORI -->
                        <div class="mb-3">
                            <label for="pelanggaran_kategori_id"
                                   class="form-label">
                                Kategori Pelanggaran
                            </label>

                            <select name="pelanggaran_kategori_id"
                                    id="pelanggaran_kategori_id"
                                    class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>

                                <?php while ($k = mysqli_fetch_assoc($kategori)): ?>
                                    <option value="<?= (int)$k['id'] ?>"
                                        <?= (string)$kategori_id === (string)$k['id']
                                            ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($k['nama']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <!-- KODE -->
                        <div class="mb-3">
                            <label for="kode" class="form-label">
                                Kode Pelanggaran
                            </label>

                            <input type="text"
                                   name="kode"
                                   id="kode"
                                   class="form-control"
                                   value="<?= htmlspecialchars($kode) ?>"
                                   required>
                        </div>

<!-- JENIS PELANGGARAN -->
<div class="mb-3">
    <label for="nama" class="form-label">
        Jenis Pelanggaran
    </label>

    <select name="nama" id="nama" class="form-select" required>
        <option value="">-- Pilih Jenis Pelanggaran --</option>
        <option value="Terlambat Datang">Terlambat Datang</option>
        <option value="Tidak Memakai Seragam">Tidak Memakai Seragam</option>
        <option value="Tidak Mengerjakan Tugas">Tidak Mengerjakan Tugas</option>
        <option value="Membolos">Membolos</option>
        <option value="Rambut Tidak Rapi">Rambut Tidak Rapi</option>
        <option value="Berkelahi">Berkelahi</option>
        <option value="Merokok">Merokok</option>
        <option value="Merusak Fasilitas Sekolah">Merusak Fasilitas Sekolah</option>
    </select>
</div>


                        <!-- POIN -->
                        <div class="mb-3">
                            <label for="poin" class="form-label">
                                Poin
                            </label>

                            <input type="number"
                                   name="poin"
                                   id="poin"
                                   class="form-control"
                                   min="0"
                                   value="<?= htmlspecialchars((string)$poin) ?>"
                                   required>
                        </div>

                        <!-- DESKRIPSI -->
                        <div class="mb-4">
                            <label for="deskripsi" class="form-label">
                                Deskripsi
                            </label>

                            <textarea name="deskripsi"
                                      id="deskripsi"
                                      class="form-control"
                                      rows="4"><?= htmlspecialchars($deskripsi) ?></textarea>
                        </div>

                        <!-- TOMBOL -->
                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit"
                                    name="simpan"
                                    class="btn btn-primary">
                                Simpan
                            </button>

                            <a href="jenis_pelanggaran.php"
                               class="btn btn-secondary">
                                Kembali
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
