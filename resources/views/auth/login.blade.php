<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BULOG</title>
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
            padding: 2rem 2rem;
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

        .logo-container {
            margin-bottom: 1.5rem;
        }

        .bulog-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            background: white;
            border-radius: 50%;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .bulog-logo img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .logo-text {
            text-align: center;
        }

        .logo-text h4 {
            color: white;
            font-weight: 800;
            margin: 0;
            font-size: 1.5rem;
        }

        .logo-text small {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            font-size: 0.8rem;
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
            box-shadow: 0 10px 25px rgba(0, 0, 255, 0.3);
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
            box-shadow: 0 0 0 3px rgba(0, 0, 255, 0.1);
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

        /* Fallback jika logo tidak ditemukan */
        .logo-fallback {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #0000FF, #0000CC);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <div class="logo-container">
                <div class="bulog-logo">
                    <!-- Logo BULOG -->
                    <img src="{{ asset('assets/img/bulog-logo.png') }}"
                         alt="BULOG Logo"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="logo-fallback" style="display: none;">BULOG</div>
                </div>
                <div class="logo-text">
                    <h4>BULOG</h4>
                    <small>Monitoring Proyek</small>
                </div>
            </div>
            <h5 class="fw-bold mb-2">Sistem Monitoring Proyek</h5>
            <p class="mb-0 opacity-75">PT. Badan Urusan Logistik - Perum BULOG</p>
        </div>

        <div class="auth-body">
            <h2 class="auth-title">Login</h2>
            <p class="auth-subtitle">Silakan login untuk mengakses sistem</p>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope text-muted"></i>
                        </span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}"
                               placeholder="masukkan email" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock text-muted"></i>
                        </span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password"
                               placeholder="masukkan password" required>
                        <button type="button" class="input-group-text"
                                onclick="togglePassword()">
                            <i class="fas fa-eye" id="password-toggle-icon"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <button type="submit" class="btn btn-bulog py-2">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                </div>

                {{-- <div class="text-center">
                    <p class="mb-0">Belum punya akun admin?
                        <a href="{{ route('register') }}" class="login-link">Daftar di sini</a>
                    </p>
                </div> --}}
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('password-toggle-icon');

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

        // Handle logo loading error
        document.addEventListener('DOMContentLoaded', function() {
            const logo = document.querySelector('.bulog-logo img');
            const fallback = document.querySelector('.logo-fallback');

            if (logo) {
                logo.onerror = function() {
                    this.style.display = 'none';
                    if (fallback) fallback.style.display = 'flex';
                };

                // Check if image loaded successfully
                if (logo.complete && logo.naturalHeight === 0) {
                    logo.style.display = 'none';
                    if (fallback) fallback.style.display = 'flex';
                }
            }
        });
    </script>
</body>
</html>
