<x-layout>
    <x-slot:title>{{ $title ?? 'Kelengkapan Dokumen Proyek' }}</x-slot:title>

    <div class="container-fluid py-4">
        <!-- Header dengan Filter -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header p-3 pb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">
                                    <i class="fas fa-file-contract me-2 text-primary"></i>
                                    Kelengkapan Dokumen Proyek
                                </h5>
                                <p class="text-sm text-muted mb-0">
                                    Kelola dokumen proyek yang telah mencapai progress 100%
                                </p>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="input-group input-group-sm" style="width: 250px;">
                                    <span class="input-group-text bg-transparent">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Cari proyek..." id="searchInput">
                                </div>
                                <button class="btn btn-sm btn-outline-primary" id="filterBtn">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Proyek Selesai
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $proyeks->count() }}</div>
                                <div class="text-xs text-muted mt-1">
                                    <i class="fas fa-clock me-1"></i>Progress 100%
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-folder-open fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Butuh Dokumen
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-warning">{{ $proyekButuhDokumen->count() }}</div>
                                <div class="text-xs text-muted mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>Perlu perhatian
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-file-excel fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Dokumen Lengkap
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-success">{{ $proyekLengkap->count() }}</div>
                                <div class="text-xs text-muted mt-1">
                                    <i class="fas fa-check-circle me-1"></i>Siap arsip
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-file-check fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Dokumen
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-info">
                                    {{ $proyeks->sum(function($proyek) { return $proyek->dokumen->count(); }) }}
                                </div>
                                <div class="text-xs text-muted mt-1">
                                    <i class="fas fa-files me-1"></i>Semua proyek
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-archive fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proyek yang Butuh Dokumen -->
        @if($proyekButuhDokumen->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header bg-warning bg-opacity-10 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-25 p-2 rounded me-3">
                            <i class="fas fa-exclamation-triangle text-warning fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="m-0 font-weight-bold text-warning">
                                Proyek yang Butuh Kelengkapan Dokumen
                            </h6>
                            <small class="text-muted">
                                {{ $proyekButuhDokumen->count() }} proyek membutuhkan perhatian
                            </small>
                        </div>
                    </div>
                    <span class="badge bg-warning text-dark">
                        <i class="fas fa-clock me-1"></i>Prioritas
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tableButuhDokumen">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Proyek & Deskripsi</th>
                                <th class="d-none d-lg-table-cell">Kantor</th>
                                <th>Progress</th>
                                <th>Status Dokumen</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proyekButuhDokumen as $proyek)
                            @php
                                $dokumenStatus = $proyek->dokumen_status;
                                $totalDokumen = count($dokumenStatus);
                                $dokumenUploaded = count(array_filter($dokumenStatus));
                                $progressPercentage = ($dokumenUploaded/$totalDokumen)*100;
                            @endphp
                            <tr class="border-start border-warning border-4">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                            <i class="fas fa-project-diagram text-warning"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 text-dark">{{ $proyek->nama_proyek }}</h6>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-info-circle me-1"></i>
                                                {{ Str::limit($proyek->deskripsi, 60) ?: 'Tidak ada deskripsi' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-building text-muted me-2"></i>
                                        <span class="text-dark">{{ $proyek->kantor->nama_kantor }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress-wrapper me-3" style="width: 80px;">
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                     style="width: {{ $proyek->progress_terendah }}%;"
                                                     aria-valuenow="{{ $proyek->progress_terendah }}"
                                                     aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                        <small class="text-success fw-bold">{{ $proyek->progress_terendah }}%</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3 text-center">
                                            <span class="badge bg-warning text-dark fs-7">
                                                {{ $dokumenUploaded }}/{{ $totalDokumen }}
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-warning" role="progressbar"
                                                     style="width: {{ $progressPercentage }}%;"
                                                     aria-valuenow="{{ $dokumenUploaded }}"
                                                     aria-valuemin="0" aria-valuemax="{{ $totalDokumen }}">
                                                </div>
                                            </div>
                                            <small class="text-muted d-block mt-1">
                                                {{ number_format($progressPercentage, 1) }}% lengkap
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('proyek.dokumen.create', $proyek) }}"
                                           class="btn btn-primary"
                                           data-bs-toggle="tooltip"
                                           title="Upload Dokumen">
                                            <i class="fas fa-upload"></i>
                                            <span class="d-none d-md-inline ms-1">Upload</span>
                                        </a>
                                        <a href="{{ route('dokumen.show', $proyek)}}
                                           class="btn btn-outline-info"
                                           data-bs-toggle="tooltip"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Proyek dengan Dokumen Lengkap -->
        @if($proyekLengkap->count() > 0)
        <div class="card shadow">
            <div class="card-header bg-success bg-opacity-10 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-25 p-2 rounded me-3">
                            <i class="fas fa-check-circle text-success fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="m-0 font-weight-bold text-success">
                                Proyek dengan Dokumen Lengkap
                            </h6>
                            <small class="text-muted">
                                {{ $proyekLengkap->count() }} proyek telah lengkap
                            </small>
                        </div>
                    </div>
                    <span class="badge bg-success">
                        <i class="fas fa-check me-1"></i>Siap Arsip
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tableLengkap">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Proyek & Deskripsi</th>
                                <th class="d-none d-lg-table-cell">Kantor</th>
                                <th>Dokumen</th>
                                <th class="d-none d-xl-table-cell">Terakhir Update</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proyekLengkap as $proyek)
                            @php
                                $lastDokumen = $proyek->dokumen->sortByDesc('tanggal_upload')->first();
                                $totalDokumen = $proyek->dokumen->count();
                            @endphp
                            <tr class="border-start border-success border-4">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                            <i class="fas fa-project-diagram text-success"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 text-dark">{{ $proyek->nama_proyek }}</h6>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-info-circle me-1"></i>
                                                {{ Str::limit($proyek->deskripsi, 60) ?: 'Tidak ada deskripsi' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-building text-muted me-2"></i>
                                        <span class="text-dark">{{ $proyek->kantor->nama_kantor }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                            <i class="fas fa-file-check text-success"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-success d-block">{{ $totalDokumen }} dokumen</span>
                                            <small class="text-muted">Lengkap</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="d-none d-xl-table-cell">
                                    @if($lastDokumen)
                                    <div class="text-center">
                                        <span class="fw-bold text-dark d-block">
                                            {{ $lastDokumen->tanggal_upload->format('d/m/Y') }}
                                        </span>
                                        <small class="text-muted">
                                            {{ $lastDokumen->tanggal_upload->format('H:i') }}
                                        </small>
                                    </div>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('dokumen.show', $proyek)}}"
                                           class="btn btn-info"
                                           data-bs-toggle="tooltip"
                                           title="Lihat Dokumen">
                                            <i class="fas fa-eye"></i>
                                            <span class="d-none d-md-inline ms-1">Detail</span>
                                        </a>
                                        <a href="{{ route('proyek.dokumen.create', $proyek)}}"
                                           class="btn btn-outline-secondary"
                                           data-bs-toggle="tooltip"
                                           title="Kelola Dokumen">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Empty State -->
        @if($proyeks->count() === 0)
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-folder-open fa-4x text-secondary mb-4"></i>
                    </div>
                    <h4 class="text-secondary mb-3">Belum Ada Proyek yang Selesai</h4>
                    <p class="text-muted mb-4">
                        Proyek akan muncul di sini ketika semua kontrak mencapai progress 100%<br>
                        baik dalam aspek fisik maupun pembayaran.
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('proyek.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Proyek
                        </a>
                        <a href="{{ route('realisasi.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-chart-line me-2"></i>Lihat Progress
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();

                    // Search in both tables
                    ['tableButuhDokumen', 'tableLengkap'].forEach(tableId => {
                        const table = document.getElementById(tableId);
                        if (table) {
                            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

                            for (let row of rows) {
                                const text = row.textContent.toLowerCase();
                                if (text.includes(searchTerm)) {
                                    row.style.display = '';
                                } else {
                                    row.style.display = 'none';
                                }
                            }
                        }
                    });
                });
            }

            // Filter button functionality
            const filterBtn = document.getElementById('filterBtn');
            if (filterBtn) {
                filterBtn.addEventListener('click', function() {
                    // Implement filter functionality here
                    alert('Fitur filter akan segera tersedia!');
                });
            }
        });
    </script>

    <style>
        .card {
            border: none;
            border-radius: 0.75rem;
        }

        .card-header {
            border-bottom: 1px solid #e3e6f0;
            border-radius: 0.75rem 0.75rem 0 0 !important;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6e707e;
        }

        .table td {
            vertical-align: middle;
            padding: 1rem 0.75rem;
        }

        .progress {
            border-radius: 0.375rem;
        }

        .progress-bar {
            border-radius: 0.375rem;
        }

        .border-start {
            border-left-width: 4px !important;
        }

        .btn-group .btn {
            border-radius: 0.375rem;
        }

        .empty-state {
            max-width: 500px;
            margin: 0 auto;
        }

        .empty-state-icon {
            opacity: 0.7;
        }

        @media (max-width: 768px) {
            .card-header .d-flex {
                flex-direction: column;
                gap: 1rem;
            }

            .btn-group .btn span {
                display: none;
            }

            .table-responsive {
                font-size: 0.875rem;
            }
        }

        .fs-7 {
            font-size: 0.75rem !important;
        }

        .bg-opacity-10 {
            background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
        }
    </style>
</x-layout>
