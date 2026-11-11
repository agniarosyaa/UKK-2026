
<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

include "config/koneksi.php";

/* =========================================================
   CEK ROLE LOGIN
   ROLE DATABASE HANYA:
   admin
   guru
========================================================= */

$role = strtolower(trim($_SESSION['role'] ?? ''));
$role = str_replace([' ', '-'], '_', $role);

if (!in_array($role, ['admin', 'guru'], true)) {
    exit("Anda tidak memiliki akses.");
}


/* =========================================================
   ID USER LOGIN
========================================================= */

$user_id = (int) (
    $_SESSION['user_id']
    ?? $_SESSION['id_user']
    ?? 0
);

if ($user_id <= 0) {
    exit("ID user tidak ditemukan. Silakan login kembali.");
}


/* =========================================================
   CEK WALI KELAS
   ROLE DATABASE TETAP GURU
========================================================= */

$is_wali_kelas = false;


/* ---------------------------------------------------------
   CEK DARI SESSION
--------------------------------------------------------- */

if ($role === 'guru') {

    if (
        !empty($_SESSION['is_wali_kelas']) ||
        !empty($_SESSION['akses_wali_kelas'])
    ) {
        $is_wali_kelas = true;
    }
}


/* ---------------------------------------------------------
   CEK DARI DATABASE
   Sebagai fallback jika session belum terbaca
--------------------------------------------------------- */

if ($role === 'guru' && !$is_wali_kelas) {

    $sql_wali = "
        SELECT wk.id
        FROM t_wali_kelas wk

        INNER JOIN t_guru g
            ON g.id = wk.guru_id

        INNER JOIN t_tahun_ajaran ta
            ON ta.id = wk.tahun_ajaran_id

        WHERE g.user_id = ?
          AND wk.status_aktif = 1
          AND ta.status_aktif = 1

        LIMIT 1
    ";

    $stmt_wali = mysqli_prepare($koneksi, $sql_wali);

    if (!$stmt_wali) {
        die(
            "Gagal mengecek status wali kelas: "
            . mysqli_error($koneksi)
        );
    }

    mysqli_stmt_bind_param(
        $stmt_wali,
        "i",
        $user_id
    );

    mysqli_stmt_execute($stmt_wali);

    $result_wali = mysqli_stmt_get_result($stmt_wali);

    if (
        $result_wali &&
        mysqli_num_rows($result_wali) > 0
    ) {
        $is_wali_kelas = true;
    }

    mysqli_stmt_close($stmt_wali);
}


/* =========================================================
   ROLE UNTUK SIDEBAR
========================================================= */

$display_role = $role;

if ($role === 'guru' && $is_wali_kelas) {
    $display_role = 'wali_kelas';
}


/* =========================================================
   QUERY DASAR
========================================================= */

$query = "
    SELECT
        ps.id,
        ps.nama_siswa,

        COALESCE(
            NULLIF(TRIM(k.nama), ''),
            NULLIF(NULLIF(TRIM(ps.nama_kelas), ''), '0'),
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
";


/* =========================================================
   ADMIN
   SEMUA RIWAYAT SELESAI
========================================================= */

if ($role === 'admin') {

    $query .= "
        WHERE LOWER(TRIM(COALESCE(ps.status, ''))) = 'selesai'

        ORDER BY
            ps.tanggal DESC,
            ps.id DESC
    ";

    $result = mysqli_query($koneksi, $query);

    if (!$result) {
        die(
            "Gagal mengambil data riwayat: "
            . mysqli_error($koneksi)
        );
    }


/* =========================================================
   GURU BIASA
   SEMUA RIWAYAT SELESAI
========================================================= */

} elseif (
    $role === 'guru' &&
    !$is_wali_kelas
) {

    $query .= "
        WHERE LOWER(TRIM(COALESCE(ps.status, ''))) = 'selesai'

        ORDER BY
            ps.tanggal DESC,
            ps.id DESC
    ";

    $result = mysqli_query($koneksi, $query);

    if (!$result) {
        die(
            "Gagal mengambil data riwayat: "
            . mysqli_error($koneksi)
        );
    }


/* =========================================================
   WALI KELAS
   HANYA RIWAYAT KELAS YANG DIA PEGANG
========================================================= */

} elseif (
    $role === 'guru' &&
    $is_wali_kelas
) {

    $query .= "
        WHERE LOWER(TRIM(COALESCE(ps.status, ''))) = 'selesai'

        AND EXISTS (

            SELECT 1
            FROM t_wali_kelas wk

            INNER JOIN t_guru g
                ON g.id = wk.guru_id

            INNER JOIN t_tahun_ajaran ta
                ON ta.id = wk.tahun_ajaran_id

            WHERE g.user_id = ?
              AND wk.kelas_id = ps.kelas_id
              AND wk.tahun_ajaran_id = ps.tahun_ajaran_id
              AND wk.status_aktif = 1
              AND ta.status_aktif = 1
        )

        ORDER BY
            ps.tanggal DESC,
            ps.id DESC
    ";

    $stmt = mysqli_prepare($koneksi, $query);

    if (!$stmt) {
        die(
            "Gagal menyiapkan query wali kelas: "
            . mysqli_error($koneksi)
        );
    }

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $user_id
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        die(
            "Gagal mengambil data riwayat: "
            . mysqli_error($koneksi)
        );
    }

}


