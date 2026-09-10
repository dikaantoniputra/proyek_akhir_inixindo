@extends('layouts.auth')

@section('title', 'Masuk ke Aplikasi')

@section('content')
    <h4 class="mt-0">Masuk ke Akun Anda</h4>
    <p class="text-muted mb-4">Silakan masukkan email dan kata sandi untuk mengakses TaskApp.</p>

    <!-- form -->
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="emailaddress" class="form-label">Alamat Email <span class="text-danger">*</span></label>
            <input class="form-control @error('email') is-invalid @enderror" type="email" id="emailaddress" name="email" value="{{ old('email') }}" required placeholder="nama@domain.com" autocomplete="email" autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Kata Sandi <span class="text-danger">*</span></label>
            <input class="form-control @error('password') is-invalid @enderror" type="password" id="password" name="password" required placeholder="Masukkan kata sandi">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="checkbox-signin" name="remember" value="1">
                <label class="form-check-label" for="checkbox-signin">Ingat saya di perangkat ini</label>
            </div>
        </div>
        <div class="d-grid mb-0 text-center">
            <button class="btn btn-primary" type="submit"><i class="ri-login-box-line me-1"></i> Masuk</button>
        </div>
    </form>
    <!-- end form-->

    <!-- Demo Account Helpers -->
    <div class="mt-4 p-3 bg-light rounded border">
        <h6 class="fs-12 fw-bold text-muted text-uppercase mb-2"><i class="ri-shield-keyhole-line me-1"></i> Akun Uji Coba (Demo 1-Klik):</h6>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-sm btn-outline-primary" onclick="fillCredentials('budi@example.com', 'password')">
                <i class="ri-user-line me-1"></i> Budi (User)
            </button>
            <button type="button" class="btn btn-sm btn-outline-info" onclick="fillCredentials('siti@example.com', 'password')">
                <i class="ri-user-line me-1"></i> Siti (User)
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="fillCredentials('admin@example.com', 'password')">
                <i class="ri-shield-user-line me-1"></i> Admin
            </button>
        </div>
        <small class="text-muted d-block mt-2 fs-11">Password bawaan: <code>password</code></small>
    </div>

    <!-- Footer-->
    <footer class="footer footer-alt mt-4">
        <p class="text-muted">Belum memiliki akun? <a href="{{ route('register') }}" class="text-primary fw-semibold ms-1">Daftar di sini</a></p>
    </footer>
@endsection

@push('scripts')
<script>
    function fillCredentials(email, password) {
        document.getElementById('emailaddress').value = email;
        document.getElementById('password').value = password;
    }
</script>
@endpush
