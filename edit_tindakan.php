
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (strtolower($_SESSION['role'] ?? '') != 'guru') {
    exit("Anda tidak memiliki akses");
}

include "config/koneksi.php";

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: tindakan.php");
    exit;
}

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id, nama_siswa, nama_kelas, nama_pelanggaran,
            tanggal, poin, tindakan, status
     FROM t_pelanggaran_siswa WHERE id = ?"
);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$row) {
    exit("Data tindakan tidak ditemukan.");
}

$error = "";

if (isset($_POST['simpan'])) {
    $tindakan = trim($_POST['tindakan'] ?? '');
    $status = trim($_POST['status'] ?? '');

    if ($tindakan === '') {
        $error = "Tindakan wajib diisi.";
    } elseif (!in_array($status, ['Diproses', 'Selesai'], true)) {
        $error = "Status tidak valid.";
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE t_pelanggaran_siswa
             SET tindakan = ?, status = ?, update_at = NOW()
             WHERE id = ?"
        );

        if (!$stmt) {
            $error = "Gagal menyiapkan query: " . mysqli_error($koneksi);
        } else {
            mysqli_stmt_bind_param($stmt, "ssi", $tindakan, $status, $id);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: tindakan.php?berhasil=1");
                exit;
            } else {
                $error = "Gagal memperbarui tindakan: " . mysqli_stmt_error($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }

    // Tampilkan kembali input yang sudah diisi jika ada kesalahan.
    $row['tindakan'] = $tindakan;
    $row['status'] = $status;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Tindakan Pelanggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary">

<div class="container-fluid">
<div class="row">

    <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">
        <h5 class="text-white mb-4">Sistem Pelanggaran</h5>
        <nav class="nav nav-pills flex-column gap-1">
            <a href="dashboard.php" class="nav-link text-white">Dashboard</a>
            <a href="catat_pelanggaran.php" class="nav-link text-white">Catat Pelanggaran</a>
            <a href="tindakan.php" class="nav-link active">Tindakan</a>
            <a href="riwayat.php" class="nav-link text-white">Riwayat</a>
            <a href="rekap_poin.php" class="nav-link text-white">Rekap Poin</a>
            <hr class="text-secondary">
            <a href="about_me.php" class="nav-link text-white">About Me</a>
            <a href="logout.php" class="nav-link text-danger"
               onclick="return confirm('Yakin ingin logout?')">Logout</a>
        </nav>
    </aside>

    <main class="col-md-9 col-lg-10 p-4">
        <h1 class="mb-2">Edit Tindakan</h1>
        <p class="text-secondary mb-4">Perbarui tindakan dan status pelanggaran siswa.</p>

        <?php if ($error !== ''): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white py-3">
                Form Edit Tindakan Pelanggaran
            </div>

            <div class="card-body p-4">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Siswa</label>
                        <input type="text" class="form-control"
                               value="<?= htmlspecialchars($row['nama_siswa'] ?? '') ?>"
                               readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <input type="text" class="form-control"
                               value="<?= htmlspecialchars($row['nama_kelas'] ?? '') ?>"
                               readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Pelanggaran</label>
                        <input type="text" class="form-control"
                               value="<?= htmlspecialchars($row['nama_pelanggaran'] ?? '') ?>"
                               readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="text" class="form-control"
                               value="<?= htmlspecialchars($row['tanggal'] ?? '') ?>"
                               readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Poin Pelanggaran</label>
                        <input type="text" class="form-control"
                               value="<?= (int)($row['poin'] ?? 0) ?> poin"
                               readonly>
                    </div>

                    <div class="mb-3">
                        <label for="tindakan" class="form-label">Tindakan</label>
                        <textarea name="tindakan" id="tindakan"
                                  class="form-control" rows="3"
                                  required><?= htmlspecialchars($row['tindakan'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="Diproses"
                                <?= ($row['status'] ?? '') == 'Diproses' ? 'selected' : '' ?>>
                                Diproses
                            </option>
                            <option value="Selesai"
                                <?= ($row['status'] ?? '') == 'Selesai' ? 'selected' : '' ?>>
                                Selesai
                            </option>
                        </select>
                    </div>

                    <button type="submit" name="simpan" class="btn btn-primary">
                        Simpan Perubahan
                    </button>
                    <a href="tindakan.php" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </main>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
