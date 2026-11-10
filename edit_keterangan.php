
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$role = strtolower(trim($_SESSION['role'] ?? ''));

if (!in_array($role, ['admin', 'guru'], true)) {
    exit("Anda tidak memiliki akses.");
}

include "config/koneksi.php";

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    header("Location: riwayat.php");
    exit;
}

$pesan_error = "";

/* ==========================================
   PROSES SIMPAN KETERANGAN
========================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $keterangan = trim($_POST['keterangan'] ?? '');

    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE t_pelanggaran_siswa
         SET keterangan = ?
         WHERE id = ?"
    );

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $keterangan, $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);

            header("Location: riwayat.php?updated=1");
            exit;
        } else {
            $pesan_error = "Gagal menyimpan keterangan.";
        }

        mysqli_stmt_close($stmt);
    } else {
        $pesan_error = "Query gagal: " . mysqli_error($koneksi);
    }
}

/* ==========================================
   AMBIL DATA PELANGGARAN
========================================== */
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT
        ps.id,
        ps.nama_siswa,
        COALESCE(
            NULLIF(NULLIF(TRIM(ps.nama_kelas), ''), '0'),
            NULLIF(k.nama, ''),
            '-'
        ) AS nama_kelas,
        COALESCE(
            NULLIF(NULLIF(TRIM(p.nama), ''), '0'),
            NULLIF(NULLIF(TRIM(ps.nama_pelanggaran), ''), '0'),
            '-'
        ) AS nama_pelanggaran,
        ps.tanggal,
        ps.poin,
        ps.tindakan,
        ps.status,
        ps.keterangan
     FROM t_pelanggaran_siswa ps
     LEFT JOIN t_pelanggaran p
        ON p.id = ps.pelanggaran_id
     LEFT JOIN t_kelas k
        ON k.id = ps.kelas_id
     WHERE ps.id = ?
     LIMIT 1"
);

if (!$stmt) {
    die("Query gagal: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$data) {
    exit("Data riwayat pelanggaran tidak ditemukan.");
}

/* Nilai textarea */
$keterangan_form = $_POST['keterangan'] ?? ($data['keterangan'] ?? '');
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Keterangan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-body-tertiary">

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 bg-dark text-white p-3 position-fixed top-0 bottom-0 start-0 overflow-auto">

            <h5 class="text-white mb-4">
                Sistem Pelanggaran
            </h5>

            <nav class="nav nav-pills flex-column gap-2">

                <a href="dashboard.php" class="nav-link text-white">
                    Dashboard
                </a>

                <a href="catat_pelanggaran.php" class="nav-link text-white">
                    Catat Pelanggaran
                </a>

                <a href="tindakan.php" class="nav-link text-white">
                    Tindakan
                </a>

                <a href="riwayat.php" class="nav-link active">
                    Riwayat
                </a>

                <a href="rekap_poin.php" class="nav-link text-white">
                    Rekap Poin
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
        <main class="col-md-9 col-lg-10 p-4 offset-md-3 offset-lg-2">

            <h2 class="mb-1">Edit Keterangan</h2>

            <p class="text-secondary mb-4">
                Perbarui keterangan pelanggaran siswa.
            </p>

            <?php if ($pesan_error !== ''): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($pesan_error); ?>
                </div>
            <?php endif; ?>

            <!-- FORM EDIT -->
            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white">
                    Form Edit Keterangan Pelanggaran
                </div>

                <div class="card-body">

                    <form method="POST"
                          action="edit_keterangan.php?id=<?= $id; ?>">

                        <input type="hidden"
                               name="id"
                               value="<?= $id; ?>">

                        <div class="mb-3">
                            <label class="form-label">Nama Siswa</label>

                            <input type="text"
                                   class="form-control"
                                   value="<?= htmlspecialchars($data['nama_siswa'] ?? '-'); ?>"
                                   readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kelas</label>

                            <input type="text"
                                   class="form-control"
                                   value="<?= htmlspecialchars($data['nama_kelas'] ?? '-'); ?>"
                                   readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Pelanggaran</label>

                            <input type="text"
                                   class="form-control"
                                   value="<?= htmlspecialchars($data['nama_pelanggaran'] ?? '-'); ?>"
                                   readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>

                            <input type="text"
                                   class="form-control"
                                   value="<?= htmlspecialchars($data['tanggal'] ?? '-'); ?>"
                                   readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Poin</label>

                            <input type="text"
                                   class="form-control"
                                   value="<?= (int)($data['poin'] ?? 0); ?> poin"
                                   readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tindakan</label>

                            <textarea class="form-control"
                                      rows="3"
                                      readonly><?= htmlspecialchars($data['tindakan'] ?? '-'); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>

                            <input type="text"
                                   class="form-control"
                                   value="<?= htmlspecialchars($data['status'] ?? '-'); ?>"
                                   readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>

                            <textarea name="keterangan"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Masukkan keterangan pelanggaran..."
                                      required><?= htmlspecialchars($keterangan_form); ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Simpan Perubahan
                        </button>

                        <a href="riwayat.php" class="btn btn-secondary">
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
