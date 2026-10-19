
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

$nama = "";
$tingkat = "";
$jurusan = "";

if (isset($_POST['tambah'])) {
    $nama = trim($_POST['nama'] ?? '');
    $tingkat = trim($_POST['tingkat'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');

    if ($nama == "" || $tingkat == "" || $jurusan == "") {
        $error = "Semua data wajib diisi.";
    } else {
        $query = "INSERT INTO t_kelas
                  (nama, tingkat, jurusan, status_aktif, created_at, update_at)
                  VALUES (?, ?, ?, 1, NOW(), NOW())";

        $stmt = mysqli_prepare($koneksi, $query);

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "sss",
                $nama,
                $tingkat,
                $jurusan
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: kelas.php?pesan=tambah");
                exit;
            } else {
                $error = "Gagal menambahkan kelas.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $error = "Query gagal diproses.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kelas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">

            <h4 class="text-white mb-4">Sistem Pelanggaran</h4>

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
                    <a href="kelas.php" class="nav-link active">
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
                    <a href="logout.php" class="nav-link text-danger"
                       onclick="return confirm('Yakin ingin logout?')">
                        Logout
                    </a>
                </li>

            </ul>
        </aside>

        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2 class="mb-4">Tambah Kelas</h2>

            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white">
                    Form Tambah Kelas
                </div>

                <div class="card-body">

                    <?php if ($error != "") { ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error); ?>
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
                                placeholder="Contoh: XII RPL 2"
                                value="<?= htmlspecialchars($nama); ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="tingkat" class="form-label">
                                Tingkat
                            </label>

                            <select
                                name="tingkat"
                                id="tingkat"
                                class="form-select"
                                required
                            >
                                <option value="">Pilih tingkat</option>

                                <option value="10"
                                    <?= $tingkat == "10" ? "selected" : ""; ?>>
                                    10
                                </option>

                                <option value="11"
                                    <?= $tingkat == "11" ? "selected" : ""; ?>>
                                    11
                                </option>

                                <option value="12"
                                    <?= $tingkat == "12" ? "selected" : ""; ?>>
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
                                placeholder="Contoh: RPL"
                                value="<?= htmlspecialchars($jurusan); ?>"
                                required
                            >
                        </div>

                        <button type="submit" name="tambah"
                                class="btn btn-primary">
                            Simpan
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
