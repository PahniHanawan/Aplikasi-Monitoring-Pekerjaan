<aside class="app-sidebar bg-dark shadow" data-bs-theme="dark">
    <!-- Sidebar Brand -->
    <div class="sidebar-brand text-center py-3 bg-primary bg-gradient">
        <a href="{{ route('dashboard') }}" class="brand-link d-flex flex-column align-items-center justify-content-center text-decoration-none py-2">
            <img src="{{ asset('assets/img/bulog-logo.png') }}"
                 alt="Bulog Logo"
                 class="brand-image shadow rounded-circle mb-2"
                 style="height: 60px; width: 60px; object-fit: contain; background: white; padding: 6px;" />
            <span class="brand-text fw-bold text-white fs-6 mt-1">Monitoring Proyek Bulog</span>
        </a>
    </div>

    <!-- Sidebar Wrapper -->
    <div class="sidebar-wrapper">
        <nav class="sidebar-nav">
            <!-- Sidebar Menu -->
            <ul class="nav sidebar-menu flex-column">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>

                <!-- Master Data -->
                <li class="nav-header">
                    <span class="nav-header-text">Master Data</span>
                </li>

                <li class="nav-item">
                    <a href="{{ route('kantor.index') }}"
                        class="nav-link {{ request()->is('kantor*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-building"></i>
                        <span class="nav-text">Kantor</span>
                        <span class="badge bg-info ms-auto">{{ \App\Models\Kantor::count() }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('proyek.index') }}"
                        class="nav-link {{ request()->is('proyek*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-diagram-3"></i>
                        <span class="nav-text">Proyek</span>
                        <span class="badge bg-success ms-auto">{{ \App\Models\Proyek::count() }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('kontrak.index') }}"
                        class="nav-link {{ request()->is('kontrak*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-file-earmark-text"></i>
                        <span class="nav-text">Kontrak</span>
                        <span class="badge bg-warning ms-auto">{{ \App\Models\Kontrak::count() }}</span>
                    </a>
                </li>

                <!-- Keuangan & Realisasi -->
                <li class="nav-header">
                    <span class="nav-header-text">Keuangan & Realisasi</span>
                </li>
                <li class="nav-item">
                    <a href="{{ route('realisasi.index') }}"
                        class="nav-link {{ request()->is('realisasi*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-graph-up"></i>
                        <span class="nav-text">Data Realisasi</span>
                        <span class="badge bg-info ms-auto">{{ \App\Models\Realisasi::count() }}</span>
                    </a>
                </li>

                <!-- Kelengkapan Dokumen -->
                <li class="nav-header">
                    <span class="nav-header-text">Dokumen Proyek</span>
                </li>

                <li class="nav-item">
                    <a href="{{ route('dokumen.index') }}"
                       class="nav-link {{ request()->is('dokumen*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-folder-check"></i>
                        <span class="nav-text">Kelengkapan Dokumen</span>
                        @php
                        $totalProyekButuhDokumen = \App\Models\Proyek::butuhDokumen()->count();
                        @endphp
                        @if($totalProyekButuhDokumen > 0)
                        <span class="badge bg-danger ms-auto">{{ $totalProyekButuhDokumen }}</span>
                        @endif
                    </a>
                </li>

                <!-- Utilities -->
                <li class="nav-header">
                    <span class="nav-header-text">Utilities</span>
                </li>

                {{-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-gear"></i>
                        <span class="nav-text">Pengaturan</span>
                    </a>
                </li> --}}
                @if(Auth::user()->isAdmin())
                <li class="nav-item">
                    <a href="{{ route('users.index') }}"
                    class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-people"></i>
                    <span class="nav-text">Manajemen User</span>
                    @php
                    $totalUsers = \App\Models\User::count();
                    @endphp
                    <span class="badge bg-primary ms-auto">{{ $totalUsers }}</span>
                </a>
                </li>
                @endif
                <!-- Logout -->
                <li class="nav-item logout-item">
                    <!-- Hidden Logout Form -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" onclick="confirmLogout(event)" class="nav-link text-danger">
                        <i class="nav-icon bi bi-box-arrow-right"></i>
                        <span class="nav-text">Log Out</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger" id="logoutModalLabel">
                    <i class="bi bi-box-arrow-right me-2"></i>Konfirmasi Logout
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="bi bi-question-circle text-warning" style="font-size: 4rem;"></i>
                </div>
                <h5 class="mb-3">Apakah Anda yakin ingin logout?</h5>
                <p class="text-muted mb-0">Anda akan keluar dari sistem Monitoring Proyek Bulog</p>

                <!-- Motivational Messages -->
                <div class="mt-4 p-3 bg-light rounded">
                    <small class="text-primary fw-bold d-block mb-1" id="kataSemangat"></small>
                    <small class="text-muted" id="kataHariIni"></small>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-danger px-4" onclick="performLogout()">
                    <i class="bi bi-box-arrow-right me-1"></i>Ya, Logout
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.app-sidebar {
    background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%) !important;
    width: 280px;
    display: flex;
    flex-direction: column;
    border-right: 1px solid rgba(255, 255, 255, 0.1);
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1030;
}