/* =========================================================
   TOTAL DATA
========================================================= */

$total_catatan = mysqli_num_rows($result);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Riwayat Pelanggaran</title>

    <!-- Bootstrap 5.3.8 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- DataTables 2.3.4 -->
    <link
        href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
        rel="stylesheet">

</head>


<body class="bg-body-tertiary">


<div class="container-fluid p-0">

    <div class="row g-0">


        <!-- =================================================
             SIDEBAR
        ================================================== -->

        <aside
            class="col-md-3 col-lg-2 bg-dark text-white p-3 position-fixed top-0 bottom-0 start-0 overflow-auto">

            <h5 class="text-white mb-4">
                Sistem Pelanggaran
            </h5>


            <nav class="nav nav-pills flex-column gap-2">


                <!-- =================================================
                     SIDEBAR ADMIN
                ================================================== -->

                <?php if ($display_role === 'admin'): ?>

                    <a
                        href="dashboard.php"
                        class="nav-link text-white">
                        Dashboard
                    </a>

                    <a
                        href="siswa.php"
                        class="nav-link text-white">
                        Kelola Siswa
                    </a>

                    <a
                        href="guru.php"
                        class="nav-link text-white">
                        Kelola Guru
                    </a>

                    <a
                        href="kelas.php"
                        class="nav-link text-white">
                        Kelola Kelas
                    </a>

                    <a
                        href="tahun_ajaran.php"
                        class="nav-link text-white">
                        Tahun Ajaran
                    </a>

                    <a
                        href="penempatan_siswa.php"
                        class="nav-link text-white">
                        Penempatan Siswa
                    </a>

                    <a
                        href="wali_kelas.php"
                        class="nav-link text-white">
                        Wali Kelas
                    </a>

                    <a
                        href="kategori_pelanggaran.php"
                        class="nav-link text-white">
                        Kategori Pelanggaran
                    </a>

                    <a
                        href="pelanggaran.php"
                        class="nav-link text-white">
                        Jenis Pelanggaran
                    </a>

                    <a
                        href="laporan.php"
                        class="nav-link text-white">
                        Laporan
                    </a>

                    <hr class="text-secondary">

                    <a
                        href="about_me.php"
                        class="nav-link text-white">
                        About Me
                    </a>

                    <a
                        href="logout.php"
                        class="nav-link text-danger"
                        onclick="return confirm('Yakin ingin logout?')">
                        Logout
                    </a>


                <!-- =================================================
                     SIDEBAR GURU BIASA
                ================================================== -->

                <?php elseif ($display_role === 'guru'): ?>

                    <a
                        href="dashboard.php"
                        class="nav-link text-white">
                        Dashboard
                    </a>

                    <a
                        href="catat_pelanggaran.php"
                        class="nav-link text-white">
                        Catat Pelanggaran
                    </a>

                    <a
                        href="tindakan.php"
                        class="nav-link text-white">
                        Tindakan
                    </a>

                    <a
                        href="riwayat.php"
                        class="nav-link active">
                        Riwayat
                    </a>

                    <a
                        href="rekap_poin.php"
                        class="nav-link text-white">
                        Rekap Poin
                    </a>

                    <hr class="text-secondary">

                    <a
                        href="about_me.php"
                        class="nav-link text-white">
                        About Me
                    </a>

                    <a
                        href="logout.php"
                        class="nav-link text-danger"
                        onclick="return confirm('Yakin ingin logout?')">
                        Logout
                    </a>


                <!-- =================================================
                     SIDEBAR WALI KELAS
                ================================================== -->

                <?php elseif ($display_role === 'wali_kelas'): ?>

                    <a
                        href="dashboard.php"
                        class="nav-link text-white">
                        Dashboard
                    </a>

                    <a
                        href="catat_pelanggaran.php"
                        class="nav-link text-white">
                        Catat Pelanggaran
                    </a>

                    <a
                        href="walikelas/data_siswa.php"
                        class="nav-link text-white">
                        Data Siswa
                    </a>

                    <a
                        href="walikelas/rekap_pelanggaran.php"
                        class="nav-link text-white">
                        Rekap Pelanggaran
                    </a>

                    <a
                        href="walikelas/laporan.php"
                        class="nav-link text-white">
                        Laporan
                    </a>

                    <!-- RIWAYAT TETAP DI LUAR FOLDER WALIKELAS -->

                    <a
                        href="riwayat.php"
                        class="nav-link active">
                        Riwayat
                    </a>

                    <a
                        href="walikelas/rekap_poin.php"
                        class="nav-link text-white">
                        Rekap Poin
                    </a>

                    <hr class="text-secondary">

                    <a
                        href="about_me.php"
                        class="nav-link text-white">
                        About Me
                    </a>

                    <a
                        href="logout.php"
                        class="nav-link text-danger"
                        onclick="return confirm('Yakin ingin logout?')">
                        Logout
                    </a>

                <?php endif; ?>

            </nav>

        </aside>


        <!-- =================================================
             KONTEN UTAMA
        ================================================== -->

        <main
            class="col-md-9 col-lg-10 p-4 offset-md-3 offset-lg-2">


            <h2 class="mb-1">
                Riwayat Pelanggaran
            </h2>

            <p class="text-secondary mb-4">
                Daftar catatan pelanggaran siswa yang tersimpan.
            </p>


            <!-- =================================================
                 NOTIFIKASI BERHASIL
            ================================================== -->

            <?php if (isset($_GET['updated'])): ?>

                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert">

                    Keterangan berhasil diperbarui.

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 NOTIFIKASI ERROR
            ================================================== -->

            <?php if (isset($_GET['error'])): ?>

                <div
                    class="alert alert-danger alert-dismissible fade show"
                    role="alert">

                    Keterangan gagal diperbarui.

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 TOTAL CATATAN
            ================================================== -->

            <div class="row g-3 mb-4">

                <div class="col-md-4">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body">

                            <p class="text-secondary mb-1">
                                Total Catatan
                            </p>

                            <h3 class="mb-0">
                                <?= $total_catatan; ?>
                            </h3>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 TABEL
            ================================================== -->

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-primary text-white py-3">
                    Data Riwayat Pelanggaran
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table
                            id="tabelRiwayat"
                            class="table table-bordered table-hover align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jenis Pelanggaran</th>
                                    <th>Tanggal</th>
                                    <th>Poin</th>
                                    <th>Tindakan</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>

                                </tr>

                            </thead>


                            <tbody>

                            <?php

                            $no = 1;

                            while (
                                $row = mysqli_fetch_assoc($result)
                            ):

                            ?>

                                <tr>

                                    <td>
                                        <?= $no++; ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $row['nama_siswa'] ?? '-'
                                        ); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $row['nama_kelas'] ?? '-'
                                        ); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $row['nama_pelanggaran'] ?? '-'
                                        ); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $row['tanggal'] ?? '-'
                                        ); ?>
                                    </td>

                                    <td>

                                        <strong>
                                            <?= (int)(
                                                $row['poin'] ?? 0
                                            ); ?>
                                            poin
                                        </strong>

                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $row['tindakan'] ?? '-'
                                        ); ?>
                                    </td>

                                    <td>

                                        <?php

                                        $status = trim(
                                            $row['status'] ?? ''
                                        );

                                        ?>

                                        <?php if (
                                            strtolower($status) === 'selesai'
                                        ): ?>

                                            <span class="badge bg-success">
                                                Selesai
                                            </span>

                                        <?php elseif (
                                            strtolower($status) === 'diproses'
                                        ): ?>

                                            <span
                                                class="badge bg-warning text-dark">
                                                Diproses
                                            </span>

                                        <?php else: ?>

                                            <span class="badge bg-secondary">
                                                <?= htmlspecialchars(
                                                    $status ?: '-'
                                                ); ?>
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $row['keterangan'] ?? '-'
                                        ); ?>
                                    </td>

                                    <td>

                                        <a
                                            href="edit_keterangan.php?id=<?= (int)$row['id']; ?>"
                                            class="btn btn-primary btn-sm">
                                            Edit
                                        </a>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                            </tbody>

                        </table>

                    </div>


                    <!-- =================================================
                         KEMBALI
                    ================================================== -->

                    <a
                        href="dashboard.php"
                        class="btn btn-secondary mt-2">

                        Kembali ke Dashboard

                    </a>

                </div>

            </div>

        </main>

    </div>

</div>


<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script
    src="https://code.jquery.com/jquery-3.7.1.min.js">
</script>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
</script>

<script
    src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js">
</script>

<script
    src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js">
</script>


<script>

$(document).ready(function () {

    $('#tabelRiwayat').DataTable({

        pageLength: 10,

        lengthMenu: [5, 10, 25, 50],

        columnDefs: [

            {
                orderable: false,
                targets: [0, 9]
            }

        ],

        language: {

            search: "Cari:",

            lengthMenu: "Tampilkan _MENU_ data",

            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",

            infoEmpty: "Tidak ada data",

            infoFiltered: "(difilter dari _MAX_ data)",

            zeroRecords: "Data tidak ditemukan",

            emptyTable: "Belum ada riwayat pelanggaran",

            paginate: {

                first: "Pertama",

                last: "Terakhir",

                next: "Selanjutnya",

                previous: "Sebelumnya"

            }

        }

    });

});

</script>

</body>

</html>