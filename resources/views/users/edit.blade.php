<x-layout>
    <x-slot:title>Edit User - Monitoring Proyek Bulog</x-slot:title>
    <x-slot:page-title>Edit User - {{ $user->name }}</x-slot:page-title>

    <div class="container-fluid mt-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0 rounded-3">

                    <!-- Header Card -->
                    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                        <h3 class="card-title m-0">
                            <i class="bi bi-pencil-square me-2"></i> Edit User - {{ $user->name }}
                        </h3>
                        <a href="{{ route('users.index') }}" class="btn btn-dark btn-sm shadow-sm">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <!-- Body Card -->
                    <div class="card-body">
                        <form action="{{ route('users.update', $user->id) }}" method="POST" id="userForm">
                            @csrf
                            @method('PUT')

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
                                                   value="{{ old('name', $user->name) }}"
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
                                                   value="{{ old('email', $user->email) }}"
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
                                        <i class="bi bi-person-check me-2"></i>Status & Kontak
                                    </h5>

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
                                                   value="{{ old('phone', $user->phone) }}"
                                                   placeholder="Masukkan nomor telepon">
                                        </div>
                                        @error('phone')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Status Aktif -->
                                    <div class="mb-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   role="switch"
                                                   id="is_active"
                                                   name="is_active"
                                                   value="1"
                                                   {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="is_active">
                                                User Aktif
                                            </label>
                                        </div>
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Nonaktifkan jika user tidak boleh login
                                        </small>
                                    </div>

                                    <!-- Info Tambahan -->
                                    <div class="card bg-light border-0">
                                        <div class="card-body py-3">
                                            <h6 class="card-title text-primary mb-2">
                                                <i class="bi bi-clock-history me-1"></i>Informasi Sistem
                                            </h6>
                                            <div class="small">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="text-muted">Dibuat:</span>
                                                    <span class="fw-semibold">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Diupdate:</span>
                                                    <span class="fw-semibold">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                            </div>
                                        </div>
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
                                                  placeholder="Masukkan alamat lengkap">{{ old('address', $user->address) }}</textarea>
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
                                    <div>
                                        <a href="{{ route('users.index') }}" class="btn btn-secondary me-2">
                                            <i class="bi bi-x-circle me-1"></i> Batal
                                        </a>
                                        <a href="{{ route('users.change-password', $user->id) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-key me-1"></i> Ubah Password
                                        </a>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-warning text-dark">
                                            <i class="bi bi-check-circle me-1"></i> Update User
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Phone number formatting
        document.getElementById('phone').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9+-\s]/g, '');
        });

        // Form submission with loading state
        document.getElementById('userForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Memperbarui...';
            submitBtn.disabled = true;
        });

        // Show confirmation if changing own role
        document.getElementById('role').addEventListener('change', function() {
            @if($user->id === auth()->id())
                const newRole = this.value;
                const currentRole = '{{ $user->role }}';

                if (newRole !== currentRole) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Mengubah Role Sendiri',
                        text: 'Anda mengubah role akun sendiri. Pastikan ini adalah tindakan yang disengaja.',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'Ya, Saya Paham'
                    });
                }
            @endif
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
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        .bg-light {
            background-color: #f8f9fa !important;
        }
    </style>
</x-layout>
