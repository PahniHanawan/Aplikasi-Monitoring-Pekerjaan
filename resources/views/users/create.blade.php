<x-layout>
    <x-slot:title>Tambah User - Monitoring Proyek Bulog</x-slot:title>
    <x-slot:page-title>Tambah User Baru</x-slot:page-title>

    <div class="container-fluid mt-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0 rounded-3">

                    <!-- Header Card -->
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h3 class="card-title m-0">
                            <i class="bi bi-person-plus me-2"></i> Tambah User Baru
                        </h3>
                        <a href="{{ route('users.index') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <!-- Body Card -->
                    <div class="card-body">
                        <form action="{{ route('users.store') }}" method="POST" id="userForm">
                            @csrf

                            <!-- Alert Error -->
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <h5 class="alert-heading">
                                        <i class="bi bi-exclamation-triangle me-2"></i>Terjadi Kesalahan!
                                    </h5>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="row">
                                <!-- Informasi Dasar -->
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3 border-bottom pb-2">
                                        <i class="bi bi-info-circle me-2"></i>Informasi Dasar
                                    </h5>

                                    <!-- Nama Lengkap -->
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">
                                            Nama Lengkap <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-person text-primary"></i>
                                            </span>
                                            <input type="text"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   id="name"
                                                   name="name"
                                                   value="{{ old('name') }}"
                                                   placeholder="Masukkan nama lengkap"
                                                   required>
                                        </div>
                                        @error('name')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-semibold">
                                            Email <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-envelope text-primary"></i>
                                            </span>
                                            <input type="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   id="email"
                                                   name="email"
                                                   value="{{ old('email') }}"
                                                   placeholder="Masukkan alamat email"
                                                   required>
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

<!-- Role -->
<div class="mb-3">
    <label for="role" class="form-label fw-semibold">
        Role <span class="text-danger">*</span>
    </label>
    <div class="input-group">
        <span class="input-group-text bg-light">
            <i class="bi bi-shield-check text-primary"></i>
        </span>
        <select class="form-select @error('role') is-invalid @enderror"
                id="role"
                name="role"
                required>
            <option value="">Pilih Role</option>
            <option value="admin" {{ old('role', isset($user) ? $user->role : '') == 'admin' ? 'selected' : '' }}>
                Administrator
            </option>
            <option value="staff" {{ old('role', isset($user) ? $user->role : '') == 'staff' ? 'selected' : '' }}>
                Staff
            </option>
        </select>
    </div>
    @error('role')
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
    <small class="text-muted">
        <i class="bi bi-info-circle me-1"></i>
        Pilih role sesuai dengan wewenang user
    </small>
</div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3 border-bottom pb-2">
                                        <i class="bi bi-lock me-2"></i>Keamanan & Kontak
                                    </h5>

                                    <!-- Password -->
                                    <div class="mb-3">
                                        <label for="password" class="form-label fw-semibold">
                                            Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-key text-primary"></i>
                                            </span>
                                            <input type="password"
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   id="password"
                                                   name="password"
                                                   placeholder="Masukkan password"
                                                   required>
                                            <button type="button" class="btn btn-outline-secondary"
                                                    onclick="togglePassword('password')">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Konfirmasi Password -->
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label fw-semibold">
                                            Konfirmasi Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-key-fill text-primary"></i>
                                            </span>
                                            <input type="password"
                                                   class="form-control @error('password_confirmation') is-invalid @enderror"
                                                   id="password_confirmation"
                                                   name="password_confirmation"
                                                   placeholder="Konfirmasi password"
                                                   required>
                                            <button type="button" class="btn btn-outline-secondary"
                                                    onclick="togglePassword('password_confirmation')">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Telepon -->
                                    <div class="mb-3">
                                        <label for="phone" class="form-label fw-semibold">Telepon</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-telephone text-primary"></i>
                                            </span>
                                            <input type="text"
                                                   class="form-control @error('phone') is-invalid @enderror"
                                                   id="phone"
                                                   name="phone"
                                                   value="{{ old('phone') }}"
                                                   placeholder="Masukkan nomor telepon">
                                        </div>
                                        @error('phone')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Status Aktif -->
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   role="switch"
                                                   id="is_active"
                                                   name="is_active"
                                                   value="1"
                                                   {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="is_active">
                                                User Aktif
                                            </label>
                                        </div>
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Nonaktifkan jika user tidak boleh login
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Alamat -->
                            <div class="row mt-2">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3 border-bottom pb-2">
                                        <i class="bi bi-geo-alt me-2"></i>Alamat
                                    </h5>
                                    <div class="mb-3">
                                        <label for="address" class="form-label fw-semibold">Alamat Lengkap</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror"
                                                  id="address"
                                                  name="address"
                                                  rows="3"
                                                  placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

<!-- Informasi Role -->
<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-info">
            <h6 class="alert-heading">
                <i class="bi bi-info-circle me-2"></i>Informasi Role:
            </h6>
            <div class="row small">
                <div class="col-md-6">
                    <span class="badge bg-danger me-1">Administrator</span>
                    <span class="text-muted">- Akses penuh sistem</span>
                </div>
                <div class="col-md-6">
                    <span class="badge bg-info me-1">Staff</span>
                    <span class="text-muted">- Input dan kelola data proyek</span>
                </div>
            </div>
        </div>
    </div>
</div>

                            <!-- Action Buttons -->
                            <div class="row mt-4">
                                <div class="col-12 d-flex justify-content-between">
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i> Simpan User
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert & Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.parentNode.querySelector('i');

            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                field.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

        // Form submission confirmation
        document.getElementById('userForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Password Tidak Sesuai',
                    text: 'Password dan konfirmasi password harus sama!',
                    confirmButtonColor: '#dc3545'
                });
                return;
            }

            // Optional: Add loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Menyimpan...';
            submitBtn.disabled = true;
        });

        // Phone number formatting
        document.getElementById('phone').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9+-\s]/g, '');
        });
    </script>

    <style>
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        .input-group-text {
            border-right: none;
        }
        .form-control:focus + .input-group-text {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .card-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</x-layout>
