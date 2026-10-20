<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Monitoring Proyek Bulog' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/bulog-logo.png') }}">

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Custom Styles -->
    <style>
        .app-content-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #dee2e6;
            padding: 1.5rem 0;
            margin-bottom: 1.5rem;
        }

        .breadcrumb {
            background: transparent;
            margin-bottom: 0;
            padding: 0;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            color: #6c757d;
        }

        .breadcrumb-item.active {
            color: #495057;
            font-weight: 500;
        }

        .alert {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .app-content {
            min-height: calc(100vh - 200px);
        }

        /* Sidebar Styling */
        .app-sidebar {
            width: 280px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%) !important;
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 1030;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .app-main {
            margin-left: 280px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .sidebar-collapsed .app-sidebar {
            transform: translateX(-100%);
        }

        .sidebar-collapsed .app-main {
            margin-left: 0;
        }

        /* Navbar Styling */
        .app-header {
            position: fixed;
            top: 0;
            right: 0;
            left: 280px;
            z-index: 1020;
            transition: left 0.3s ease;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .sidebar-collapsed .app-header {
            left: 0;
        }

        /* Padding untuk konten agar tidak tertutup navbar */
        .app-content-header {
            padding-top: 5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .app-sidebar {
                transform: translateX(-100%);
                width: 100%;
                z-index: 1040;
            }

            .app-main {
                margin-left: 0;
            }

            .app-header {
                left: 0 !important;
            }

            .sidebar-mobile-open .app-sidebar {
                transform: translateX(0);
            }

            .app-content-header {
                padding: 1rem 0;
                padding-top: 4rem;
            }
        }

        /* Select2 Customization */
        .select2-container .select2-selection--single {
            height: 45px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 45px;
            padding-left: 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 45px;
        }

        /* Loading state */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }
    </style>

    {{ $styles ?? '' }}
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!-- App Wrapper -->
    <div class="app-wrapper">
        <!-- Sidebar -->
        <x-layouts.sidebar />

        <!-- Main Wrapper -->
        <div class="main-wrapper">
            <!-- Header -->
            <x-layouts.navbar />

            <!-- Main Content -->
            <main class="app-main">
                <!-- Content Header -->
                <div class="app-content-header">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h3 class="mb-0">{{ $pageTitle ?? 'Dashboard' }}</h3>
                                @if(isset($pageSubtitle))
                                    <small class="text-muted">{{ $pageSubtitle }}</small>
                                @endif
                            </div>

                            <!-- Breadcrumb -->
                            <div class="col-sm-6">
                                <x-layouts.breadcrumb />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="app-content">
                    <div class="container-fluid">
                        <!-- Flash Messages -->
                        <x-layouts.flash-messages />

                        <!-- Page Content -->
                        {{ $slot }}
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <x-layouts.footer />
        </div>
    </div>

<!-- 1. jQuery -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

<!-- 2. Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- 3. Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- 4. SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- 5. DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <!-- Custom Scripts -->
    <script>
        // Debug: Cek apakah semua library terload dengan benar
        console.log('=== DEBUG LIBRARY LOADING ===');
        console.log('jQuery loaded:', typeof jQuery !== 'undefined');
        console.log('Bootstrap loaded:', typeof bootstrap !== 'undefined');
        console.log('Select2 loaded:', typeof $.fn.select2 !== 'undefined');
        console.log('SweetAlert2 loaded:', typeof Swal !== 'undefined');

        // Fungsi untuk menunggu sampai semua library siap
        function initializeApplication() {
            // Cek ketersediaan library
            if (typeof jQuery === 'undefined') {
                console.error('❌ jQuery gagal load!');
                return;
            }

            if (typeof bootstrap === 'undefined') {
                console.error('❌ Bootstrap gagal load!');
                return;
            }

            console.log('✅ Semua library berhasil di-load');

            // Initialize Bootstrap components
            try {
                var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
                var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                    return new bootstrap.Dropdown(dropdownToggleEl);
                });
                console.log('✅ Bootstrap dropdowns initialized');
            } catch (error) {
                console.error('❌ Error initializing Bootstrap dropdowns:', error);
            }

            // Sidebar toggle functionality
            const sidebarToggle = document.querySelector('[data-bs-toggle="sidebar"]');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.body.classList.toggle('sidebar-collapsed');
                    console.log('Sidebar toggled');
                });
            }

            // Mobile sidebar toggle
            const mobileSidebarToggle = document.querySelector('[data-bs-toggle="mobile-sidebar"]');
            if (mobileSidebarToggle) {
                mobileSidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.body.classList.toggle('sidebar-mobile-open');
                    console.log('Mobile sidebar toggled');
                });
            }

            // Initialize Select2 jika tersedia
            if (typeof $.fn.select2 !== 'undefined') {
                try {
                    // Inisialisasi Select2 untuk elemen dengan class .select2
                    $('.select2').select2({
                        placeholder: "-- Pilih --",
                        allowClear: true,
                        width: '100%',
                        language: {
                            noResults: function() {
                                return "Tidak ditemukan data yang sesuai";
                            },
                            searching: function() {
                                return "Mencari...";
                            }
                        }
                    });
                    console.log('✅ Select2 initialized successfully');
                } catch (error) {
                    console.error('❌ Error initializing Select2:', error);
                }
            } else {
                console.warn('⚠️ Select2 tidak tersedia, menggunakan dropdown standar');
            }

            // Auto dismiss alerts setelah 5 detik
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    try {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    } catch (error) {
                        console.warn('Cannot auto-dismiss alert:', error);
                    }
                });
            }, 5000);

            // Sidebar active state
            const currentPath = window.location.pathname;
            document.querySelectorAll('.sidebar-menu .nav-link').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });

            // Close mobile sidebar ketika klik link (untuk mobile)
            document.querySelectorAll('.sidebar-menu .nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        document.body.classList.remove('sidebar-mobile-open');
                    }
                });
            });

            // Update waktu real-time
            function updateTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                });
                const timeElement = document.getElementById('current-time');
                if (timeElement) {
                    timeElement.textContent = timeString;
                }
            }

            // Jalankan update waktu
            updateTime();
            setInterval(updateTime, 60000);

            console.log('✅ Application initialized successfully');
        }

        // Tunggu sampai DOM siap
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeApplication);
        } else {
            initializeApplication();
        }

        // Logout confirmation function
        function confirmLogout(event) {
            event.preventDefault();

            const kataSemangat = [
                "Terus semangat dan pantang menyerah!",
                "Setiap hari adalah kesempatan baru untuk berkembang!",
                "Kesuksesan dimulai dengan niat dan diakhiri dengan tindakan!",
                "Jangan menyerah, proses tidak akan mengkhianati hasil!",
                "Hari ini adalah kanvas kosong, lukislah dengan karya terbaikmu!",
                "Semangat adalah bahan bakar untuk mencapai impian!",
                "Tetap konsisten, hasil akan mengikuti!",
                "Setiap langkah kecil membawa pada pencapaian besar!",
                "Optimis adalah kunci menghadapi tantangan!",
                "Kegagalan adalah batu loncatan menuju kesuksesan!"
            ];

            const kataHariIni = [
                "#HariPenuhSemangat",
                "#MomentumTerbaik",
                "#EnergiPositif",
                "#ProgressTakTerhenti",
                "#AchievementDay",
                "#ProductivityBoost",
                "#SuccessMindset",
                "#GrowthJourney",
                "#PositiveVibes",
                "#DreamBig"
            ];

            // Generate random kata semangat dan kata hari ini
            const randomSemangat = kataSemangat[Math.floor(Math.random() * kataSemangat.length)];
            const randomHariIni = kataHariIni[Math.floor(Math.random() * kataHariIni.length)];

            // Update teks di modal
            document.getElementById('kataSemangat').textContent = randomSemangat;
            document.getElementById('kataHariIni').textContent = randomHariIni;

            // Tampilkan modal konfirmasi
            const logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
            logoutModal.show();
        }

        // Handle form submission dengan loading state
        document.getElementById('logout-form')?.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Logging out...';
            submitBtn.disabled = true;
        });

        // Global error handler untuk AJAX
        $(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
            console.error('AJAX Error:', {
                status: jqXHR.status,
                statusText: jqXHR.statusText,
                responseText: jqXHR.responseText,
                thrownError: thrownError
            });
        });

        // Global loading indicator
        $(document).ajaxStart(function() {
            $('body').addClass('loading');
        }).ajaxStop(function() {
            $('body').removeClass('loading');
        });

        // Utility function untuk format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        // Export utility functions ke global scope
        window.App = {
            formatCurrency: formatCurrency,
            showLoading: function() { $('body').addClass('loading'); },
            hideLoading: function() { $('body').removeClass('loading'); }
        };

        console.log('🚀 Application ready!');
    </script>

    {{ $scripts ?? '' }}
</body>
</html>
