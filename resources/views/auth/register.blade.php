<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin - BULOG</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/img/bulog-logo.png') }}" />
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bulog-red: #0000FF;
            --bulog-dark-red: #0000FF;
            --bulog-gold: #FFD700;
            --bulog-dark: #2D3748;
            --bulog-light: #F7FAFC;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--bulog-light) 0%, #E2E8F0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }

        .auth-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        .auth-header {
            background: linear-gradient(135deg, var(--bulog-red) 0%, var(--bulog-dark-red) 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .auth-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M0 0h40v40H0V0zm1 1h38v38H1V1z'/%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .bulog-logo {
            background: white;
            border-radius: 15px;
            padding: 1rem;
            display: inline-block;
            margin-bottom: 1.5rem;
        }

        .bulog-logo h4 {
            color: var(--bulog-red);
            font-weight: 800;
            margin: 0;
        }

        .bulog-logo small {
            color: var(--bulog-dark);
            font-weight: 600;
            font-size: 0.75rem;
        }

        .auth-body {
            padding: 2.5rem 2rem;
        }

        .auth-title {
            color: var(--bulog-dark);
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
        }

        .auth-subtitle {
            color: #718096;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }

        .btn-bulog {
            background: linear-gradient(135deg, var(--bulog-red) 0%, var(--bulog-dark-red) 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-bulog:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(227, 24, 55, 0.3);
            color: white;
        }

        .form-control {
            border: 2px solid #E2E8F0;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--bulog-red);
            box-shadow: 0 0 0 3px rgba(227, 24, 55, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: var(--bulog-dark);
            margin-bottom: 0.5rem;
        }

        .login-link {
            color: var(--bulog-red);
            text-decoration: none;
            font-weight: 600;
        }

        .login-link:hover {
            color: var(--bulog-dark-red);
            text-decoration: underline;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #E2E8F0;
            border-right: none;
        }

        .input-group .form-control {
            border-left: none;
        }

        .input-group .form-control:focus {
            border-left: none;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <div class="bulog-logo">
                <h4>BULOG</h4>
                <small>Monitoring Proyek</small>
            </div>
            <h3 class="fw-bold mb-2">Sistem Monitoring Proyek</h3>
            <p class="mb-0 opacity-75">PT. Badan Urusan Logistik - Perum BULOG</p>
        </div>

        <div class="auth-body">
            <h2 class="auth-title">Register Admin</h2>
            <p class="auth-subtitle">Buat akun administrator baru</p>

            <!-- Flash Messages -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user text-muted"></i>
                        </span>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}"
                               placeholder="masukkan nama lengkap" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope text-muted"></i>
                        </span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}"
                               placeholder="masukkan email admin" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock text-muted"></i>
                        </span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password"
                               placeholder="buat password (min. 6 karakter)" required>
                        <button type="button" class="input-group-text"
                                onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="password-toggle-icon"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock text-muted"></i>
                        </span>
                        <input type="password" class="form-control"
                               id="password_confirmation" name="password_confirmation"
                               placeholder="ulangi password Anda" required>
                        <button type="button" class="input-group-text"
                                onclick="togglePassword('password_confirmation')">
                            <i class="fas fa-eye" id="password-confirm-toggle-icon"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <button type="submit" class="btn btn-bulog py-2">
                        <i class="fas fa-user-plus me-2"></i>Daftar sebagai Admin
                    </button>
                </div>

                <div class="text-center">
                    <p class="mb-0">Sudah punya akun?
                        <a href="{{ route('login') }}" class="login-link">Login di sini</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-toggle-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Auto dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>
