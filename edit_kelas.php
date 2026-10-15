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

if (!$data) {
    die("Data kelas tidak ditemukan.");
}

/* UPDATE DATA */
if (isset($_POST['update'])) {
    $nama = trim($_POST['nama']);
    $tingkat = trim($_POST['tingkat']);
    $jurusan = trim($_POST['jurusan']);
    $status_aktif = (int) $_POST['status_aktif'];

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
        header("Location: kelas.php?pesan=edit");
        exit;
    } else {
        $pesan_error = "Gagal mengubah data kelas.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kelas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0">Edit Kelas</h4>
                </div>

                <div class="card-body p-4">

                    <?php if (isset($pesan_error)) { ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($pesan_error); ?>
                        </div>
                    <?php } ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Kelas</label>
                            <input type="text" id="nama" name="nama"
                                   class="form-control"
                                   value="<?= htmlspecialchars($data['nama'] ?? ''); ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="tingkat" class="form-label">Tingkat</label>
                            <select id="tingkat" name="tingkat"
                                    class="form-select" required>
                                <option value="10"
                                    <?= (string) $data['tingkat'] === '10' ? 'selected' : ''; ?>>
                                    10
                                </option>
                                <option value="11"
                                    <?= (string) $data['tingkat'] === '11' ? 'selected' : ''; ?>>
                                    11
                                </option>
                                <option value="12"
                                    <?= (string) $data['tingkat'] === '12' ? 'selected' : ''; ?>>
                                    12
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="jurusan" class="form-label">Jurusan</label>
                            <input type="text" id="jurusan" name="jurusan"
                                   class="form-control"
                                   value="<?= htmlspecialchars($data['jurusan'] ?? ''); ?>"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label for="status_aktif" class="form-label">Status</label>
                            <select id="status_aktif" name="status_aktif"
                                    class="form-select" required>
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

                        <div class="d-flex gap-2">
                            <button type="submit" name="update"
                                    class="btn btn-primary">
                                Simpan Perubahan
                            </button>

                            <a href="kelas.php" class="btn btn-secondary">
                                Kembali
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
