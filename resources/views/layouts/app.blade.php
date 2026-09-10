<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Aplikasi Manajemen Tugas') | TaskApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Aplikasi Manajemen Tugas Pegawai berbasis Laravel" name="description" />
    <meta content="TaskApp" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('admin/assets/images/favicon.ico') }}">

    <!-- Theme Config Js -->
    <script src="{{ asset('admin/assets/js/config.js') }}"></script>

    <!-- App css -->
    <link href="{{ asset('admin/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="{{ asset('admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .page-title-box {
            padding: 18px 0;
        }
        .card-header {
            padding: 1rem 1.25rem;
            background-color: transparent;
        }
        .card-body {
            padding: 1.25rem;
        }
        .table > :not(caption) > * > * {
            padding: 0.75rem 0.85rem;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <!-- ========== Topbar Start ========== -->
        <div class="navbar-custom">
            <div class="topbar container-fluid">
                <div class="d-flex align-items-center gap-lg-2 gap-1">

                    <!-- Topbar Brand Logo -->
                    <div class="logo-topbar">
                        <!-- Logo light -->
                        <a href="{{ route('dashboard') }}" class="logo-light">
                            <span class="logo-lg">
                                <img src="{{ asset('admin/assets/images/logo.png') }}" alt="logo">
                            </span>
                            <span class="logo-sm">
                                <img src="{{ asset('admin/assets/images/logo-sm.png') }}" alt="small logo">
                            </span>
                        </a>

                        <!-- Logo Dark -->
                        <a href="{{ route('dashboard') }}" class="logo-dark">
                            <span class="logo-lg">
                                <img src="{{ asset('admin/assets/images/logo-dark.png') }}" alt="dark logo">
                            </span>
                            <span class="logo-sm">
                                <img src="{{ asset('admin/assets/images/logo-sm.png') }}" alt="small logo">
                            </span>
                        </a>
                    </div>

                    <!-- Sidebar Menu Toggle Button -->
                    <button class="button-toggle-menu">
                        <i class="ri-menu-2-fill"></i>
                    </button>
                </div>

                <ul class="topbar-menu d-flex align-items-center gap-3">
                    <li class="d-none d-sm-inline-block">
                        <div class="nav-link" id="light-dark-mode" data-bs-toggle="tooltip" data-bs-placement="left" title="Ganti Mode Tampilan">
                            <i class="ri-moon-line fs-22"></i>
                        </div>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle arrow-none nav-user px-2" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="account-user-avatar">
                                <div class="avatar-sm d-inline-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold">
                                    {{ Auth::user()->initials }}
                                </div>
                            </span>
                            <span class="d-lg-flex flex-column gap-1 d-none">
                                <h5 class="my-0">{{ Auth::user()->name }}</h5>
                                <h6 class="my-0 fw-normal text-capitalize">
                                    <span class="badge {{ Auth::user()->isAdmin() ? 'bg-danger-subtle text-danger' : 'bg-primary-subtle text-primary' }}">
                                        {{ Auth::user()->role }}
                                    </span>
                                </h6>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Halo, {{ Auth::user()->name }}!</h6>
                            </div>

                            <a href="{{ route('tasks.index') }}" class="dropdown-item">
                                <i class="ri-task-line fs-18 align-middle me-1"></i>
                                <span>Tugas Saya</span>
                            </a>

                            <a href="{{ route('tasks.create') }}" class="dropdown-item">
                                <i class="ri-add-circle-line fs-18 align-middle me-1"></i>
                                <span>Tambah Tugas</span>
                            </a>

                            <div class="dropdown-divider"></div>

                            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="ri-logout-box-line fs-18 align-middle me-1"></i>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- ========== Topbar End ========== -->

        <!-- ========== Left Sidebar Start ========== -->
        <div class="leftside-menu">

            <!-- Brand Logo Light -->
            <a href="{{ route('dashboard') }}" class="logo logo-light">
                <span class="logo-lg">
                    <img src="{{ asset('admin/assets/images/logo.png') }}" alt="logo">
                </span>
                <span class="logo-sm">
                    <img src="{{ asset('admin/assets/images/logo-sm.png') }}" alt="small logo">
                </span>
            </a>

            <!-- Brand Logo Dark -->
            <a href="{{ route('dashboard') }}" class="logo logo-dark">
                <span class="logo-lg">
                    <img src="{{ asset('admin/assets/images/logo-dark.png') }}" alt="dark logo">
                </span>
                <span class="logo-sm">
                    <img src="{{ asset('admin/assets/images/logo-sm.png') }}" alt="small logo">
                </span>
            </a>

            <!-- Sidebar Hover Menu Toggle Button -->
            <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Tampilkan Sidebar">
                <i class="ri-checkbox-blank-circle-line align-middle"></i>
            </div>

            <!-- Full Sidebar Menu Close Button -->
            <div class="button-close-fullsidebar">
                <i class="ri-close-fill align-middle"></i>
            </div>

            <!-- Sidebar -left -->
            <div class="h-100" id="leftside-menu-container" data-simplebar>

                <!--- Sidemenu -->
                <ul class="side-nav">

                    <li class="side-nav-title">Menu Utama</li>

                    <li class="side-nav-item {{ request()->routeIs('dashboard') ? 'menuitem-active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="side-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="ri-dashboard-2-line"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>

                    <li class="side-nav-title">Manajemen Tugas</li>

                    <li class="side-nav-item {{ request()->routeIs('tasks.index') ? 'menuitem-active' : '' }}">
                        <a href="{{ route('tasks.index') }}" class="side-nav-link {{ request()->routeIs('tasks.index') ? 'active' : '' }}">
                            <i class="ri-task-line"></i>
                            <span> Daftar Tugas </span>
                        </a>
                    </li>

                    <li class="side-nav-item {{ request()->routeIs('tasks.create') ? 'menuitem-active' : '' }}">
                        <a href="{{ route('tasks.create') }}" class="side-nav-link {{ request()->routeIs('tasks.create') ? 'active' : '' }}">
                            <i class="ri-add-circle-line"></i>
                            <span> Tambah Tugas </span>
                        </a>
                    </li>

                    <li class="side-nav-item {{ request()->routeIs('categories.*') ? 'menuitem-active' : '' }}">
                        <a href="{{ route('categories.index') }}" class="side-nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <i class="ri-price-tag-3-line"></i>
                            <span> Kategori </span>
                        </a>
                    </li>

                    @if(Auth::user()->isAdmin())
                        <li class="side-nav-title">Administrator</li>

                        <li class="side-nav-item {{ request()->routeIs('admin.tasks') ? 'menuitem-active' : '' }}">
                            <a href="{{ route('admin.tasks') }}" class="side-nav-link {{ request()->routeIs('admin.tasks') ? 'active' : '' }}">
                                <i class="ri-file-list-3-line"></i>
                                <span> Semua Tugas </span>
                            </a>
                        </li>

                        <li class="side-nav-item {{ request()->routeIs('admin.users') ? 'menuitem-active' : '' }}">
                            <a href="{{ route('admin.users') }}" class="side-nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                                <i class="ri-user-star-line"></i>
                                <span> Rekap Pengguna </span>
                            </a>
                        </li>
                    @endif

                </ul>
                <!--- End Sidemenu -->

                <div class="clearfix"></div>
            </div>
        </div>
        <!-- ========== Left Sidebar End ========== -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">TaskApp</a></li>
                                        @yield('breadcrumb')
                                    </ol>
                                </div>
                                <h4 class="page-title">@yield('page_title', 'Dashboard')</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                            <i class="ri-check-line me-1 align-middle fs-16"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                            <i class="ri-error-warning-line me-1 align-middle fs-16"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                            <i class="ri-alert-line me-1 align-middle fs-16"></i>
                            <strong>Terdapat kesalahan input:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Main Dynamic Content -->
                    @yield('content')

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            {{ date('Y') }} &copy; Aplikasi Manajemen Tugas
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-end footer-links d-none d-md-block">
                                <a href="{{ route('dashboard') }}">Dashboard</a>
                                <a href="{{ route('tasks.index') }}">Tugas</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Vendor js -->
    <script src="{{ asset('admin/assets/js/vendor.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('admin/assets/js/app.min.js') }}"></script>

    @stack('scripts')
</body>

</html>
