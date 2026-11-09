
<?php
session_start();

if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Sistem Pelanggaran Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-primary-subtle">

<div class="container min-vh-100 d-flex justify-content-center align-items-center py-4">

    <div class="card border-0 shadow-lg rounded-4 w-100 overflow-hidden"
         style="max-width: 450px;">

        <div class="card-header bg-primary text-white text-center border-0 py-4">

            <img src="logo_smk.jpeg"
                 alt="Logo SMK Muhammadiyah"
                 width="95"
                 height="95"
                 class="img-fluid rounded-circle bg-white p-2 mb-3">

            <h4 class="fw-bold mb-2">
                Sistem Informasi
            </h4>

            <h5 class="fw-semibold mb-2">
                Pelanggaran Siswa
            </h5>

            <p class="small mb-0 text-white-50">
                SMK Muhammadiyah Tasikmalaya
            </p>

        </div>

        <div class="card-body p-4 p-md-5">

            <div class="text-center mb-4">

                <h4 class="fw-bold text-primary mb-2">
                    Selamat Datang!
                </h4>

                <p class="text-secondary mb-0">
                    Silahkan login untuk melanjutkan.
                </p>

            </div>

            <?php if ($error != ''): ?>
                <div class="alert alert-danger alert-dismissible fade show"
                     role="alert">

                    <?= htmlspecialchars($error); ?>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Tutup">
                    </button>

                </div>
            <?php endif; ?>

            <form action="proses_login.php" method="POST">

                <div class="mb-3">

                    <label for="email" class="form-label fw-semibold">
                        Email
                    </label>

                    <input
                        type="email"
                        class="form-control form-control-lg rounded-3"
                        id="email"
                        name="email"
                        placeholder="Masukkan email"
                        autocomplete="username"
                        required>

                </div>

                <div class="mb-4">

                    <label for="password" class="form-label fw-semibold">
                        Password
                    </label>

                    <input
                        type="password"
                        class="form-control form-control-lg rounded-3"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required>

                </div>

                <button type="submit"
                        name="login"
                        class="btn btn-primary btn-lg w-100 rounded-3 fw-semibold shadow-sm">

                    Masuk ke Sistem

                </button>

            </form>

        </div>

        <div class="card-footer bg-light text-center border-0 py-3">

            <small class="text-secondary d-block">
                Sistem Informasi Pelanggaran Siswa
            </small>

            <small class="text-secondary">
                © 2026 SMK Muhammadiyah Tasikmalaya
            </small>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
