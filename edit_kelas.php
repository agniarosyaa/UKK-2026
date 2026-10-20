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

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("ID kelas tidak valid.");
}

/* AMBIL DATA KELAS */
$stmt = mysqli_prepare($koneksi, "SELECT * FROM t_kelas WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$data) {
    die("Data kelas tidak ditemukan.");
}

$pesan_error = "";

/* UPDATE DATA */
if (isset($_POST['update'])) {
    $nama = trim($_POST['nama'] ?? '');
    $tingkat = trim($_POST['tingkat'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $status_aktif = (int) ($_POST['status_aktif'] ?? 0);

    if (
        $nama == '' || $tingkat == '' || $jurusan == '' ||
        !in_array($tingkat, ['10', '11', '12'], true) ||
        !in_array($status_aktif, [0, 1], true)
    ) {
        $pesan_error = "Semua data wajib diisi dengan benar.";
    } else {
        $update = mysqli_prepare(
            $koneksi,
            "UPDATE t_kelas
             SET nama = ?, tingkat = ?, jurusan = ?,
                 status_aktif = ?, update_at = NOW()
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $update,
            "sssii",
            $nama,
            $tingkat,
            $jurusan,
            $status_aktif,
            $id
        );

        if (mysqli_stmt_execute($update)) {
            mysqli_stmt_close($update);
            header("Location: kelas.php?pesan=edit");
            exit;
        } else {
            $pesan_error = "Gagal mengubah data kelas.";
            mysqli_stmt_close($update);
        }
    }

    $data['nama'] = $nama;
    $data['tingkat'] = $tingkat;
    $data['jurusan'] = $jurusan;
    $data['status_aktif'] = $status_aktif;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Kelas</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">

            <h4 class="text-white mb-4">Sistem Pelanggaran</h4>

            <nav class="nav nav-pills flex-column gap-2">

                <a href="dashboard.php" class="nav-link text-white">
                    Dashboard
                </a>

                <a href="siswa.php" class="nav-link text-white">
                    Kelola Siswa
                </a>

                <a href="guru.php" class="nav-link text-white">
                    Kelola Guru
                </a>

                <a href="kelas.php" class="nav-link active">
                    Kelola Kelas
                </a>

                <a href="tahun_ajaran.php" class="nav-link text-white">
                    Tahun Ajaran
                </a>

                <a href="penempatan_siswa.php" class="nav-link text-white">
                    Penempatan Siswa
                </a>

                <a href="wali_kelas.php" class="nav-link text-white">
                    Wali Kelas
                </a>

                <a href="kategori_pelanggaran.php" class="nav-link text-white">
                    Kategori Pelanggaran
                </a>

                <a href="jenis_pelanggaran.php" class="nav-link text-white">
                    Jenis Pelanggaran
                </a>

                <a href="laporan.php" class="nav-link text-white">
                    Laporan
                </a>

                <hr class="text-secondary">

                <a href="about_me.php" class="nav-link text-white">
                    About Me
                </a>

                <a href="logout.php"
                   class="nav-link text-danger"
                   onclick="return confirm('Yakin ingin logout?')">
                    Logout
                </a>

            </nav>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2 class="mb-4">Edit Kelas</h2>

            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white">
                    Form Edit Kelas
                </div>

                <div class="card-body">

                    <?php if ($pesan_error != '') { ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($pesan_error); ?>
                        </div>
                    <?php } ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label for="nama" class="form-label">
                                Nama Kelas
                            </label>

                            <input
                                type="text"
                                name="nama"
                                id="nama"
                                class="form-control"
                                value="<?= htmlspecialchars($data['nama'] ?? ''); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="tingkat" class="form-label">
                                Tingkat
                            </label>

                            <select
                                name="tingkat"
                                id="tingkat"
                                class="form-select"
                                required>

                                <option value="">-- Pilih Tingkat --</option>

                                <option value="10"
                                    <?= (string) ($data['tingkat'] ?? '') === '10' ? 'selected' : ''; ?>>
                                    10
                                </option>

                                <option value="11"
                                    <?= (string) ($data['tingkat'] ?? '') === '11' ? 'selected' : ''; ?>>
                                    11
                                </option>

                                <option value="12"
                                    <?= (string) ($data['tingkat'] ?? '') === '12' ? 'selected' : ''; ?>>
                                    12
                                </option>

                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="jurusan" class="form-label">
                                Jurusan
                            </label>

                            <input
                                type="text"
                                name="jurusan"
                                id="jurusan"
                                class="form-control"
                                value="<?= htmlspecialchars($data['jurusan'] ?? ''); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="status_aktif" class="form-label">
                                Status
                            </label>

                            <select
                                name="status_aktif"
                                id="status_aktif"
                                class="form-select"
                                required>

                                <option value="1"
                                    <?= ($data['status_aktif'] ?? 0) == 1 ? 'selected' : ''; ?>>
                                    Aktif
                                </option>

                                <option value="0"
                                    <?= ($data['status_aktif'] ?? 0) == 0 ? 'selected' : ''; ?>>
                                    Tidak Aktif
                                </option>

                            </select>
                        </div>

                        <button
                            type="submit"
                            name="update"
                            class="btn btn-primary">
                            Simpan Perubahan
                        </button>

                        <a href="kelas.php" class="btn btn-secondary">
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