.sidebar-brand {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding: 1rem;
    flex-shrink: 0;
}

.brand-image {
    transition: all 0.3s ease;
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.brand-link:hover .brand-image {
    transform: scale(1.05);
    border-color: rgba(255, 255, 255, 0.4);
}

.brand-text {
    font-size: 0.9rem;
    line-height: 1.2;
}

.sidebar-wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-height: 0;
}

.sidebar-nav {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.sidebar-menu {
    flex: 1;
    padding: 0.5rem 0.75rem;
    overflow-y: auto;
    overflow-x: hidden;
    max-height: 100%;
}

.nav-header {
    padding: 1rem 0.5rem 0.5rem;
    margin-top: 0.5rem;
}

.nav-header:first-child {
    margin-top: 0;
}

.nav-header-text {
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #94a3b8;
    display: block;
}

.nav-item {
    margin-bottom: 0.125rem;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    color: #cbd5e1;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

.nav-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 3px;
    background: #3b82f6;
    transform: scaleY(0);
    transition: transform 0.2s ease;
}

.nav-link:hover {
    background: rgba(255, 255, 255, 0.05);
    color: #ffffff;
    transform: translateX(4px);
}

.nav-link:hover::before {
    transform: scaleY(1);
}

.nav-link.active {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.nav-link.active::before {
    transform: scaleY(1);
    background: #ffffff;
}

.nav-icon {
    font-size: 1.1rem;
    width: 24px;
    margin-right: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nav-text {
    flex: 1;
    font-size: 0.9rem;
    font-weight: 500;
}

.badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
    font-weight: 600;
    min-width: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Custom scrollbar styling for sidebar */
.sidebar-menu::-webkit-scrollbar {
    width: 6px;
}

.sidebar-menu::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 3px;
}

.sidebar-menu::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 3px;
}

.sidebar-menu::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Firefox scrollbar */
.sidebar-menu {
    scrollbar-width: thin;
    scrollbar-color: rgba(255, 255, 255, 0.2) rgba(255, 255, 255, 0.05);
}

/* Logout item styling */
.logout-item {
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

/* Mobile responsive */
@media (max-width: 768px) {
    .app-sidebar {
        width: 100%;
        transform: translateX(-100%);
        z-index: 1040;
    }

    .sidebar-mobile-open .app-sidebar {
        transform: translateX(0);
    }

    .sidebar-menu {
        padding: 0.5rem 1rem;
    }
}

/* Ensure main content adjusts */
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

/* Mobile header adjustment */
@media (max-width: 768px) {
    .app-main {
        margin-left: 0;
    }

    .app-header {
        left: 0 !important;
    }
}
</style>

<script>
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

function performLogout() {
    // Show loading in modal
    const logoutBtn = document.querySelector('#logoutModal .btn-danger');
    const originalText = logoutBtn.innerHTML;
    logoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Logging out...';
    logoutBtn.disabled = true;

    // Submit the logout form
    document.getElementById('logout-form').submit();
}
</script>
