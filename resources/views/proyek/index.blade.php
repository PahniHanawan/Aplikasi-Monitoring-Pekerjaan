<x-layout>
    <x-slot:title>{{ $title ?? 'Daftar Proyek' }}</x-slot:title>

    <div class="container-fluid mt-3">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body py-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                                        <i class="fas fa-tasks text-primary fa-2x"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 text-dark">Manajemen Proyek</h4>
                                        <p class="text-muted mb-0">Kelola semua proyek dalam satu tempat</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <a href="{{ route('proyek.create') }}" class="btn btn-primary px-3 py-2">
                                    <i class="fas fa-plus me-2"></i>Tambah Proyek
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Proyek
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $proyeks->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-briefcase fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Nilai Kontrak
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($proyeks->sum('total_nilai_kontrak'), 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Proyek Selesai
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $proyeks->where('progress_terendah', '>=', 100)->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Proyek Berjalan
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $proyeks->where('progress_terendah', '<', 100)->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-sync-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="card shadow-sm border-0">
            <!-- Card Header -->
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title m-0 text-dark me-3">
                            <i class="fas fa-list me-2 text-primary"></i>Daftar Proyek
                        </h5>
                        <span class="badge bg-primary bg-opacity-10 text-primary py-2">
                            <i class="fas fa-database me-1"></i>{{ $proyeks->count() }} Proyek
                        </span>
                    </div>

                    <!-- Search and Filter -->
                    <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text"
                                   id="searchInput"
                                   class="form-control border-start-0"
                                   placeholder="Cari proyek...">
                        </div>
                        <button class="btn btn-outline-secondary btn-sm" onclick="toggleFilter()">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="row mt-3 d-none" id="filterSection">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Status</label>
                        <select class="form-select form-select-sm" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="selesai">Selesai</option>
                            <option value="berjalan">Berjalan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Kantor</label>
                        <select class="form-select form-select-sm" id="kantorFilter">
                            <option value="">Semua Kantor</option>
                            @foreach($proyeks->pluck('kantor')->unique() as $kantor)
                                @if($kantor)
                                    <option value="{{ $kantor->id }}">{{ $kantor->nama_kantor }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Izin Prinsip</label>
                        <select class="form-select form-select-sm" id="izinFilter">
                            <option value="">Semua</option>
                            <option value="ada">Ada</option>
                            <option value="tidak">Tidak Ada</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="d-flex gap-2 w-100">
                            <button class="btn btn-primary btn-sm flex-fill" onclick="applyFilter()">
                                <i class="fas fa-check me-1"></i>Terapkan
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="resetFilter()">
                                <i class="fas fa-refresh me-1"></i>Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body p-0">
                <!-- Alert Success -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show m-3 mb-0" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Table Container -->
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width: 50px;">#</th>
                                    <th>Proyek</th>
                                    <th style="width: 120px;">Kantor</th>
                                    <th style="width: 100px;" class="text-center">Progress</th>
                                    <th style="width: 100px;" class="text-center">Kontrak</th>
                                    <th style="width: 150px;" class="text-center">Total Nilai</th>
                                    <th style="width: 100px;" class="text-center">Izin</th>
                                    <th style="width: 120px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @foreach ($proyeks as $key => $p)
                                    @php
                                        $progress = $p->progress_terendah;
                                        $progressColor = $progress >= 100 ? 'success' : ($progress >= 80 ? 'warning' : ($progress >= 50 ? 'info' : 'danger'));
                                        $isSelesai = $progress >= 100;
                                    @endphp
                                    <tr class="proyek-row"
                                        data-nama="{{ strtolower($p->nama_proyek) }}"
                                        data-kantor="{{ strtolower($p->kantor->nama_kantor ?? '') }}"
                                        data-deskripsi="{{ strtolower($p->deskripsi ?? '') }}"
                                        data-status="{{ $isSelesai ? 'selesai' : 'berjalan' }}"
                                        data-kantor-id="{{ $p->kantor->id ?? '' }}"
                                        data-izin="{{ $p->izin_prinsip ? 'ada' : 'tidak' }}">
                                        <td class="ps-4">
                                            <div class="fw-medium text-muted">{{ $key + 1 }}</div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-start">
                                                <div class="flex-shrink-0">
                                                    <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                                        <i class="fas fa-briefcase text-primary"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 text-dark">{{ $p->nama_proyek }}</h6>
                                                    @if($p->deskripsi)
                                                        <p class="text-muted small mb-1">{{ Str::limit($p->deskripsi, 60) }}</p>
                                                    @endif
                                                    <div class="d-flex align-items-center gap-2">
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar me-1"></i>
                                                            {{ $p->created_at->format('d M Y') }}
                                                        </small>
                                                        @if($p->kontraks->count() > 0)
                                                            <small class="text-muted">
                                                                <i class="fas fa-file-contract me-1"></i>
                                                                {{ $p->kontraks->count() }} bagian
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                <i class="fas fa-building me-1 text-muted"></i>
                                                {{ $p->kantor->nama_kantor ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="progress-wrapper">
                                                <div class="progress" style="height: 6px; width: 80px; margin: 0 auto 4px;">
                                                    <div class="progress-bar bg-{{ $progressColor }}"
                                                         style="width: {{ min($progress, 100) }}%"
                                                         title="{{ $progress }}%">
                                                    </div>
                                                </div>
                                                <small class="fw-bold text-{{ $progressColor }}">
                                                    {{ $progress }}%
                                                </small>
                                                @if($isSelesai)
                                                    <div class="mt-1">
                                                        <small class="badge bg-success bg-opacity-10 text-success">
                                                            <i class="fas fa-check me-1"></i>Selesai
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info bg-opacity-10 text-info border-0 py-2">
                                                {{ $p->kontraks->count() }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="fw-bold text-primary">
                                                Rp {{ number_format($p->total_nilai_kontrak, 0, ',', '.') }}
                                            </div>
                                            @if($p->kontraks->count() > 0)
                                                <small class="text-muted">
                                                    {{ number_format($p->total_nilai_kontrak / $p->kontraks->count(), 0, ',', '.') }}/bagian
                                                </small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($p->izin_prinsip)
                                                <span class="badge bg-success bg-opacity-10 text-success border-0 py-2">
                                                    <i class="fas fa-check me-1"></i>Ada
                                                </span>
                                            @if($p->izin_prinsip_file)
                                                <div class="mt-1">
                                                    <a href="{{ route('proyek.download-izin', $p->id) }}"
                                                    target="_blank"
                                                    class="btn btn-sm btn-outline-primary border-0 p-1"
                                                    title="Lihat File">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            @endif
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border-0 py-2">
                                                    <i class="fas fa-times me-1"></i>Tidak
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('proyek.show', $p->id) }}"
                                                   class="btn btn-outline-info border-0"
                                                   data-bs-toggle="tooltip"
                                                   title="Detail Proyek">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('proyek.edit', $p->id) }}"
                                                   class="btn btn-outline-warning border-0"
                                                   data-bs-toggle="tooltip"
                                                   title="Edit Proyek">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-outline-danger border-0"
                                                        onclick="confirmDelete({{ $p->id }}, '{{ $p->nama_proyek }}')"
                                                        data-bs-toggle="tooltip"
                                                        title="Hapus Proyek">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <form id="delete-form-{{ $p->id }}"
                                                  action="{{ route('proyek.destroy', $p->id) }}"
                                                  method="POST"
                                                  style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State -->
                    <div id="emptyState" class="text-center py-5 d-none">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Data tidak ditemukan</h5>
                        <p class="text-muted mb-4">Coba ubah kata kunci pencarian atau filter</p>
                        <button class="btn btn-primary" onclick="resetFilter()">
                            <i class="fas fa-refresh me-2"></i>Reset Pencarian
                        </button>
                    </div>
                </div>

                <!-- Results Count -->
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted" id="resultCount">
                            Menampilkan {{ $proyeks->count() }} dari {{ $proyeks->count() }} proyek
                        </small>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Total nilai kontrak: Rp {{ number_format($proyeks->sum('total_nilai_kontrak'), 0, ',', '.') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const resultCount = document.getElementById('resultCount');
            const tableBody = document.getElementById('tableBody');
            const emptyState = document.getElementById('emptyState');
            const proyekRows = document.querySelectorAll('.proyek-row');
            const totalData = proyekRows.length;

            function performSearch() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                let visibleCount = 0;

                proyekRows.forEach(row => {
                    const nama = row.dataset.nama;
                    const kantor = row.dataset.kantor;
                    const deskripsi = row.dataset.deskripsi;

                    const isVisible = nama.includes(searchTerm) ||
                                    kantor.includes(searchTerm) ||
                                    deskripsi.includes(searchTerm);

                    row.style.display = isVisible ? '' : 'none';
                    if (isVisible) visibleCount++;
                });

                updateDisplay(visibleCount);
            }

            function applyFilter() {
                const status = document.getElementById('statusFilter').value;
                const kantor = document.getElementById('kantorFilter').value;
                const izin = document.getElementById('izinFilter').value;
                const searchTerm = searchInput.value.toLowerCase().trim();

                let visibleCount = 0;

                proyekRows.forEach(row => {
                    const rowStatus = row.dataset.status;
                    const rowKantor = row.dataset.kantorId;
                    const rowIzin = row.dataset.izin;
                    const nama = row.dataset.nama;
                    const kantorName = row.dataset.kantor;
                    const deskripsi = row.dataset.deskripsi;

                    const statusMatch = !status || rowStatus === status;
                    const kantorMatch = !kantor || rowKantor === kantor;
                    const izinMatch = !izin || rowIzin === izin;
                    const searchMatch = !searchTerm ||
                                      nama.includes(searchTerm) ||
                                      kantorName.includes(searchTerm) ||
                                      deskripsi.includes(searchTerm);

                    const isVisible = statusMatch && kantorMatch && izinMatch && searchMatch;
                    row.style.display = isVisible ? '' : 'none';
                    if (isVisible) visibleCount++;
                });

                updateDisplay(visibleCount);
            }

            function updateDisplay(visibleCount) {
                if (visibleCount === 0) {
                    tableBody.style.display = 'none';
                    emptyState.style.display = 'block';
                    resultCount.textContent = `Menampilkan 0 dari ${totalData} proyek`;
                } else {
                    tableBody.style.display = '';
                    emptyState.style.display = 'none';
                    resultCount.textContent = `Menampilkan ${visibleCount} dari ${totalData} proyek`;
                }
            }

            function toggleFilter() {
                const filterSection = document.getElementById('filterSection');
                filterSection.classList.toggle('d-none');
            }

            function resetFilter() {
                document.getElementById('statusFilter').value = '';
                document.getElementById('kantorFilter').value = '';
                document.getElementById('izinFilter').value = '';
                searchInput.value = '';
                applyFilter();

                const filterSection = document.getElementById('filterSection');
                filterSection.classList.add('d-none');
            }

            // Event listeners
            searchInput.addEventListener('input', applyFilter);
            document.getElementById('statusFilter').addEventListener('change', applyFilter);
            document.getElementById('kantorFilter').addEventListener('change', applyFilter);
            document.getElementById('izinFilter').addEventListener('change', applyFilter);
        });

        function confirmDelete(id, namaProyek) {
            Swal.fire({
                title: "Hapus Proyek?",
                html: `Anda akan menghapus proyek <strong>"${namaProyek}"</strong>.<br>Data yang dihapus tidak dapat dikembalikan.`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>

    <style>
        .card {
            border: 1px solid #e3e6f0;
            border-radius: 0.5rem;
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }

        .table {
            margin-bottom: 0;
            font-size: 0.875rem;
        }

        .table th {
            background-color: #f8f9fc;
            border-bottom: 2px solid #e3e6f0;
            font-weight: 600;
            padding: 1rem 0.75rem;
            font-size: 0.8rem;
            color: #6c757d;
        }

        .table td {
            padding: 1rem 0.75rem;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .progress {
            border-radius: 10px;
        }

        .progress-bar {
            border-radius: 10px;
        }

        .badge {
            font-size: 0.75rem;
            font-weight: 500;
        }

        .btn-group .btn {
            padding: 0.375rem 0.5rem;
        }

        .btn-group .btn:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }

        .border-left-primary {
            border-left: 4px solid #007bff !important;
        }

        .border-left-success {
            border-left: 4px solid #28a745 !important;
        }

        .border-left-info {
            border-left: 4px solid #17a2b8 !important;
        }

        .border-left-warning {
            border-left: 4px solid #ffc107 !important;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }

            .table-responsive {
                font-size: 0.8rem;
            }

            .btn-group .btn {
                padding: 0.25rem 0.375rem;
            }

            .statistics-cards .col-md-6 {
                margin-bottom: 1rem;
            }
        }

        .table-container {
            min-height: 400px;
        }

        #emptyState {
            background-color: #f8f9fa;
            border-radius: 0 0 0.5rem 0.5rem;
        }
    </style>
</x-layout>
