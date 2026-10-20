<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!-- Sidebar Brand -->
    <div class="sidebar-brand text-center py-4 bg-primary">
        <a href="{{ route('dashboard') }}" class="brand-link d-flex flex-column align-items-center justify-content-center text-decoration-none">
            <img src="{{ asset('assets/img/bulog-logo.png') }}"
                 alt="Bulog Logo"
                 class="brand-image shadow rounded-circle mb-2"
                 style="max-height:70px; background:white; padding:8px;" />
            <span class="brand-text fw-bold text-white fs-5">Monitoring Proyek Bulog</span>
        </a>
    </div>

    <!-- Sidebar Wrapper -->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!-- Sidebar Menu -->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Master Data -->
                <li class="nav-header text-uppercase small fw-bold text-muted mt-3 mb-1">Master Data</li>

                <li class="nav-item">
                    <a href="{{ route('kantor.index') }}"
                        class="nav-link {{ request()->is('kantor*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building"></i>
                        <p>Kantor</p>
                        <span class="badge bg-info float-end">{{ \App\Models\Kantor::count() }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('proyek.index') }}"
                        class="nav-link {{ request()->is('proyek*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-project-diagram"></i>
                        <p>Proyek</p>
                        <span class="badge bg-success float-end">{{ \App\Models\Proyek::count() }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('kontrak.index') }}"
                        class="nav-link {{ request()->is('kontrak*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-contract"></i>
                        <p>Kontrak</p>
                        <span class="badge bg-warning float-end">{{ \App\Models\Kontrak::count() }}</span>
                    </a>
                </li>

                <!-- Keuangan & Realisasi -->
                <li class="nav-header text-uppercase small fw-bold text-muted mt-3 mb-1">Keuangan & Realisasi</li>

                <!-- Biaya Menu dengan Submenu -->
                <li class="nav-item {{ request()->is('biaya*') ? 'menu-is-opening menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('biaya*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>
                            Biaya
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('biaya.index') }}"
                               class="nav-link {{ request()->routeIs('biaya.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Biaya</p>
                                <span class="badge bg-primary float-end">{{ \App\Models\Biaya::count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('biaya.create') }}"
                               class="nav-link {{ request()->routeIs('biaya.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tambah Biaya</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Realisasi Menu dengan Submenu -->
                <li class="nav-item {{ request()->is('realisasi*') ? 'menu-is-opening menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('realisasi*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>
                            Realisasi
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('realisasi.index') }}"
                               class="nav-link {{ request()->routeIs('realisasi.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Realisasi</p>
                                <span class="badge bg-info float-end">{{ \App\Models\Realisasi::count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('realisasi.create') }}"
                               class="nav-link {{ request()->routeIs('realisasi.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tambah Realisasi</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Kelengkapan Dokumen -->
                <li class="nav-header text-uppercase small fw-bold text-muted mt-3 mb-1">Dokumen Proyek</li>

                <li class="nav-item">
                    <a href="{{ route('dokumen.index') }}" class="nav-link {{ request()->is('dokumen') && !request()->is('dokumen/create/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Kelengkapan Dokumen</p>
                        @php
                        $totalProyekButuhDokumen = \App\Models\Proyek::whereHas('kontraks.biayas', function($query) {
                            $query->where('progress', '>=', 100);
                        })->whereHas('kontraks.realisasis', function($query) {
                            $query->where('progress_persen', '>=', 100);
                        })->get()->filter(function($proyek) {
                            return !$proyek->dokumen_lengkap;
                        })->count();
                        @endphp
                        @if($totalProyekButuhDokumen > 0)
                        <span class="badge bg-danger float-end">{{ $totalProyekButuhDokumen }}</span>
                        @endif
                    </a>
                </li>
                @php
                $totalProyekButuhDokumen = \App\Models\Proyek::butuhDokumen()->count();
                @endphp
                <!-- Utilities -->
                <li class="nav-header text-uppercase small fw-bold text-muted mt-3 mb-1">Utilities</li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Pengaturan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Manajemen User</p>
                    </a>
                </li>

                <!-- Logout dengan Modal -->
                <li class="nav-item">
                    <a href="#" onclick="confirmLogout(event);" class="nav-link text-danger">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Log Out</p>
                    </a>
                </li>

            </ul>
            <!-- End Sidebar Menu -->
        </nav>
    </div>
</aside>

<!-- Modal Konfirmasi Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-warning" id="logoutModalLabel">
                    <i class="fas fa-sign-out-alt me-2"></i>Konfirmasi Logout
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-question-circle text-warning display-4"></i>
                </div>
                <p class="text-center mb-0">Apakah Anda yakin ingin keluar dari sistem?</p>
                <div class="mt-3 p-3 bg-light rounded">
                    <small class="text-muted d-block text-center fst-italic">
                        <i class="fas fa-quote-left me-1 text-primary"></i>
                        <span id="kataSemangat">"Terus semangat dan pantang menyerah!"</span>
                        <i class="fas fa-quote-right ms-1 text-primary"></i>
                    </small>
                    <small class="text-primary d-block text-center mt-2 fw-bold" id="kataHariIni">
                        #HariPenuhSemangat
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Batal
                </button>
                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-sign-out-alt me-1"></i>Ya, Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Daftar kata-kata semangat
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

// Daftar kata-kata hari ini
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

function confirmLogout(event) {
    event.preventDefault();

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

// Auto activate sidebar based on current page
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.sidebar-menu .nav-link');

    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');

            // Activate parent dropdown
            const parentDropdown = link.closest('.nav-treeview');
            if (parentDropdown) {
                const parentItem = parentDropdown.closest('.nav-item');
                parentItem.classList.add('menu-is-opening', 'menu-open');

                const parentLink = parentItem.querySelector('> .nav-link');
                if (parentLink) {
                    parentLink.classList.add('active');
                }
            }
        }
    });
});
</script>

<style>
    .app-sidebar {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
    }

    .sidebar-brand {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .nav-header {
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }

    .nav-link {
        border-radius: 0.5rem;
        margin: 0.125rem 0.5rem;
        transition: all 0.3s ease;
    }

    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateX(5px);
    }

    .nav-link.active {
        background-color: #3498db;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .nav-treeview .nav-link {
        margin: 0.125rem 0;
        padding-left: 2rem;
    }

    .nav-treeview .nav-link.active {
        background-color: rgba(52, 152, 219, 0.3);
        border-left: 3px solid #3498db;
    }

    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }

    .brand-image {
        transition: transform 0.3s ease;
    }

    .brand-link:hover .brand-image {
        transform: scale(1.05);
    }

    /* Modal logout custom styling */
    #logoutModal .modal-header {
        background-color: #fff3cd;
        border-bottom: 1px solid #ffeaa7;
    }

    #logoutModal .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000;
    }

    #logoutModal .btn-warning:hover {
        background-color: #e0a800;
        border-color: #e0a800;
    }

    /* Styling untuk kata semangat */
    #kataSemangat {
        font-size: 0.9rem;
        line-height: 1.4;
    }

    #kataHariIni {
        font-size: 0.8rem;
    }

    .bg-light {
        background-color: #f8f9fa !important;
        border-left: 4px solid #3498db;
    }

    /* Animation for dropdown */
    .nav-item.menu-is-opening .nav-treeview {
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
