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
$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    header("Location: jenis_pelanggaran.php");
    exit;
}

// AMBIL DATA JENIS PELANGGARAN
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_pelanggaran WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$row) {
    header("Location: jenis_pelanggaran.php");
    exit;
}

// AMBIL DATA KATEGORI
$kategori = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_pelanggaran_kategori ORDER BY nama ASC"
);

if (!$kategori) {
    die("Gagal mengambil data kategori pelanggaran.");
}

// PROSES EDIT
if (isset($_POST['simpan'])) {
    $kategori_id = (int) ($_POST['pelanggaran_kategori_id'] ?? 0);
    $kode = trim($_POST['kode'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $poin = filter_var($_POST['poin'] ?? null, FILTER_VALIDATE_INT);
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($kategori_id <= 0 || $kode === '' || $nama === '' ||
        $poin === false || $poin === null || $poin < 0) {
        $error = "Lengkapi data dengan benar.";
    } else {
        $stmtUpdate = mysqli_prepare(
            $koneksi,
            "UPDATE t_pelanggaran
             SET pelanggaran_kategori_id = ?,
                 kode = ?,
                 nama = ?,
                 poin = ?,
                 deskripsi = ?
             WHERE id = ?"
        );

        if ($stmtUpdate) {
            mysqli_stmt_bind_param(
                $stmtUpdate,
                "issisi",
                $kategori_id,
                $kode,
                $nama,
                $poin,
                $deskripsi,
                $id
            );

            if (mysqli_stmt_execute($stmtUpdate)) {
                mysqli_stmt_close($stmtUpdate);
                header("Location: jenis_pelanggaran.php?pesan=edit");
                exit;
            } else {
                $error = "Gagal mengubah data jenis pelanggaran.";
            }

            mysqli_stmt_close($stmtUpdate);
        } else {
            $error = "Terjadi kesalahan saat menyiapkan perubahan data.";
        }
    }

    // PERTAHANKAN ISIAN JIKA ADA ERROR
    $row['pelanggaran_kategori_id'] = $kategori_id;
    $row['kode'] = $kode;
    $row['nama'] = $nama;
    $row['poin'] = $_POST['poin'] ?? '';
    $row['deskripsi'] = $deskripsi;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jenis Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
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

            <h2 class="mb-3">Edit Jenis Pelanggaran</h2>

        

            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">Form Edit Jenis Pelanggaran</h5>
                </div>

                <div class="card-body p-4">

                    <?php if ($error !== '') { ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error); ?>
                        </div>
                    <?php } ?>

                    <form method="POST" action="">

                        <input type="hidden" name="id" value="<?= $id; ?>">

                        <div class="mb-3">
                            <label for="pelanggaran_kategori_id" class="form-label">
                                Kategori Pelanggaran
                            </label>

                            <select
                                name="pelanggaran_kategori_id"
                                id="pelanggaran_kategori_id"
                                class="form-select"
                                required
                            >
                                <option value="">-- Pilih Kategori --</option>

                                <?php
                                mysqli_data_seek($kategori, 0);
                                while ($k = mysqli_fetch_assoc($kategori)) {
                                ?>
                                    <option
                                        value="<?= (int) $k['id']; ?>"
                                        <?= (int) ($row['pelanggaran_kategori_id'] ?? 0) === (int) $k['id'] ? 'selected' : ''; ?>
                                    >
                                        <?= htmlspecialchars($k['nama']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="kode" class="form-label">
                                Kode Pelanggaran
                            </label>

                            <input
                                type="text"
                                name="kode"
                                id="kode"
                                class="form-control"
                                value="<?= htmlspecialchars($row['kode'] ?? ''); ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label">
                                Nama Pelanggaran
                            </label>

                            <input
                                type="text"
                                name="nama"
                                id="nama"
                                class="form-control"
                                value="<?= htmlspecialchars($row['nama'] ?? ''); ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="poin" class="form-label">
                                Poin
                            </label>

                            <input
                                type="number"
                                name="poin"
                                id="poin"
                                class="form-control"
                                min="0"
                                value="<?= htmlspecialchars((string) ($row['poin'] ?? 0)); ?>"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label for="deskripsi" class="form-label">
                                Deskripsi
                            </label>

                            <textarea
                                name="deskripsi"
                                id="deskripsi"
                                class="form-control"
                                rows="4"
                            ><?= htmlspecialchars($row['deskripsi'] ?? ''); ?></textarea>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <button
                                type="submit"
                                name="simpan"
                                class="btn btn-primary"
                            >
                                Simpan Perubahan
                            </button>

                            <a href="jenis_pelanggaran.php" class="btn btn-secondary">
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
