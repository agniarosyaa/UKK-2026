<?php
session_start();

if (isset($_SESSION['login'])) {
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

<body class="bg-body-tertiary">

<div class="container min-vh-100 d-flex justify-content-center align-items-center">

    <div class="card border-0 shadow-lg rounded-4 w-100"
         style="max-width: 460px;">

        <!-- HEADER -->
        <div class="card-header bg-primary text-white text-center py-4 rounded-top-4">
            <h3 class="fw-bold mb-2">
                Sistem Pelanggaran Siswa
            </h3>

            <p class="mb-0 small text-white-50">
                Silakan login untuk melanjutkan
            </p>
        </div>

        <!-- FORM LOGIN -->
        <div class="card-body p-4 p-md-5">

            <?php if ($error != ''): ?>
                <div class="alert alert-danger alert-dismissible fade show"
                     role="alert">
                    <?= htmlspecialchars($error); ?>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                    </button>
                </div>
            <?php endif; ?>

            <form action="proses_login.php" method="POST">

                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold">
                        Email
                    </label>

                    <input
                        type="email"
                        class="form-control form-control-lg rounded-3"
                        id="email"
                        name="email"
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
                        autocomplete="current-password"
                        required>
                </div>

                <button type="submit"
                        name="login"
                        class="btn btn-primary btn-lg w-100 rounded-3 fw-semibold">
                    Login
                </button>

            </form>

        </div>

        <!-- FOOTER -->
        <div class="card-footer bg-white text-center py-3 border-0">
            <small class="text-secondary">
                Sistem Informasi Pelanggaran Siswa
            </small>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>