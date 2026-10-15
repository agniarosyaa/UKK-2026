<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (strtolower($_SESSION['role'] ?? '') != 'guru') {
    echo "Anda tidak memiliki akses";
    exit;
}

include "config/koneksi.php";

$pesan = "";
$jenis_pesan = "success";

/* UPDATE TINDAKAN */
if (isset($_POST['simpan'])) {
    $id = (int) ($_POST['id'] ?? 0);
    $tindakan = trim($_POST['tindakan'] ?? '');
    $status = trim($_POST['status'] ?? '');

    $status_diizinkan = ['Diproses', 'Selesai'];

    if ($id <= 0 || $tindakan == '') {
        $pesan = "Data tindakan harus diisi.";
        $jenis_pesan = "danger";
    } elseif (!in_array($status, $status_diizinkan, true)) {
        $pesan = "Status tidak valid.";
        $jenis_pesan = "danger";
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE t_pelanggaran_siswa
             SET tindakan = ?, status = ?, update_at = NOW()
             WHERE id = ?"
        );

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "ssi",
                $tindakan,
                $status,
                $id
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: tindakan.php?berhasil=1");
                exit;
            } else {
                $pesan = "Gagal memperbarui tindakan.";
                $jenis_pesan = "danger";
            }

            mysqli_stmt_close($stmt);
        } else {
            $pesan = "Query gagal: " . mysqli_error($koneksi);
            $jenis_pesan = "danger";
        }
    }
}

if (isset($_GET['berhasil'])) {
    $pesan = "Tindakan berhasil diperbarui.";
    $jenis_pesan = "success";
}

/* AMBIL DATA PELANGGARAN */
$data = mysqli_query(
    $koneksi,
    "SELECT id, nama_siswa, nama_kelas, nama_pelanggaran,
            tanggal, keterangan, poin, tindakan, status
     FROM t_pelanggaran_siswa
     ORDER BY tanggal DESC, id DESC"
);

if (!$data) {
    die("Gagal mengambil data: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tindakan Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-body-tertiary">

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR GURU -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">
            <h5 class="text-white mb-4">Sistem Pelanggaran</h5>

            <nav class="nav nav-pills flex-column gap-2">

                <a href="dashboard.php" class="nav-link text-white">
                    Dashboard
                </a>

                <a href="catat_pelanggaran.php"
                   class="nav-link text-white">
                    Catat Pelanggaran
                </a>

                <a href="tindakan.php" class="nav-link active">
                    Tindakan
                </a>

                <a href="riwayat.php" class="nav-link text-white">
                    Riwayat
                </a>

                <a href="rekap_poin.php" class="nav-link text-white">
                    Rekap Poin
                </a>

                <hr class="text-secondary">

                <a href="about_me.php" class="nav-link text-white">
                    About Me
                </a>

                <a href="logout.php" class="nav-link text-danger">
                    Logout
                </a>

            </nav>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2 class="mb-1">Tindakan Pelanggaran</h2>
            <p class="text-secondary mb-4">
                Kelola tindakan dan status pelanggaran siswa.
            </p>

            <?php if ($pesan != ''): ?>
                <div class="alert alert-<?= $jenis_pesan ?> alert-dismissible fade show">
                    <?= htmlspecialchars($pesan) ?>

                    <button type="button" class="btn-close"
                            data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Pelanggaran</th>
                                    <th>Tanggal</th>
                                    <th>Poin</th>
                                    <th>Tindakan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php if (mysqli_num_rows($data) > 0): ?>
                                <?php $no = 1; ?>

                                <?php while ($row = mysqli_fetch_assoc($data)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>

                                        <td>
                                            <?= htmlspecialchars($row['nama_siswa'] ?? '') ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($row['nama_kelas'] ?? '') ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($row['nama_pelanggaran'] ?? '') ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($row['tanggal'] ?? '') ?>
                                        </td>

                                        <td>
                                            <span class="badge bg-primary">
                                                <?= (int) $row['poin'] ?> poin
                                            </span>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($row['tindakan'] ?? '') ?: '-' ?>
                                        </td>

                                        <td>
                                            <?php
                                            $status = $row['status'] ?? '';

                                            if (strtolower($status) == 'selesai') {
                                                echo '<span class="badge bg-success">Selesai</span>';
                                            } else {
                                                echo '<span class="badge bg-warning text-dark">'
                                                    . htmlspecialchars($status ?: 'Diproses')
                                                    . '</span>';
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <button type="button"
                                                    class="btn btn-primary btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#edit<?= (int) $row['id'] ?>">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- MODAL EDIT TINDAKAN -->
                                    <div class="modal fade"
                                         id="edit<?= (int) $row['id'] ?>"
                                         tabindex="-1"
                                         aria-hidden="true">

                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <form method="POST">

                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            Edit Tindakan Pelanggaran
                                                        </h5>

                                                        <button type="button"
                                                                class="btn-close"
                                                                data-bs-dismiss="modal">
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">

                                                        <input type="hidden"
                                                               name="id"
                                                               value="<?= (int) $row['id'] ?>">

                                                        <div class="mb-3">
                                                            <label class="form-label">
                                                                Nama Siswa
                                                            </label>

                                                            <input type="text"
                                                                   class="form-control"
                                                                   value="<?= htmlspecialchars($row['nama_siswa'] ?? '') ?>"
                                                                   readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">
                                                                Pelanggaran
                                                            </label>

                                                            <input type="text"
                                                                   class="form-control"
                                                                   value="<?= htmlspecialchars($row['nama_pelanggaran'] ?? '') ?>"
                                                                   readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">
                                                                Tindakan
                                                            </label>

                                                            <textarea name="tindakan"
                                                                      class="form-control"
                                                                      rows="3"
                                                                      required><?= htmlspecialchars($row['tindakan'] ?? '') ?></textarea>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">
                                                                Status
                                                            </label>

                                                            <select name="status"
                                                                    class="form-select"
                                                                    required>
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

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button"
                                                                class="btn btn-secondary"
                                                                data-bs-dismiss="modal">
                                                            Batal
                                                        </button>

                                                        <button type="submit"
                                                                name="simpan"
                                                                class="btn btn-primary">
                                                            Simpan Perubahan
                                                        </button>
                                                    </div>

                                                </form>

                                            </div>
                                        </div>
                                    </div>

                                <?php endwhile; ?>

                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center text-secondary py-4">
                                        Belum ada data pelanggaran siswa.
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
