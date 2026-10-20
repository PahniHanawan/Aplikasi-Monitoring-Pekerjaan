<x-layout>
    <x-slot:title>{{ $title ?? 'Daftar Kontrak' }}</x-slot:title>

    <div class="container-fluid mt-3">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-primary fw-bold mb-1">
                    <i class="bi bi-file-earmark-text me-2"></i> Data Kontrak
                </h2>
                <div class="text-muted">
                    <span id="kontrakCount">{{ $kontraks->count() }}</span> kontrak ditemukan
                </div>
            </div>
            <a href="{{ route('kontrak.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i> Kontrak Baru
            </a>
        </div>

        {{-- <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-check-circle-fill fs-4"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        {!! session('success') !!}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        {!! session('error') !!}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif --}}

        <!-- Filter Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label small fw-medium mb-1">Tahun</label>
                        <select class="form-select form-select-sm" id="tahunFilter">
                            <option value="">Semua Tahun</option>
                            @php
                                $tahunList = $kontraks->map(function($kontrak) {
                                    return \Carbon\Carbon::parse($kontrak->tanggal)->year;
                                })->unique()->sortDesc();
                            @endphp
                            @foreach($tahunList as $tahun)
                                <option value="{{ $tahun }}">{{ $tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label small fw-medium mb-1">Kantor</label>
                        <select class="form-select form-select-sm" id="kantorFilter">
                            <option value="">Semua Kantor</option>
                            @php
                                $kantorList = $kontraks->pluck('proyek.kantor.nama_kantor')
                                            ->filter()
                                            ->unique()
                                            ->sort();
                            @endphp
                            @foreach($kantorList as $kantor)
                                <option value="{{ $kantor }}">{{ $kantor }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label small fw-medium mb-1">Bagian</label>
                        <select class="form-select form-select-sm" id="bagianFilter">
                            <option value="">Semua Bagian</option>
                            <option value="Perencana">Perencana</option>
                            <option value="Pelaksana">Pelaksana</option>
                            <option value="Pengawas">Pengawas</option>
                            <option value="Pengelola Teknis">Pengelola Teknis</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="d-flex gap-2">
                            <div class="flex-grow-1">
                                <label class="form-label small fw-medium mb-1">Cari</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-transparent border-end-0">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0"
                                           id="searchInput" placeholder="Cari proyek/mitra/SPK...">
                                </div>
                            </div>
                            <div class="d-flex align-items-end">
                                <button class="btn btn-sm btn-outline-secondary" onclick="resetFilters()"
                                        title="Reset Filter">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                @if($kontraks->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" width="5%">#</th>
                                    <th width="25%">Kontrak & Proyek</th>
                                    <th width="15%">SPK & Tanggal</th>
                                    <th width="20%">Nilai & Progress</th>
                                    <th class="text-center" width="10%">Status</th>
                                    <th class="text-center" width="10%">File</th>
                                    <th class="text-center pe-4" width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kontraks as $index => $k)
                                <tr class="kontrak-row"
                                    data-year="{{ \Carbon\Carbon::parse($k->tanggal)->year }}"
                                    data-kantor="{{ $k->proyek->kantor->nama_kantor ?? 'Lainnya' }}"
                                    data-bagian="{{ $k->bagian }}"
                                    data-proyek="{{ $k->proyek->nama_proyek ?? '' }}"
                                    data-mitra="{{ $k->mitra }}"
                                    data-spk="{{ $k->nomor_spk }}">

                                    <!-- No. Urut -->
                                    <td class="ps-4 fw-bold text-muted">
                                        {{ $index + 1 }}
                                    </td>

                                    <!-- Kontrak & Proyek -->
                                    <td>
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center"
                                                     style="width: 36px; height: 36px;">
                                                    <i class="bi bi-file-earmark text-primary"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-1">
                                                    <span class="badge bg-{{ get_bagian_color($k->bagian) }} me-2">
                                                        {{ $k->bagian }}
                                                    </span>
                                                    <h6 class="mb-0 fw-semibold" style="font-size: 0.9rem;">
                                                        {{ Str::limit($k->mitra, 30) }}
                                                    </h6>
                                                </div>
                                                <div class="text-muted small">
                                                    <i class="bi bi-briefcase me-1"></i>
                                                    {{ Str::limit($k->proyek->nama_proyek ?? '-', 25) }}
                                                </div>
                                                <div class="text-muted small">
                                                    <i class="bi bi-building me-1"></i>
                                                    {{ $k->proyek->kantor->nama_kantor ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- SPK & Tanggal -->
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium text-dark mb-1">{{ $k->nomor_spk }}</span>
                                            <div class="text-muted small">
                                                <i class="bi bi-calendar me-1"></i>
                                                {{ $k->tanggal_formatted }}
                                            </div>
                                            @if($k->jangka_waktu)
                                            <div class="text-muted small">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $k->jangka_waktu }} hari
                                            </div>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Nilai & Progress -->
                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="fw-bold text-success mb-1">
                                                {{ $k->nilai_kontrak_formatted }}
                                            </div>
                                            @if($k->pagu_biaya)
                                            <div class="text-info small mb-2">
                                                Pagu: {{ format_currency($k->pagu_biaya, false) }}
                                            </div>
                                            @endif
                                            @php
                                                $progress = $k->total_progress_biaya ?? 0;
                                                $color = get_progress_color($progress);
                                            @endphp
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                    <div class="progress-bar bg-{{ $color }}"
                                                         style="width: {{ min($progress, 100) }}%">
                                                    </div>
                                                </div>
                                                <span class="text-{{ $color }} fw-medium small">
                                                    {{ number_format($progress, 1) }}%
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td class="text-center">
                                        @php
                                            $progress = $k->total_progress_biaya ?? 0;
                                            $statusColor = $progress >= 100 ? 'success' :
                                                        ($progress > 0 ? 'warning' : 'secondary');
                                            $statusText = $progress >= 100 ? 'Selesai' :
                                                        ($progress > 0 ? 'Berjalan' : 'Baru');
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }} mb-2 d-block">
                                            {{ $statusText }}
                                        </span>
                                        <div class="small text-muted">
                                            {{ $k->biayas->count() }} Termin
                                        </div>
                                    </td>

                                    <!-- File -->
                                    <td class="text-center">
                                        @if($k->file_spk)
                                            <a href="{{ route('kontrak.download-spk', $k->id) }}"
                                               class="btn btn-sm btn-outline-success"
                                               title="Download {{ $k->original_filename }}"
                                               target="_blank">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>

                                    <!-- Aksi -->
                                    <td class="pe-4">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('kontrak.show', $k->id) }}"
                                               class="btn btn-sm btn-info text-white"
                                               title="Detail"
                                               data-bs-toggle="tooltip">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('kontrak.edit', $k->id) }}"
                                               class="btn btn-sm btn-warning text-white"
                                               title="Edit"
                                               data-bs-toggle="tooltip">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete({{ $k->id }}, '{{ addslashes($k->nomor_spk) }}', '{{ addslashes($k->mitra) }}')"
                                                    title="Hapus"
                                                    data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $k->id }}"
                                                action="{{ route('kontrak.destroy', $k->id) }}"
                                                method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-file-earmark-x display-1 text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">Belum ada data kontrak</h4>
                        <p class="text-muted mb-4">Mulai dengan menambahkan kontrak pertama Anda</p>
                        <a href="{{ route('kontrak.create') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-plus-circle me-2"></i> Tambah Kontrak
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- No Results -->
        <div id="noResultsMessage" class="text-center py-5 d-none">
            <i class="bi bi-search display-4 text-muted"></i>
            <h4 class="text-muted mt-3">Tidak ada data yang sesuai</h4>
            <p class="text-muted">Coba ubah filter pencarian Anda</p>
            <button class="btn btn-outline-primary mt-2" onclick="resetFilters()">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
            </button>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $(document).ready(function() {
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Filter functionality
        function applyFilters() {
            const tahun = $('#tahunFilter').val();
            const kantor = $('#kantorFilter').val();
            const bagian = $('#bagianFilter').val();
            const search = $('#searchInput').val().toLowerCase();

            let visibleCount = 0;

            $('.kontrak-row').each(function() {
                const row = $(this);
                const rowYear = row.data('year').toString();
                const rowKantor = row.data('kantor').toLowerCase();
                const rowBagian = row.data('bagian');
                const rowProyek = row.data('proyek').toLowerCase();
                const rowMitra = row.data('mitra').toLowerCase();
                const rowSpk = row.data('spk').toLowerCase();

                let visible = true;

                if (tahun && rowYear !== tahun) visible = false;
                if (kantor && rowKantor !== kantor.toLowerCase()) visible = false;
                if (bagian && rowBagian !== bagian) visible = false;

                if (search) {
                    if (!rowProyek.includes(search) &&
                        !rowMitra.includes(search) &&
                        !rowSpk.includes(search)) {
                        visible = false;
                    }
                }

                row.toggle(visible);
                if (visible) visibleCount++;
            });

            // Update counter
            $('#kontrakCount').text(visibleCount);

            // Show/hide messages
            if (visibleCount === 0) {
                $('.table-responsive').hide();
                $('#noResultsMessage').removeClass('d-none');
            } else {
                $('.table-responsive').show();
                $('#noResultsMessage').addClass('d-none');
            }
        }

        window.resetFilters = function() {
            $('#tahunFilter, #kantorFilter, #bagianFilter').val('');
            $('#searchInput').val('');
            applyFilters();
        }

        $('#tahunFilter, #kantorFilter, #bagianFilter').on('change', applyFilters);
        $('#searchInput').on('keyup', applyFilters);

        applyFilters();

        // TIDAK ADA hover effect yang mengubah cursor menjadi pointer
        // TIDAK ADA click event untuk row
        // Hanya tombol aksi yang bisa diklik
    });

    // Confirm delete function
    function confirmDelete(id, nomorSpk, mitra) {
        Swal.fire({
            title: "Hapus Kontrak?",
            html: `<div class="text-start">
                    <p>Anda akan menghapus kontrak:</p>
                    <div class="alert alert-light border">
                        <strong>${mitra}</strong><br>
                        <small class="text-muted">No. SPK: ${nomorSpk}</small>
                    </div>
                    <div class="alert alert-danger mt-3">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Tindakan ini tidak dapat dibatalkan. Semua data terkait akan dihapus permanen.
                    </div>
                </div>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus Sekarang',
            cancelButtonText: 'Batalkan',
            reverseButtons: true,
            focusCancel: true,
            customClass: {
                confirmButton: 'btn btn-danger px-4',
                cancelButton: 'btn btn-secondary px-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    // Prevent row click from doing anything
    $(document).on('click', '.kontrak-row', function(e) {
        // Only allow clicks on buttons and links
        if (!$(e.target).closest('a, button, .btn').length) {
            e.stopPropagation();
            return false;
        }
    });
    </script>

    <style>
    .avatar-sm {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
        border-radius: 6px;
    }

    /* Table styling */
    .table {
        margin-bottom: 0;
    }

    .table > thead {
        background-color: #f8f9fa;
    }

    .table > thead > tr > th {
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        color: #495057;
        padding: 1rem;
    }

    .table > tbody > tr > td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f4;
    }

    /* Remove hover effect that suggests clickability */
    .table-hover > tbody > tr:hover {
        background-color: #fafafa; /* Very subtle hover */
        cursor: default; /* Normal cursor, not pointer */
    }

    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
        min-width: 80px;
    }

    .progress-bar {
        border-radius: 10px;
        transition: width 0.6s ease;
    }

    .badge {
        font-weight: 500;
        padding: 0.4em 0.8em;
        border-radius: 6px;
        font-size: 0.75rem;
    }

    /* Action buttons styling */
    .btn-sm {
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-sm i {
        font-size: 0.875rem;
    }

    /* Table responsive */
    @media (max-width: 992px) {
        .table-responsive {
            font-size: 0.9rem;
        }

        .btn-sm {
            padding: 0.2rem 0.4rem;
            width: 30px;
            height: 30px;
        }

        .badge {
            font-size: 0.7rem;
            padding: 0.3em 0.6em;
        }
    }

    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.85rem;
        }

        .table > tbody > tr > td,
        .table > thead > tr > th {
            padding: 0.75rem 0.5rem;
        }

        .avatar-sm {
            width: 32px;
            height: 32px;
        }

        .d-flex.gap-1 {
            flex-wrap: wrap;
            gap: 0.25rem !important;
        }

        .btn-sm {
            width: 28px;
            height: 28px;
            padding: 0.15rem 0.3rem;
        }
    }

    @media (max-width: 576px) {
        .table-responsive {
            font-size: 0.8rem;
        }

        h6.fw-semibold {
            font-size: 0.85rem !important;
        }

        .text-muted.small {
            font-size: 0.75rem;
        }
    }

    /* Custom scrollbar */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
        width: 6px;
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

    /* Ensure buttons look clickable but rows don't */
    .kontrak-row {
        user-select: none;
    }

    .kontrak-row a,
    .kontrak-row button {
        cursor: pointer;
    }
    </style>
</x-layout>
