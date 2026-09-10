<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Masuk') | TaskApp - Aplikasi Manajemen Tugas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Aplikasi Manajemen Tugas Pegawai berbasis Laravel" name="description" />
    <meta content="TaskApp" name="author" />

    
    <link rel="shortcut icon" href="/admin/assets/images/favicon.ico">

    
    <script src="/admin/assets/js/config.js"></script>

    
    <link href="/admin/assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

    
    <link href="/admin/assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    @stack('styles')
</head>

<body class="authentication-bg pb-0">

    <div class="auth-fluid">
        
        <div class="auth-fluid-right text-center">
            <div class="auth-user-testimonial">
                <h2 class="mb-3 text-white">Kelola Tugas Lebih Mudah & Terstruktur</h2>
                <p class="lead"><i class="ri-double-quotes-l"></i> Tingkatkan produktivitas kerja dengan memantau tenggat waktu, menentukan prioritas, dan menyelesaikan tugas tepat waktu. <i class="ri-double-quotes-r"></i></p>
                <h5 class="text-white">- Mini Project Laravel 12</h5>
            </div> 
        </div>
        

        
        <div class="auth-fluid-form-box">
            <div class="card-body d-flex flex-column h-100 gap-3">

                
                <div class="auth-brand text-center text-lg-start">
                    <a href="{{ url('/') }}" class="logo-dark">
                        <span><img src="/admin/assets/images/logo-dark.png" alt="dark logo" height="26"></span>
                    </a>
                    <a href="{{ url('/') }}" class="logo-light">
                        <span><img src="/admin/assets/images/logo.png" alt="logo" height="26"></span>
                    </a>
                </div>

                <div class="my-auto">
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ri-check-line me-1 align-middle"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ri-error-warning-line me-1 align-middle fs-16"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ri-alert-line me-1 align-middle fs-16"></i>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    
                    @yield('content')
                </div>

                
                <footer class="footer footer-alt">
                    <p class="text-muted mb-0">{{ date('Y') }} &copy; TaskApp - Mini Project Laravel</p>
                </footer>

            </div> 
        </div>
        
    </div>
    

    
    <script src="/admin/assets/js/vendor.min.js"></script>

    
    <script src="/admin/assets/js/app.min.js"></script>

    @stack('scripts')

</body>

</html>
