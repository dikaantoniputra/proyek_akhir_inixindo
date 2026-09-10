<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Aplikasi Manajemen Tugas') | TaskApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Aplikasi Manajemen Tugas Pegawai berbasis Laravel" name="description" />
    <meta content="TaskApp" name="author" />

    
    <link rel="shortcut icon" href="/admin/assets/images/favicon.ico">

    
    <script>
        var savedTheme = localStorage.getItem('taskapp_theme');
        if (savedTheme) {
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        }
    </script>

    
    <script src="/admin/assets/js/config.js"></script>

    
    <link href="/admin/assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

    
    <link href="/admin/assets/css/icons.min.css" rel="stylesheet" type="text/css" />

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
    
    <div class="wrapper">

        
        <div class="navbar-custom">
            <div class="topbar container-fluid">
                <div class="d-flex align-items-center gap-lg-2 gap-1">

                    
                    <div class="logo-topbar">
                        
                        <a href="{{ route('dashboard') }}" class="logo-light">
                            <span class="logo-lg">
                                <img src="/admin/assets/images/logo.png" alt="logo">
                            </span>
                            <span class="logo-sm">
                                <img src="/admin/assets/images/logo-sm.png" alt="small logo">
                            </span>
                        </a>

                        
                        <a href="{{ route('dashboard') }}" class="logo-dark">
                            <span class="logo-lg">
                                <img src="/admin/assets/images/logo-dark.png" alt="dark logo">
                            </span>
                            <span class="logo-sm">
                                <img src="/admin/assets/images/logo-sm.png" alt="small logo">
                            </span>
                        </a>
                    </div>

                    
                    <button class="button-toggle-menu">
                        <i class="ri-menu-2-fill"></i>
                    </button>
                </div>

                <ul class="topbar-menu d-flex align-items-center gap-2">
                    <li class="d-none d-sm-inline-block">
                        <div class="nav-link" id="light-dark-mode" data-bs-toggle="tooltip" data-bs-placement="left" title="Ganti Mode Tampilan">
                            <i class="ri-moon-line fs-22"></i>
                        </div>
                    </li>

                    
                    @php
                        $urgentTasks = Auth::user()->tasks()
                            ->where('status', '!=', 'selesai')
                            ->whereNotNull('due_date')
                            ->whereDate('due_date', '<=', \Carbon\Carbon::today()->addDays(1))
                            ->orderBy('due_date', 'asc')
                            ->take(5)
                            ->get();
                        $urgentCount = $urgentTasks->count();
                        $hasOverdue = $urgentTasks->contains(fn($t) => $t->is_overdue);
                    @endphp
                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ri-notification-3-line fs-22"></i>
                            @if($urgentCount > 0)
                                <span class="noti-icon-badge badge {{ $hasOverdue ? 'bg-danger' : 'bg-warning' }} rounded-circle position-absolute top-0 start-100 translate-middle p-1" style="transform: translate(-10px, 14px) !important;">
                                    <span class="visually-hidden">notifikasi</span>
                                </span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg py-0 shadow-lg border">
                            <div class="p-2 border-top-0 border-start-0 border-end-0 border-dashed border">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 fs-13 fw-semibold">Pengingat Tenggat Waktu</h6>
                                    </div>
                                    <div class="col-auto">
                                        <span class="badge {{ $urgentCount > 0 ? ($hasOverdue ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning') : 'bg-success-subtle text-success' }} fs-11">
                                            {{ $urgentCount }} Tugas Mendesak
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div style="max-height: 280px;" data-simplebar>
                                @forelse($urgentTasks as $utask)
                                    <a href="{{ route('tasks.show', $utask) }}" class="dropdown-item notify-item border-bottom py-2">
                                        <div class="notify-icon {{ $utask->is_overdue ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning' }} rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="{{ $utask->is_overdue ? 'ri-alarm-warning-line' : 'ri-time-line' }} fs-16"></i>
                                        </div>
                                        <p class="notify-details mb-0 fw-semibold text-truncate fs-12">
                                            {{ $utask->title }}
                                        </p>
                                        <p class="text-muted mb-0 user-msg fs-11">
                                            <span class="{{ $utask->is_overdue ? 'text-danger fw-bold' : 'text-warning' }}">
                                                {{ $utask->is_overdue ? 'Terlewat: ' : 'Jatuh tempo: ' }} {{ $utask->due_date_label }}
                                            </span> • {{ ucfirst($utask->priority) }}
                                        </p>
                                    </a>
                                @empty
                                    <div class="text-center py-3 text-muted fs-12">
                                        <i class="ri-checkbox-circle-line fs-24 text-success d-block mb-1"></i>
                                        Tidak ada tugas mendesak atau terlewat saat ini.
                                    </div>
                                @endforelse
                            </div>

                            <a href="{{ route('tasks.index', ['status' => 'overdue']) }}" class="dropdown-item text-center text-primary notify-item border-top border-light py-2 fs-12 fw-semibold">
                                Lihat Semua Tugas Terlewat <i class="ri-arrow-right-line ms-1"></i>
                            </a>
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

                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="ri-user-line fs-18 align-middle me-1"></i>
                                <span>Profil Saya</span>
                            </a>

                            <a href="{{ route('tasks.index') }}" class="dropdown-item">
                                <i class="ri-task-line fs-18 align-middle me-1"></i>
                                <span>Tugas Saya</span>
                            </a>

                            <a href="{{ route('tasks.kanban') }}" class="dropdown-item">
                                <i class="ri-artboard-2-line fs-18 align-middle me-1 text-primary"></i>
                                <span>Papan Kanban</span>
                            </a>

                            <a href="{{ route('tasks.create') }}" class="dropdown-item">
                                <i class="ri-add-circle-line fs-18 align-middle me-1"></i>
                                <span>Tambah Tugas</span>
                            </a>

                            @if(Auth::user()->isAdmin())
                            <div class="dropdown-divider"></div>
                            <div class="dropdown-header noti-title py-1">
                                <span class="fs-11 text-uppercase text-muted fw-bold">Menu Administrator</span>
                            </div>
                            <a href="{{ route('admin.tasks') }}" class="dropdown-item">
                                <i class="ri-file-list-3-line fs-18 align-middle me-1 text-primary"></i>
                                <span>Semua Tugas Pegawai</span>
                            </a>
                            <a href="{{ route('admin.users') }}" class="dropdown-item">
                                <i class="ri-user-star-line fs-18 align-middle me-1 text-info"></i>
                                <span>Rekap Penggunaan</span>
                            </a>
                            @endif

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
        

        
        <div class="leftside-menu">

            
            <a href="{{ route('dashboard') }}" class="logo logo-light">
                <span class="logo-lg">
                    <img src="/admin/assets/images/logo.png" alt="logo">
                </span>
                <span class="logo-sm">
                    <img src="/admin/assets/images/logo-sm.png" alt="small logo">
                </span>
            </a>

            
            <a href="{{ route('dashboard') }}" class="logo logo-dark">
                <span class="logo-lg">
                    <img src="/admin/assets/images/logo-dark.png" alt="dark logo">
                </span>
                <span class="logo-sm">
                    <img src="/admin/assets/images/logo-sm.png" alt="small logo">
                </span>
            </a>

            
            <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Tampilkan Sidebar">
                <i class="ri-checkbox-blank-circle-line align-middle"></i>
            </div>

            
            <div class="button-close-fullsidebar">
                <i class="ri-close-fill align-middle"></i>
            </div>

            
            <div class="h-100" id="leftside-menu-container" data-simplebar>

                
                <ul class="side-nav">

                    <li class="side-nav-title">Menu Utama</li>

                    <li class="side-nav-item {{ request()->routeIs('dashboard') ? 'menuitem-active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="side-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="ri-dashboard-2-line"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>

                    <li class="side-nav-item {{ request()->routeIs('profile.edit') ? 'menuitem-active' : '' }}">
                        <a href="{{ route('profile.edit') }}" class="side-nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                            <i class="ri-user-3-line"></i>
                            <span> Profil Saya </span>
                        </a>
                    </li>

                    <li class="side-nav-title">Manajemen Tugas</li>

                    <li class="side-nav-item {{ request()->routeIs('tasks.index') ? 'menuitem-active' : '' }}">
                        <a href="{{ route('tasks.index') }}" class="side-nav-link {{ request()->routeIs('tasks.index') ? 'active' : '' }}">
                            <i class="ri-task-line"></i>
                            <span> Daftar Tugas </span>
                        </a>
                    </li>

                    <li class="side-nav-item {{ request()->routeIs('tasks.kanban') ? 'menuitem-active' : '' }}">
                        <a href="{{ route('tasks.kanban') }}" class="side-nav-link {{ request()->routeIs('tasks.kanban') ? 'active' : '' }}">
                            <i class="ri-artboard-2-line"></i>
                            <span> Papan Kanban </span>
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
                            <span> Kategori Tugas </span>
                        </a>
                    </li>

                    @if(Auth::user()->isAdmin())
                    <li class="side-nav-title">Menu Administrator</li>
                    <li class="side-nav-item {{ request()->routeIs('admin.tasks') ? 'menuitem-active' : '' }}">
                        <a href="{{ route('admin.tasks') }}" class="side-nav-link {{ request()->routeIs('admin.tasks') ? 'active' : '' }}">
                            <i class="ri-file-list-3-line"></i>
                            <span> Semua Tugas </span>
                        </a>
                    </li>
                    <li class="side-nav-item {{ request()->routeIs('admin.users') ? 'menuitem-active' : '' }}">
                        <a href="{{ route('admin.users') }}" class="side-nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                            <i class="ri-user-star-line"></i>
                            <span> Rekap Penggunaan </span>
                        </a>
                    </li>
                    @endif

                </ul>
                

                <div class="clearfix"></div>
            </div>
        </div>
        

        
        
        

        <div class="content-page">
            <div class="content">

                
                <div class="container-fluid">

                    
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

                    
                    @yield('content')

                </div> 

            </div> 

            
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
            

        </div>

        
        
        

    </div>
    

    
    <script src="/admin/assets/js/vendor.min.js"></script>

    
    <script src="/admin/assets/js/app.min.js"></script>

    <script>
        (function() {
            var savedTheme = localStorage.getItem('taskapp_theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            
            var modeToggle = document.getElementById('light-dark-mode');
            if (modeToggle) {
                var icon = modeToggle.querySelector('i');
                if (icon) {
                    icon.className = savedTheme === 'dark' ? 'ri-sun-line fs-22' : 'ri-moon-line fs-22';
                }

                modeToggle.addEventListener('click', function(e) {
                    var currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
                    var newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    
                    document.documentElement.setAttribute('data-bs-theme', newTheme);
                    localStorage.setItem('taskapp_theme', newTheme);
                    
                    if (icon) {
                        icon.className = newTheme === 'dark' ? 'ri-sun-line fs-22' : 'ri-moon-line fs-22';
                    }
                });
            }
        })();
    </script>

    @stack('scripts')
</body>

</html>
