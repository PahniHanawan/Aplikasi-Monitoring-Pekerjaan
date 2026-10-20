<x-layout>
    <x-slot:title>Detail Kontrak & Biaya</x-slot:title>

    <div class="container-fluid mt-3">
        <div class="card shadow-lg border-0 rounded-3">
            <!-- Card Header -->
            <div class="card-header bg-info text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title m-0">
                            <i class="bi bi-eye me-2"></i> Detail Kontrak & Biaya
                        </h3>
                        <small class="opacity-75">{{ $kontrak->proyek->nama_proyek ?? 'Proyek tidak ditemukan' }}</small>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('kontrak.edit', $kontrak->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <a href="{{ route('kontrak.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <!-- Alert -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {!! session('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {!! session('error') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Overview -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">Nilai Kontrak</h6>
                                <h3 class="text-success">{{ format_currency($kontrak->nilai_kontrak) }}</h3>
                                @if($kontrak->pagu_biaya)
                                    <small class="text-muted">
                                        Pagu: {{ format_currency($kontrak->pagu_biaya) }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">Total Progress</h6>
                                <h3 class="text-primary">{{ format_currency($kontrak->total_progress_biaya, 1) }}%</h3>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-primary"
                                         style="width: {{ min($kontrak->total_progress_biaya, 100) }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">Total Realisasi</h6>
                                <h3 class="text-info">{{ format_currency($kontrak->total_realisasi_biaya) }}</h3>
                                <small class="text-muted">
                                    {{ format_currency($kontrak->progress_percentage, 1) }}% dari nilai kontrak
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">Sisa Kontrak</h6>
                                <h3 class="text-warning">{{ format_currency($kontrak->sisa_kontrak) }}</h3>
                                <small class="text-muted">
                                    {{ $kontrak->biayas->count() }} Termin
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Detail Kontrak -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="bi bi-file-text me-2"></i> Data Kontrak</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Proyek</th>
                                        <td>
                                            <strong>{{ $kontrak->proyek->nama_proyek ?? '-' }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="bi bi-building"></i>
                                                {{ $kontrak->proyek->kantor->nama_kantor ?? '-' }}
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Bagian</th>
                                        <td>
                                            <span class="badge bg-{{ get_bagian_color($kontrak->bagian) }}">
                                                {{ $kontrak->bagian }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Mitra</th>
                                        <td>{{ $kontrak->mitra }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nomor SPK</th>
                                        <td class="font-monospace">{{ $kontrak->nomor_spk }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal</th>
                                        <td>{{ \Carbon\Carbon::parse($kontrak->tanggal)->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jangka Waktu</th>
                                        <td>
                                            @if($kontrak->jangka_waktu)
                                                <span class="badge bg-warning text-dark">{{ $kontrak->jangka_waktu }} hari</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Masa Pemeliharaan</th>
                                        <td>
                                            @if($kontrak->masa_pemeliharaan)
                                                <span class="badge bg-info text-dark">{{ $kontrak->masa_pemeliharaan }} hari</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>File SPK</th>
                                        <td>
                                            @if($kontrak->file_spk)
                                                <a href="{{ route('kontrak.download-spk', $kontrak->id) }}"
                                                   class="btn btn-sm btn-outline-success" target="_blank">
                                                    <i class="bi bi-download me-1"></i>
                                                    {{ $kontrak->original_filename }} ({{ $kontrak->file_size }})
                                                </a>
                                            @else
                                                <span class="badge bg-secondary">Tidak ada file</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Kontrak Lain dalam Proyek -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i> Kontrak Lain dalam Proyek</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>Bagian</th>
                                                <th>Mitra</th>
                                                <th>Nilai</th>
                                                <th>Progress</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($kontrak->proyek->kontraks as $k)
                                                @if($k->id != $kontrak->id)
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-{{ get_bagian_color($k->bagian) }}">
                                                            {{ $k->bagian }}
                                                        </span>
                                                    </td>
                                                    <td>{{ Str::limit($k->mitra, 15) }}</td>
                                                    <td>{{ format_currency($k->nilai_kontrak) }}</td>
                                                    <td>
                                                        <div class="progress" style="height: 4px; width: 60px;">
                                                            <div class="progress-bar bg-primary"
                                                                 style="width: {{ min($k->total_progress_biaya, 100) }}%">
                                                            </div>
                                                        </div>
                                                        <small>{{ format_currency($k->total_progress_biaya, 1) }}%</small>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                        @if($kontrak->proyek->kontraks->count() > 1)
                                        <tfoot>
                                            <tr class="table-secondary">
                                                <td colspan="2" class="text-end fw-bold">Total:</td>
                                                <td class="fw-bold text-success">
                                                    {{ format_currency($kontrak->proyek->kontraks->sum('nilai_kontrak')) }}
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                        @endif
                                    </table>
                                </div>
                                @if($kontrak->proyek->kontraks->count() == 1)
                                    <div class="text-center py-3">
                                        <small class="text-muted">Tidak ada kontrak lain dalam proyek ini</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Termin Biaya -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-cash-stack me-2"></i> Termin Biaya</h5>
                        <a href="{{ route('kontrak.edit', $kontrak->id) }}#terminContainer"
                           class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Kelola Termin
                        </a>
                    </div>
                    <div class="card-body">
                        @if($kontrak->biayas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="15%">Jenis</th>
                                        <th width="10%">Termin</th>
                                        <th width="15%">Progress</th>
                                        <th width="20%">Tanggal Realisasi</th>
                                        <th width="20%">Nominal Realisasi</th>
                                        <th width="15%">Persentase dari Kontrak</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kontrak->biayas->sortBy('termin') as $index => $biaya)
                                    <tr>
                                        <td class="fw-bold">{{ $index + 1 }}</td>
                                        <td>
                                            <span class="badge {{ $biaya->jenis == 'Masa Pelaksanaan' ? 'bg-primary' : 'bg-info' }}">
                                                {{ $biaya->jenis }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark">#{{ $biaya->termin }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                    <div class="progress-bar bg-success"
                                                         style="width: {{ $biaya->progress }}%">
                                                    </div>
                                                </div>
                                                <span class="fw-medium">{{ format_currency($biaya->progress, 1) }}%</span>
                                            </div>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($biaya->tanggal_realisasi)->format('d/m/Y') }}</td>
                                        <td class="fw-bold text-success">
                                            {{ format_currency($biaya->nominal_realisasi) }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                    <div class="progress-bar bg-warning"
                                                         style="width: {{ ($biaya->nominal_realisasi / $kontrak->nilai_kontrak) * 100 }}%">
                                                    </div>
                                                </div>
                                                <span class="fw-medium">
                                                    {{ format_currency(($biaya->nominal_realisasi / $kontrak->nilai_kontrak) * 100, 1) }}%
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-secondary">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">TOTAL:</td>
                                        <td class="fw-bold">{{ format_currency($kontrak->total_progress_biaya, 1) }}%</td>
                                        <td></td>
                                        <td class="fw-bold text-success">{{ format_currency($kontrak->total_realisasi_biaya) }}</td>
                                        <td class="fw-bold">{{ format_currency($kontrak->progress_percentage, 1) }}%</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-cash display-5 text-muted"></i>
                            <h5 class="text-muted mt-3">Belum ada termin biaya</h5>
                            <p class="text-muted">Tambahkan termin biaya untuk melacak progress dan realisasi</p>
                            <a href="{{ route('kontrak.edit', $kontrak->id) }}#terminContainer"
                               class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Tambah Termin
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 d-flex justify-content-between">
                    <div>
                        <a href="{{ route('kontrak.create') }}?proyek_id={{ $kontrak->proyek_id }}"
                           class="btn btn-outline-primary me-2">
                            <i class="bi bi-plus-circle"></i> Tambah Kontrak Lain
                        </a>
                        <a href="{{ route('kontrak.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list"></i> Semua Kontrak
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('kontrak.download-spk', $kontrak->id) }}"
                           class="btn btn-success me-2 {{ !$kontrak->file_spk ? 'disabled' : '' }}"
                           target="_blank">
                            <i class="bi bi-download"></i> Download SPK
                        </a>
                        <a href="{{ route('kontrak.edit', $kontrak->id) }}" class="btn btn-warning me-2">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <button class="btn btn-danger"
                                onclick="confirmDelete({{ $kontrak->id }}, '{{ addslashes($kontrak->nomor_spk) }}')">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                        <form id="delete-form-{{ $kontrak->id }}"
                            action="{{ route('kontrak.destroy', $kontrak->id) }}"
                            method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    function confirmDelete(id, nomorSpk) {
        Swal.fire({
            title: "Konfirmasi Hapus",
            html: `<div class="text-center">
                    <i class="bi bi-exclamation-triangle text-warning display-4 mb-3"></i>
                    <p>Apakah Anda yakin ingin menghapus kontrak ini?</p>
                    <div class="alert alert-danger text-start">
                        <strong>No. SPK:</strong> ${nomorSpk}<br>
                        <strong>⚠️ PERHATIAN:</strong> Semua termin biaya akan ikut terhapus!
                    </div>
                </div>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
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
        border: none;
        transition: all 0.3s ease;
    }

    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar {
        border-radius: 10px;
        transition: width 0.6s ease;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
        background-color: #f8f9fa;
        padding: 1rem;
    }

    .table td {
        padding: 1rem;
        vertical-align: middle;
    }

    .badge {
        padding: 0.35em 0.65em;
        font-weight: 500;
        border-radius: 6px;
    }

    /* Custom badge colors */
    .bg-Perencana { background-color: #0d6efd !important; }
    .bg-Pelaksana { background-color: #198754 !important; }
    .bg-Pengawas { background-color: #fd7e14 !important; }
    .bg-Pengelola Teknis { background-color: #6f42c1 !important; }

    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.85rem;
        }

        .btn {
            margin-bottom: 0.5rem;
        }
    }
    </style>
</x-layout>
