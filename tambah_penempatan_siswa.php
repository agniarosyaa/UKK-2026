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

$siswa = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_siswa ORDER BY nama ASC"
);

$kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_kelas ORDER BY nama ASC"
);

/* AMBIL TAHUN AJARAN YANG AKTIF SAJA */
$tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama 
     FROM t_tahun_ajaran 
     WHERE status_aktif = 1 
     ORDER BY id DESC"
);

if (!$siswa || !$kelas || !$tahun) {
    die("Gagal mengambil data pilihan: " . mysqli_error($koneksi));
}

/* SIMPAN PENEMPATAN SISWA */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $siswa_id = (int) ($_POST['siswa_id'] ?? 0);
    $kelas_id = (int) ($_POST['kelas_id'] ?? 0);
    $tahun_ajaran_id = (int) ($_POST['tahun_ajaran_id'] ?? 0);

    if ($siswa_id <= 0 || $kelas_id <= 0 || $tahun_ajaran_id <= 0) {

        $error = "Semua pilihan wajib diisi.";

    } else {

        /* CEK TAHUN AJARAN HARUS AKTIF */
        $cekTahun = mysqli_prepare(
            $koneksi,
            "SELECT id 
             FROM t_tahun_ajaran 
             WHERE id = ? AND status_aktif = 1"
        );

        mysqli_stmt_bind_param(
            $cekTahun,
            "i",
            $tahun_ajaran_id
        );

        mysqli_stmt_execute($cekTahun);

        $hasilTahun = mysqli_stmt_get_result($cekTahun);

        if (mysqli_num_rows($hasilTahun) == 0) {

            $error = "Tahun ajaran yang dipilih tidak aktif.";

            mysqli_stmt_close($cekTahun);

        } else {

            mysqli_stmt_close($cekTahun);

            $stmt = mysqli_prepare(
                $koneksi,
                "INSERT INTO t_kelas_siswa
                 (siswa_id, kelas_id, tahun_ajaran_id)
                 VALUES (?, ?, ?)"
            );

            if (!$stmt) {

                $error = "Gagal menyiapkan penyimpanan data.";

            } else {

                mysqli_stmt_bind_param(
                    $stmt,
                    "iii",
                    $siswa_id,
                    $kelas_id,
                    $tahun_ajaran_id
                );

                if (mysqli_stmt_execute($stmt)) {

                    mysqli_stmt_close($stmt);

                    $_SESSION['notifikasi'] = [
                        'tipe' => 'success',
                        'pesan' => 'Data penempatan siswa berhasil ditambahkan!'
                    ];

                    header("Location: penempatan_siswa.php");
                    exit;

                } else {

                    $error = "Gagal menyimpan data. Pastikan siswa belum ditempatkan pada kombinasi kelas dan tahun ajaran yang sama.";

                    mysqli_stmt_close($stmt);
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Penempatan Siswa</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
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
                    <a href="penempatan_siswa.php" class="nav-link active">
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

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2 class="mb-3">Tambah Penempatan Siswa</h2>

            <!-- PESAN ERROR -->
            <?php if ($error != "") { ?>

                <div class="alert alert-danger">
                    <?= htmlspecialchars($error); ?>
                </div>

            <?php } ?>

            <!-- FORM TAMBAH -->
            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">Form Tambah Penempatan Siswa</h5>
                </div>

                <div class="card-body p-4">

                    <form method="POST">

                        <!-- NAMA SISWA -->
                        <div class="mb-3">

                            <label for="siswa_id" class="form-label">
                                Nama Siswa
                            </label>

                            <select
                                name="siswa_id"
                                id="siswa_id"
                                class="form-select"
                                required>

                                <option value="">
                                    -- Pilih Siswa --
                                </option>

                                <?php while ($row = mysqli_fetch_assoc($siswa)) { ?>

                                    <option
                                        value="<?= (int) $row['id']; ?>"
                                        <?= (($_POST['siswa_id'] ?? '') == $row['id']) ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($row['nama']); ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <!-- KELAS -->
                        <div class="mb-3">

                            <label for="kelas_id" class="form-label">
                                Kelas
                            </label>

                            <select
                                name="kelas_id"
                                id="kelas_id"
                                class="form-select"
                                required>

                                <option value="">
                                    -- Pilih Kelas --
                                </option>

                                <?php while ($row = mysqli_fetch_assoc($kelas)) { ?>

                                    <option
                                        value="<?= (int) $row['id']; ?>"
                                        <?= (($_POST['kelas_id'] ?? '') == $row['id']) ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($row['nama']); ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <!-- TAHUN AJARAN -->
                        <div class="mb-4">

                            <label for="tahun_ajaran_id" class="form-label">
                                Tahun Ajaran
                            </label>

                            <select
                                name="tahun_ajaran_id"
                                id="tahun_ajaran_id"
                                class="form-select"
                                required>

                                <?php while ($row = mysqli_fetch_assoc($tahun)) { ?>

                                    <option
                                        value="<?= (int) $row['id']; ?>"
                                        <?= (
                                            isset($_POST['tahun_ajaran_id'])
                                                ? ((int)$_POST['tahun_ajaran_id'] == (int)$row['id'])
                                                : true
                                        ) ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($row['nama']); ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <!-- TOMBOL -->
                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary">
                                Simpan
                            </button>

                            <a
                                href="penempatan_siswa.php"
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>