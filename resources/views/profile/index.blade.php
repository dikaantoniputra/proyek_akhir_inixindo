@extends('layouts.app')

@section('title', 'Profil Pengguna')
@section('page-title', 'Profil Pengguna')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">TaskApp</a></li>
    <li class="breadcrumb-item active">Profil Pengguna</li>
@endsection

@section('content')
<div class="row">
    
    <div class="col-xl-4 col-lg-5">
        <div class="card text-center">
            <div class="card-body">
                <div class="avatar-lg mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fs-28 fw-bold shadow-sm" style="width: 80px; height: 80px;">
                    {{ $user->initials }}
                </div>

                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-2">{{ $user->email }}</p>

                <div class="mb-3">
                    <span class="badge {{ $user->isAdmin() ? 'bg-danger-subtle text-danger' : 'bg-primary-subtle text-primary' }} fs-12 px-3 py-1">
                        <i class="{{ $user->isAdmin() ? 'ri-shield-star-fill' : 'ri-user-3-fill' }} me-1"></i>
                        {{ ucfirst($user->role) }}
                    </span>
                </div>

                <div class="text-start border-top pt-3 mt-3">
                    <p class="text-muted mb-2 font-13">
                        <strong><i class="ri-calendar-line me-1 text-primary"></i> Bergabung Sejak :</strong>
                        <span class="ms-2">{{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}</span>
                    </p>
                    <p class="text-muted mb-2 font-13">
                        <strong><i class="ri-task-line me-1 text-info"></i> Total Tugas :</strong>
                        <span class="ms-2 fw-semibold">{{ $user->tasks()->count() }} Tugas</span>
                    </p>
                    <p class="text-muted mb-0 font-13">
                        <strong><i class="ri-checkbox-circle-line me-1 text-success"></i> Tugas Selesai :</strong>
                        <span class="ms-2 fw-semibold text-success">{{ $user->tasks()->where('status', 'selesai')->count() }} Tugas</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3 fs-15 text-uppercase fw-bold text-muted">
                    <i class="ri-shield-keyhole-line text-warning me-1"></i> Tips Keamanan
                </h5>
                <ul class="text-muted fs-13 ps-3 mb-0">
                    <li class="mb-2">Gunakan kombinasi minimal 6 karakter untuk kata sandi.</li>
                    <li class="mb-2">Jangan membagikan kata sandi Anda kepada orang lain.</li>
                    <li>Pastikan alamat email Anda aktif untuk keperluan konfirmasi.</li>
                </ul>
            </div>
        </div>
    </div>

    
    <div class="col-xl-8 col-lg-7">
        
        <div class="card mb-4">
            <div class="card-header bg-light border-bottom">
                <h5 class="card-title mb-0">
                    <i class="ri-user-settings-line me-1 text-primary"></i> Perbarui Informasi Profil
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-user-line"></i></span>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-mail-line"></i></span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header bg-light border-bottom">
                <h5 class="card-title mb-0">
                    <i class="ri-lock-password-line me-1 text-warning"></i> Ubah Kata Sandi
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-semibold">Kata Sandi Saat Ini <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-lock-unlock-line"></i></span>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" placeholder="Masukkan kata sandi lama" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-semibold">Kata Sandi Baru <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-key-line"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Minimal 6 karakter" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Kata Sandi Baru <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-check-double-line"></i></span>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi baru" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning">
                            <i class="ri-lock-line me-1"></i> Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
