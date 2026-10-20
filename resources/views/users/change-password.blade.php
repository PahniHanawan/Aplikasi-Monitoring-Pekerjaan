<x-layout>
    <x-slot:title>Ubah Password - Monitoring Proyek Bulog</x-slot:title>
    <x-slot:page-title>Ubah Password - {{ $user->name }}</x-slot:page-title>

    <div class="container-fluid mt-3">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-lg border-0 rounded-3">

                    <!-- Header Card -->
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h3 class="card-title m-0">
                            <i class="bi bi-key me-2"></i> Ubah Password - {{ $user->name }}
                        </h3>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-arrow-left"></i> Kembali ke Edit
                        </a>
                    </div>

                    <!-- Body Card -->
                    <div class="card-body">
                        <form action="{{ route('users.update-password', $user->id) }}" method="POST" id="passwordForm">
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

                            <!-- Informasi User -->
                            <div class="alert alert-primary">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-person-circle fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="alert-heading mb-1">Informasi User</h6>
                                        <div class="small">
                                            <strong>Nama:</strong> {{ $user->name }} |
                                            <strong>Email:</strong> {{ $user->email }} |
                                            <strong>Role:</strong> <span class="badge bg-{{ $user->getRoleBadgeColor() }}">{{ $user->getRoleLabel() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Password Baru -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">
                                    Password Baru <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-key text-primary"></i>
                                    </span>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           placeholder="Masukkan password baru"
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
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Password minimal 8 karakter dan mengandung huruf dan angka
                                </div>
                            </div>

                            <!-- Konfirmasi Password -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold">
                                    Konfirmasi Password Baru <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-key-fill text-primary"></i>
                                    </span>
                                    <input type="password"
                                           class="form-control @error('password_confirmation') is-invalid @enderror"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           placeholder="Konfirmasi password baru"
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

                            <!-- Password Strength Indicator -->
                            <div class="mb-4">
                                <div class="password-strength">
                                    <div class="progress mb-2" style="height: 5px;">
                                        <div class="progress-bar" id="passwordStrengthBar"
                                             role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <small class="text-muted" id="passwordStrengthText">
                                        Kekuatan password: -
                                    </small>
                                </div>
                            </div>

                            <!-- Warning Message -->
                            <div class="alert alert-warning">
                                <h6 class="alert-heading">
                                    <i class="bi bi-exclamation-triangle me-2"></i>Peringatan!
                                </h6>
                                <p class="mb-0 small">
                                    Setelah password diubah, user harus login kembali dengan password yang baru.
                                    @if($user->id === auth()->id())
                                        <strong class="text-danger">Anda akan logout otomatis setelah mengubah password sendiri.</strong>
                                    @endif
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-4">
                                <div class="col-12 d-flex justify-content-between">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-info text-white">
                                        <i class="bi bi-check-circle me-1"></i> Update Password
                                    </button>
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

        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrengthBar');
            const strengthText = document.getElementById('passwordStrengthText');

            let strength = 0;
            let text = '';
            let color = '';

            if (password.length >= 8) strength += 25;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 25;
            if (password.match(/\d/)) strength += 25;
            if (password.match(/[^a-zA-Z\d]/)) strength += 25;

            if (strength === 0) {
                text = 'Kosong';
                color = 'bg-secondary';
            } else if (strength <= 25) {
                text = 'Sangat Lemah';
                color = 'bg-danger';
            } else if (strength <= 50) {
                text = 'Lemah';
                color = 'bg-warning';
            } else if (strength <= 75) {
                text = 'Cukup';
                color = 'bg-info';
            } else {
                text = 'Kuat';
                color = 'bg-success';
            }

            strengthBar.style.width = strength + '%';
            strengthBar.className = 'progress-bar ' + color;
            strengthText.textContent = 'Kekuatan password: ' + text;
        });

        // Form validation
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
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

            if (password.length < 8) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Password Terlalu Pendek',
                    text: 'Password minimal harus 8 karakter!',
                    confirmButtonColor: '#dc3545'
                });
                return;
            }

            // Confirmation for self password change
            @if($user->id === auth()->id())
                e.preventDefault();
                Swal.fire({
                    title: 'Ubah Password Sendiri?',
                    text: "Anda akan logout otomatis setelah mengubah password. Pastikan Anda ingat password baru!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0dcaf0',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Ubah Password!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading and submit
                        const submitBtn = document.querySelector('button[type="submit"]');
                        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Mengubah...';
                        submitBtn.disabled = true;
                        document.getElementById('passwordForm').submit();
                    }
                });
            @else
                // For other users, just show loading
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Mengubah...';
                submitBtn.disabled = true;
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
        .card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .progress {
            background-color: #e9ecef;
        }
    </style>
</x-layout>
