<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>403 Akses Ditolak | TaskApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/assets/images/favicon.ico">
    <script src="/assets/js/config.js"></script>
    <link href="/assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />
    <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
</head>
<body class="authentication-bg">
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-5 col-lg-6">
                    <div class="card overflow-hidden">
                        <div class="card-header py-4 text-center bg-danger">
                            <h2 class="text-white fw-bold mb-0">TaskApp</h2>
                        </div>
                        <div class="card-body p-4 text-center">
                            <div class="text-center">
                                <h1 class="text-error display-3 text-danger fw-bold">403</h1>
                                <h4 class="text-uppercase text-danger mt-3">Akses Ditolak (Forbidden)</h4>
                                <p class="text-muted mt-3">
                                    {{ $exception->getMessage() ?: 'Anda tidak memiliki hak akses untuk melihat, mengubah, atau menghapus data tugas milik pengguna lain.' }}
                                </p>
                                <a class="btn btn-primary mt-3" href="{{ route('dashboard') }}">
                                    <i class="ri-home-4-line me-1"></i> Kembali ke Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer footer-alt fw-medium">
        <span class="text-dark-emphasis">&copy; {{ date('Y') }} TaskApp - Aplikasi Manajemen Tugas</span>
    </footer>
    <script src="/assets/js/vendor.min.js"></script>
    <script src="/assets/js/app.min.js"></script>
</body>
</html>
