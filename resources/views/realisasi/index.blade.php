<x-layout>
    <x-slot:title>{{ $title ?? 'Daftar Realisasi' }}</x-slot:title>

    <div class="container-fluid mt-3">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title m-0">
                    <i class="bi bi-graph-up-arrow me-2"></i> Data Realisasi
                </h3>
                <div>
                    <a href="{{ route('realisasi.create') }}" class="btn btn-light btn-sm shadow-sm">
                        <i class="bi bi-plus-circle"></i> Tambah Realisasi
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Alert Messages -->
                @if (session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: "{{ session('success') }}",
                                timer: 2500,
                                showConfirmButton: false
                            });
                        });
                    </script>
                @endif

                @if (session('error'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: "{{ session('error') }}",
                                timer: 3000,
                                showConfirmButton: false
                            });
                        });
                    </script>
                @endif

                <!-- Search Section -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-search text-muted"></i>
                                            </span>
                                            <input type="text"
                                                   class="form-control border-start-0"
                                                   id="search-input"
                                                   placeholder="Cari data realisasi... (No. SPK, Nama Proyek, Kantor, Mitra, dll)"
                                                   aria-label="Search">
                                            <button class="btn btn-outline-secondary" type="button" id="clear-search">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted mt-1 d-block">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Cari berdasarkan: No. SPK, Nama Proyek, Kantor, Mitra, Nilai, Progress, dll.
                                        </small>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button id="reset-filters" class="btn btn-outline-secondary btn-sm">
                                                <i class="bi bi-arrow-clockwise me-1"></i> Reset All
                                            </button>
                                            <button id="search-btn" class="btn btn-primary btn-sm">
                                                <i class="bi bi-search me-1"></i> Cari
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Filters Indicator -->
                <div id="active-filters" class="alert alert-info alert-dismissible fade show mb-4" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span id="filters-text"></span>
                        <button type="button" class="btn-close" onclick="$('#active-filters').hide()"></button>
                    </div>
                </div>

                <!-- Statistik Ringkas -->
                <div class="row mb-4" id="stats-cards">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="mb-0" id="total-data">{{ $totalData ?? 0 }}</h4>
                                        <small>Total Realisasi</small>
                                    </div>
                                    <div class="opacity-75">
                                        <i class="bi bi-list-check fs-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="mb-0" id="total-nilai">Rp {{ number_format($totalRealisasi ?? 0, 0, ',', '.') }}</h4>
                                        <small>Total Nilai Realisasi</small>
                                    </div>
                                    <div class="opacity-75">
                                        <i class="bi bi-currency-dollar fs-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="mb-0" id="proyek-selesai">{{ $proyekSelesai ?? 0 }}</h4>
                                        <small>Proyek Selesai</small>
                                    </div>
                                    <div class="opacity-75">
                                        <i class="bi bi-check-circle fs-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="mb-0" id="proyek-berjalan">{{ $proyekBerjalan ?? 0 }}</h4>
                                        <small>Proyek Berjalan</small>
                                    </div>
                                    <div class="opacity-75">
                                        <i class="bi bi-clock-history fs-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Filter Status</label>
                        <select class="form-select" id="status-filter">
                            <option value="">Semua Status</option>
                            <option value="selesai">Selesai (100%)</option>
                            <option value="berjalan">Sedang Berjalan</option>
                            <option value="belum">Belum Mulai</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Filter Kantor</label>
                        <select class="form-select" id="kantor-filter">
                            <option value="">Semua Kantor</option>
                            @foreach($kantorList as $id => $nama)
                                <option value="{{ $id }}">{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Filter Bulan</label>
                        <select class="form-select" id="bulan-filter">
                            <option value="">Semua Bulan</option>
                            @foreach($bulanList as $key => $bulan)
                                <option value="{{ $key }}">{{ $bulan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Filter Tahun</label>
                        <select class="form-select" id="tahun-filter">
                            <option value="">Semua Tahun</option>
                            @foreach($tahunList as $tahun)
                                <option value="{{ $tahun }}">{{ $tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Results Info -->
                <div id="results-info" class="alert alert-light border mb-3" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span id="results-text"></span>
                        <span id="results-count" class="badge bg-primary"></span>
                    </div>
                </div>

                <!-- Loading Indicator -->
                <div id="loading-indicator" class="text-center mb-3" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data...</p>
                </div>

                <!-- Tabel Data Realisasi -->
                <div id="table-container">
                    @include('realisasi.partials.table', ['realisasis' => $realisasis])
                </div>

                <!-- Pagination -->
                <div id="pagination-container" class="mt-4">
                    {{ $realisasis->links() }}
                </div>

            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            let searchTimeout;

            // Filter functionality dengan AJAX
            function applyFilters(pageUrl = null) {
                const status = $('#status-filter').val();
                const kantor = $('#kantor-filter').val();
                const bulan = $('#bulan-filter').val();
                const tahun = $('#tahun-filter').val();
                const search = $('#search-input').val();

                // Show loading
                $('#loading-indicator').show();
                $('#table-container').hide();
                $('#pagination-container').hide();
                $('#results-info').hide();

                // Update active filters display
                updateActiveFilters(search);

                const data = {
                    status: status,
                    kantor_id: kantor,
                    bulan: bulan,
                    tahun: tahun,
                    search: search
                };

                let url = '{{ route("realisasi.index") }}';
                if (pageUrl) {
                    url = pageUrl;
                }

                // AJAX request
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        if (!response.success) {
                            throw new Error(response.message || 'Unknown error');
                        }

                        $('#table-container').html(response.html);
                        $('#pagination-container').html(response.pagination || '');
                        updateStatistics(response.stats);
                        updateResultsInfo(response.search_count || response.stats.totalData, search);

                        // Scroll to top of table
                        $('html, body').animate({
                            scrollTop: $('#table-container').offset().top - 100
                        }, 500);

                        $('#loading-indicator').hide();
                        $('#table-container').show();
                        $('#pagination-container').show();
                        $('#results-info').show();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        $('#loading-indicator').hide();
                        $('#table-container').show();
                        $('#pagination-container').show();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat data: ' + error
                        });
                    }
                });
            }

            function updateStatistics(stats) {
                $('#total-data').text(stats.totalData || 0);
                $('#total-nilai').text('Rp ' + formatNumber(stats.totalRealisasi || 0));
                $('#proyek-selesai').text(stats.proyekSelesai || 0);
                $('#proyek-berjalan').text(stats.proyekBerjalan || 0);
            }

            function updateActiveFilters(search) {
                const activeFilters = [];

                if (search) {
                    activeFilters.push('Pencarian: "' + search + '"');
                }
                if ($('#status-filter').val()) {
                    activeFilters.push('Status: ' + $('#status-filter option:selected').text());
                }
                if ($('#kantor-filter').val()) {
                    activeFilters.push('Kantor: ' + $('#kantor-filter option:selected').text());
                }
                if ($('#bulan-filter').val()) {
                    activeFilters.push('Bulan: ' + $('#bulan-filter option:selected').text());
                }
                if ($('#tahun-filter').val()) {
                    activeFilters.push('Tahun: ' + $('#tahun-filter').val());
                }

                if (activeFilters.length > 0) {
                    $('#active-filters').show();
                    $('#filters-text').html('<strong>Filter Aktif:</strong> ' + activeFilters.join(' | '));
                } else {
                    $('#active-filters').hide();
                }
            }

            function updateResultsInfo(total, search) {
                if (search) {
                    $('#results-text').text('Hasil pencarian untuk: "' + search + '"');
                    $('#results-count').text(total + ' data ditemukan');
                    $('#results-info').show();
                } else {
                    $('#results-info').hide();
                }
            }

            function formatNumber(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }

            // Event listeners for filters
            $('#status-filter, #kantor-filter, #bulan-filter, #tahun-filter').on('change', function() {
                applyFilters();
            });

            // Search functionality
            $('#search-input').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    if ($('#search-input').val().length >= 3 || $('#search-input').val().length === 0) {
                        applyFilters();
                    }
                }, 500);
            });

            $('#search-btn').on('click', function() {
                applyFilters();
            });

            $('#search-input').on('keypress', function(e) {
                if (e.which === 13) {
                    applyFilters();
                }
            });

            $('#clear-search').on('click', function() {
                $('#search-input').val('');
                applyFilters();
            });

            // Reset filters
            $('#reset-filters').on('click', function() {
                $('#status-filter, #kantor-filter, #bulan-filter, #tahun-filter').val('');
                $('#search-input').val('');
                applyFilters();
            });

            // Handle pagination clicks
            $(document).on('click', '#pagination-container .pagination a, .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                applyFilters(url);
            });

            // initial state
            updateStatistics({
                totalRealisasi: {{ $totalRealisasi ?? 0 }},
                totalData: {{ $totalData ?? 0 }},
                proyekSelesai: {{ $proyekSelesai ?? 0 }},
                proyekBerjalan: {{ $proyekBerjalan ?? 0 }}
            });
        });

        function confirmDelete(id, nomorSpk) {
            Swal.fire({
                title: "Konfirmasi Hapus",
                html: `Apakah Anda yakin ingin menghapus realisasi untuk kontrak <strong>${nomorSpk}</strong>?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>

    <style>
        .card {
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .table-highlight {
            background-color: #d1e7dd !important;
            transition: background-color 0.7s ease;
        }
        .progress {
            border-radius: 10px;
            overflow: hidden;
        }
        .progress-bar {
            transition: width 0.6s ease;
        }
        .badge {
            font-size: 0.75em;
        }
        .btn-group-sm > .btn {
            padding: 0.25rem 0.5rem;
        }
        #active-filters {
            border-left: 4px solid #0dcaf0;
        }
        #results-info {
            border-left: 4px solid #198754;
        }
        .pagination {
            margin-bottom: 0;
        }
        #search-input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        /* Improved table scrolling */
        .table-responsive {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            max-height: 600px;
        }

        .table-responsive::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Sticky header styling */
        .sticky-top {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Table row hover effect */
        #realisasiTable tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05) !important;
            transition: background-color 0.2s ease-in-out;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .table-responsive {
                max-height: 400px;
                font-size: 0.875rem;
            }

            .btn-group-sm > .btn {
                padding: 0.2rem 0.4rem;
                font-size: 0.75rem;
            }
        }
    </style>
</x-layout>
