
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "config/koneksi.php";

$role = strtolower($_SESSION['role'] ?? '');

if (!in_array($role, ['admin', 'guru'])) {
    exit("Anda tidak memiliki akses");
}

$error = '';

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/* =====================================
   AMBIL TAHUN AJARAN AKTIF
===================================== */

$tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_tahun_ajaran
     WHERE status_aktif = 1
     LIMIT 1"
);

$tahunAktif = mysqli_fetch_assoc($tahun);
$tahun_id = (int)($tahunAktif['id'] ?? 0);


/* =====================================
   AMBIL DATA KELAS SESUAI TAHUN AJARAN
===================================== */

$daftarKelas = [];

if ($tahun_id) {

    $queryKelas = mysqli_prepare($koneksi, "
        SELECT DISTINCT
            k.id,
            k.nama
        FROM t_kelas k
        INNER JOIN t_kelas_siswa ks
            ON ks.kelas_id = k.id
        WHERE ks.tahun_ajaran_id = ?
          AND ks.status_aktif = 1
          AND k.status_aktif = 1
        ORDER BY k.nama ASC
    ");

    mysqli_stmt_bind_param($queryKelas, "i", $tahun_id);
    mysqli_stmt_execute($queryKelas);

    $hasilKelas = mysqli_stmt_get_result($queryKelas);

    while ($row = mysqli_fetch_assoc($hasilKelas)) {
        $daftarKelas[] = $row;
    }
}


/* =====================================
   AMBIL DATA SISWA BESERTA KELAS
===================================== */

$daftarSiswa = [];

if ($tahun_id) {

    $querySiswa = mysqli_prepare($koneksi, "
        SELECT DISTINCT
            s.id,
            s.nama,
            s.nis,
            ks.kelas_id
        FROM t_siswa s
        INNER JOIN t_kelas_siswa ks
            ON ks.siswa_id = s.id
        INNER JOIN t_kelas k
            ON k.id = ks.kelas_id
        WHERE ks.tahun_ajaran_id = ?
          AND ks.status_aktif = 1
          AND s.status_aktif = 1
          AND k.status_aktif = 1
        ORDER BY s.nama ASC
    ");

    mysqli_stmt_bind_param($querySiswa, "i", $tahun_id);
    mysqli_stmt_execute($querySiswa);

    $hasilSiswa = mysqli_stmt_get_result($querySiswa);

    while ($row = mysqli_fetch_assoc($hasilSiswa)) {
        $daftarSiswa[] = $row;
    }
}


/* =====================================
   AMBIL DATA PELANGGARAN
===================================== */

$daftarPelanggaran = mysqli_query($koneksi, "
    SELECT id, nama, poin
    FROM t_pelanggaran
    WHERE status_aktif = 1
    ORDER BY nama ASC
");


/* =====================================
   AMBIL DATA GURU
===================================== */

$daftarGuru = mysqli_query($koneksi, "
    SELECT id, nama
    FROM t_guru
    WHERE status_aktif = 1
    ORDER BY nama ASC
");


/* =====================================
   PROSES SIMPAN
===================================== */

if (isset($_POST['simpan'])) {

    $tanggal = $_POST['tanggal'] ?? '';

    $kelas_id = (int)($_POST['kelas_id'] ?? 0);

    $siswa_id = (int)($_POST['siswa_id'] ?? 0);

    $nama_pelanggaran = trim($_POST['nama_pelanggaran'] ?? '');

    $nama_guru = trim($_POST['nama_guru'] ?? '');

    $keterangan = trim($_POST['keterangan'] ?? '');

    $poin = (int)($_POST['poin'] ?? 0);

    $tindakan = trim($_POST['tindakan'] ?? '');

    $status = $_POST['status'] ?? '';

    if (!$tahun_id) {

        $error = "Tahun ajaran aktif belum tersedia.";

    } elseif (
        $tanggal == '' ||
        $kelas_id <= 0 ||
        $siswa_id <= 0 ||
        $nama_pelanggaran == '' ||
        $nama_guru == '' ||
        $keterangan == '' ||
        $poin < 0 ||
        $tindakan == '' ||
        $status == ''
    ) {

        $error = "Semua kolom wajib diisi.";

    } else {

        try {

            /* =====================================
               CARI SISWA BERDASARKAN KELAS
            ===================================== */

            $stmtSiswa = mysqli_prepare($koneksi, "
                SELECT
                    s.id AS siswa_id,
                    s.nama AS nama_siswa,
                    ks.kelas_id,
                    k.nama AS nama_kelas
                FROM t_siswa s
                INNER JOIN t_kelas_siswa ks
                    ON ks.siswa_id = s.id
                INNER JOIN t_kelas k
                    ON k.id = ks.kelas_id
                WHERE s.id = ?
                  AND ks.kelas_id = ?
                  AND ks.tahun_ajaran_id = ?
                  AND ks.status_aktif = 1
                  AND s.status_aktif = 1
                  AND k.status_aktif = 1
                LIMIT 1
            ");

            mysqli_stmt_bind_param(
                $stmtSiswa,
                "iii",
                $siswa_id,
                $kelas_id,
                $tahun_id
            );

            mysqli_stmt_execute($stmtSiswa);

            $dataSiswa = mysqli_fetch_assoc(
                mysqli_stmt_get_result($stmtSiswa)
            );


            /* =====================================
               CARI JENIS PELANGGARAN
            ===================================== */

            $stmtPelanggaran = mysqli_prepare($koneksi, "
                SELECT
                    id,
                    nama,
                    pelanggaran_kategori_id,
                    poin
                FROM t_pelanggaran
                WHERE nama = ?
                  AND status_aktif = 1
                LIMIT 1
            ");

            mysqli_stmt_bind_param(
                $stmtPelanggaran,
                "s",
                $nama_pelanggaran
            );

            mysqli_stmt_execute($stmtPelanggaran);

            $dataPelanggaran = mysqli_fetch_assoc(
                mysqli_stmt_get_result($stmtPelanggaran)
            );


            /* =====================================
               CARI GURU
            ===================================== */

            $stmtGuru = mysqli_prepare($koneksi, "
                SELECT id, nama
                FROM t_guru
                WHERE nama = ?
                  AND status_aktif = 1
                LIMIT 1
            ");

            mysqli_stmt_bind_param(
                $stmtGuru,
                "s",
                $nama_guru
            );

            mysqli_stmt_execute($stmtGuru);

            $dataGuru = mysqli_fetch_assoc(
                mysqli_stmt_get_result($stmtGuru)
            );


            /* =====================================
               VALIDASI DATA
            ===================================== */

            if (!$dataSiswa) {

                $error = "Siswa tidak ditemukan pada kelas yang dipilih.";

            } elseif (!$dataPelanggaran) {

                $error = "Nama pelanggaran tidak ditemukan. Pilih pelanggaran yang tersedia.";

            } elseif (!$dataGuru) {

                $error = "Nama guru tidak ditemukan. Pilih guru yang tersedia.";

            } else {

                /* =====================================
                   AMBIL ID DAN NAMA DARI DATABASE
                ===================================== */

                $siswa_id = (int)$dataSiswa['siswa_id'];

                $nama_siswa = $dataSiswa['nama_siswa'];

                $kelas_id = (int)$dataSiswa['kelas_id'];

                $nama_kelas = $dataSiswa['nama_kelas'];

                $pelanggaran_id = (int)$dataPelanggaran['id'];

                $nama_pelanggaran = $dataPelanggaran['nama'];

                $kategori_id = (int)$dataPelanggaran['pelanggaran_kategori_id'];

                $guru_id = (int)$dataGuru['id'];

                $nama_guru = $dataGuru['nama'];


                /* =====================================
                   SIMPAN KE TABEL PELANGGARAN SISWA
                ===================================== */

                $queryInsert = "
                    INSERT INTO t_pelanggaran_siswa (
                        tahun_ajaran_id,
                        siswa_id,
                        nama_siswa,
                        kelas_id,
                        nama_kelas,
                        pelanggaran_id,
                        nama_pelanggaran,
                        pelanggaran_kategori_id,
                        guru_id,
                        nama_guru,
                        tanggal,
                        keterangan,
                        poin,
                        tindakan,
                        status
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ";

                $stmtInsert = mysqli_prepare(
                    $koneksi,
                    $queryInsert
                );

                mysqli_stmt_bind_param(
                    $stmtInsert,
                    "iisisiisiississ",
                    $tahun_id,
                    $siswa_id,
                    $nama_siswa,
                    $kelas_id,
                    $nama_kelas,
                    $pelanggaran_id,
                    $nama_pelanggaran,
                    $kategori_id,
                    $guru_id,
                    $nama_guru,
                    $tanggal,
                    $keterangan,
                    $poin,
                    $tindakan,
                    $status
                );

                mysqli_stmt_execute($stmtInsert);

                header("Location: catat_pelanggaran.php?pesan=tambah");
                exit;
            }

        } catch (mysqli_sql_exception $e) {

            $error = "Gagal menyimpan data: " . $e->getMessage();

        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Catat Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR TETAP -->
        <aside class="col-md-3 col-lg-2 bg-dark text-white p-3 position-fixed top-0 bottom-0 start-0 overflow-auto">

            <h5 class="text-white mb-4">
                Sistem Pelanggaran
            </h5>

            <ul class="nav nav-pills flex-column gap-2">

                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link text-white">
                        Dashboard
                    </a>
                </li>

                <?php if ($role == 'admin') { ?>

                    <li class="nav-item">
                        <a href="siswa.php" class="nav-link text-white">Kelola Siswa</a>
                    </li>

                    <li class="nav-item">
                        <a href="guru.php" class="nav-link text-white">Kelola Guru</a>
                    </li>

                    <li class="nav-item">
                        <a href="kelas.php" class="nav-link text-white">Kelola Kelas</a>
                    </li>

                    <li class="nav-item">
                        <a href="tahun_ajaran.php" class="nav-link text-white">Tahun Ajaran</a>
                    </li>

                    <li class="nav-item">
                        <a href="penempatan_siswa.php" class="nav-link text-white">Penempatan Siswa</a>
                    </li>

                    <li class="nav-item">
                        <a href="wali_kelas.php" class="nav-link text-white">Wali Kelas</a>
                    </li>

                    <li class="nav-item">
                        <a href="kategori_pelanggaran.php" class="nav-link text-white">Kategori Pelanggaran</a>
                    </li>

                    <li class="nav-item">
                        <a href="jenis_pelanggaran.php" class="nav-link text-white">Jenis Pelanggaran</a>
                    </li>

                    <li class="nav-item">
                        <a href="catat_pelanggaran.php" class="nav-link active">Catat Pelanggaran</a>
                    </li>

                    <li class="nav-item">
                        <a href="laporan.php" class="nav-link text-white">Laporan</a>
                    </li>

                <?php } ?>

                <?php if ($role == 'guru') { ?>

                    <li class="nav-item">
                        <a href="catat_pelanggaran.php" class="nav-link active">
                            Catat Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="tindakan.php" class="nav-link text-white">Tindakan</a>
                    </li>

                    <li class="nav-item">
                        <a href="riwayat.php" class="nav-link text-white">Riwayat</a>
                    </li>

                    <li class="nav-item">
                        <a href="rekap_poin.php" class="nav-link text-white">Rekap Poin</a>
                    </li>

                <?php } ?>

                <hr class="text-secondary">

                <li class="nav-item">
                    <a href="about_me.php" class="nav-link text-white">About Me</a>
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
        <main class="col-md-9 col-lg-10 p-4 offset-md-3 offset-lg-2">

            <h2 class="mb-2">Tambah Catat Pelanggaran</h2>

            <p class="text-secondary mb-4">
                Silakan isi data pelanggaran siswa.
            </p>


            <?php if ($tahunAktif) { ?>

                <div class="alert alert-info">
                    Tahun Ajaran Aktif:
                    <strong><?= e($tahunAktif['nama']); ?></strong>
                </div>

            <?php } else { ?>

                <div class="alert alert-warning">
                    Belum ada tahun ajaran yang aktif.
                </div>

            <?php } ?>


            <?php if ($error != '') { ?>

                <div class="alert alert-danger">
                    <?= e($error); ?>
                </div>

            <?php } ?>


            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white">
                    Form Tambah Catat Pelanggaran
                </div>

                <div class="card-body">

                    <form method="POST">

                        <!-- TANGGAL -->
                        <div class="mb-3">

                            <label class="form-label">
                                Tanggal
                            </label>

                            <input type="date"
                                   name="tanggal"
                                   class="form-control"
                                   value="<?= e($_POST['tanggal'] ?? date('Y-m-d')); ?>"
                                   required>

                        </div>


                        <!-- PILIH KELAS -->
                        <div class="mb-3">

                            <label class="form-label">
                                Nama Kelas
                            </label>

                            <select name="kelas_id"
                                    id="kelas_id"
                                    class="form-select"
                                    required>

                                <option value="">
                                    -- Pilih Kelas --
                                </option>

                                <?php foreach ($daftarKelas as $kelas) { ?>

                                    <option
                                        value="<?= e($kelas['id']); ?>"
                                        <?= (($_POST['kelas_id'] ?? '') == $kelas['id']) ? 'selected' : ''; ?>>

                                        <?= e($kelas['nama']); ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>


                        <!-- PILIH SISWA -->
                        <div class="mb-3">

                            <label class="form-label">
                                Nama Siswa
                            </label>

                            <select name="siswa_id"
                                    id="siswa_id"
                                    class="form-select"
                                    required>

                                <option value="">
                                    -- Pilih Nama Siswa --
                                </option>

                                <?php foreach ($daftarSiswa as $siswa) { ?>

                                    <option
                                        value="<?= e($siswa['id']); ?>"
                                        data-kelas="<?= e($siswa['kelas_id']); ?>"
                                        <?= (($_POST['siswa_id'] ?? '') == $siswa['id']) ? 'selected' : ''; ?>>

                                        <?= e($siswa['nama']); ?>
                                        - NIS: <?= e($siswa['nis']); ?>

                                    </option>

                                <?php } ?>

                            </select>

                            <small class="text-secondary">
                                Pilih kelas terlebih dahulu untuk menampilkan siswa.
                            </small>

                        </div>


                        <!-- NAMA PELANGGARAN -->
                        <div class="mb-3">

                            <label class="form-label">
                                Nama Pelanggaran
                            </label>

                            <input type="text"
                                   name="nama_pelanggaran"
                                   class="form-control"
                                   list="listPelanggaran"
                                   placeholder="Ketik atau pilih pelanggaran"
                                   value="<?= e($_POST['nama_pelanggaran'] ?? ''); ?>"
                                   required>

                            <datalist id="listPelanggaran">

                                <?php while ($pelanggaran = mysqli_fetch_assoc($daftarPelanggaran)) { ?>

                                    <option
                                        value="<?= e($pelanggaran['nama']); ?>"
                                        label="Poin: <?= e($pelanggaran['poin']); ?>">

                                    </option>

                                <?php } ?>

                            </datalist>

                        </div>


                        <!-- NAMA GURU -->
                        <div class="mb-3">

                            <label class="form-label">
                                Nama Guru
                            </label>

                            <input type="text"
                                   name="nama_guru"
                                   class="form-control"
                                   list="listGuru"
                                   placeholder="Ketik atau pilih nama guru"
                                   value="<?= e($_POST['nama_guru'] ?? ''); ?>"
                                   required>

                            <datalist id="listGuru">

                                <?php while ($guru = mysqli_fetch_assoc($daftarGuru)) { ?>

                                    <option value="<?= e($guru['nama']); ?>"></option>

                                <?php } ?>

                            </datalist>

                        </div>


                        <!-- POIN -->
                        <div class="mb-3">

                            <label class="form-label">
                                Poin Pelanggaran
                            </label>

                            <input type="number"
                                   name="poin"
                                   class="form-control"
                                   min="0"
                                   value="<?= e($_POST['poin'] ?? ''); ?>"
                                   required>

                        </div>


                        <!-- KETERANGAN -->
                        <div class="mb-3">

                            <label class="form-label">
                                Keterangan
                            </label>

                            <textarea name="keterangan"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Masukkan keterangan pelanggaran"
                                      required><?= e($_POST['keterangan'] ?? ''); ?></textarea>

                        </div>


                        <!-- TINDAKAN -->
                        <div class="mb-3">

                            <label class="form-label">
                                Tindakan
                            </label>

                            <textarea name="tindakan"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Masukkan tindakan yang diberikan"
                                      required><?= e($_POST['tindakan'] ?? ''); ?></textarea>

                        </div>


                        <!-- STATUS -->
                        <div class="mb-4">

                            <label class="form-label">
                                Status
                            </label>

                            <select name="status" class="form-select" required>

                                <option value="Belum Ditindaklanjuti"
                                    <?= ($_POST['status'] ?? 'Belum Ditindaklanjuti') == 'Belum Ditindaklanjuti' ? 'selected' : ''; ?>>

                                    Belum Ditindaklanjuti

                                </option>

                                <option value="Diproses"
                                    <?= ($_POST['status'] ?? '') == 'Diproses' ? 'selected' : ''; ?>>

                                    Diproses

                                </option>

                                <option value="Selesai"
                                    <?= ($_POST['status'] ?? '') == 'Selesai' ? 'selected' : ''; ?>>

                                    Selesai

                                </option>

                            </select>

                        </div>


                        <!-- TOMBOL -->
                        <button type="submit"
                                name="simpan"
                                class="btn btn-primary">

                            Simpan

                        </button>

                        <a href="catat_pelanggaran.php"
                           class="btn btn-secondary">

                            Kembali

                        </a>

                    </form>

                </div>

            </div>

        </main>

    </div>
</div>


<!-- JAVASCRIPT FILTER SISWA BERDASARKAN KELAS -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    const pilihKelas = document.getElementById('kelas_id');

    const pilihSiswa = document.getElementById('siswa_id');

    const semuaSiswa = Array.from(pilihSiswa.options).slice(1);

    const siswaTerpilihAwal = "<?= e($_POST['siswa_id'] ?? ''); ?>";


    function filterSiswa() {

        const kelasDipilih = pilihKelas.value;

        pilihSiswa.innerHTML = '';

        const placeholder = document.createElement('option');

        placeholder.value = '';

        placeholder.textContent = kelasDipilih
            ? '-- Pilih Nama Siswa --'
            : '-- Pilih Kelas Terlebih Dahulu --';

        pilihSiswa.appendChild(placeholder);


        semuaSiswa.forEach(function (option) {

            if (option.dataset.kelas === kelasDipilih && kelasDipilih !== '') {

                const pilihan = option.cloneNode(true);

                pilihSiswa.appendChild(pilihan);

            }

        });


        if (
            siswaTerpilihAwal &&
            pilihSiswa.querySelector('option[value="' + siswaTerpilihAwal + '"]')
        ) {

            pilihSiswa.value = siswaTerpilihAwal;

        }

    }


    pilihKelas.addEventListener('change', function () {

        filterSiswa();

    });


    filterSiswa();

});

</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
