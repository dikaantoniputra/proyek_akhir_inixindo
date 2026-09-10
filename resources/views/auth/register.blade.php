@extends('layouts.auth')

@section('title', 'Daftar Akun Baru')

@section('content')
    <h4 class="mt-0">Daftar Akun Baru</h4>
    <p class="text-muted mb-4">Buat akun untuk mulai mengelola dan memantau tugas-tugas Anda dengan mudah.</p>

    
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="fullname" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input class="form-control @error('name') is-invalid @enderror" type="text" id="fullname" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap Anda" required autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="emailaddress" class="form-label">Alamat Email <span class="text-danger">*</span></label>
            <input class="form-control @error('email') is-invalid @enderror" type="email" id="emailaddress" name="email" value="{{ old('email') }}" required placeholder="Masukkan alamat email Anda">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Kata Sandi <span class="text-danger">*</span></label>
            <div class="input-group input-group-merge">
                <input class="form-control @error('password') is-invalid @enderror" type="password" id="password" name="password" required placeholder="Minimal 6 karakter">
                <div class="input-group-text" data-password="false" style="cursor: pointer;" title="Tampilkan / Sembunyikan Kata Sandi">
                    <i class="ri-eye-line password-eye-icon"></i>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
            <div class="input-group input-group-merge">
                <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ketik ulang kata sandi">
                <div class="input-group-text" data-password="false" style="cursor: pointer;" title="Tampilkan / Sembunyikan Kata Sandi">
                    <i class="ri-eye-line password-eye-icon"></i>
                </div>
            </div>
        </div>
        <div class="d-grid mb-0 text-center">
            <button class="btn btn-primary" type="submit"><i class="ri-user-add-line me-1"></i> Daftar Sekarang</button>
        </div>
    </form>
    

    
    <footer class="footer footer-alt mt-4">
        <p class="text-muted">Sudah memiliki akun? <a href="{{ route('login') }}" class="text-primary fw-semibold ms-1">Masuk ke akun Anda</a></p>
    </footer>
@endsection
