<x-layout>
    <x-slot:title>{{ $title ?? 'Detail Dokumen Proyek' }}</x-slot:title>

    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h6 class="mb-1">Detail Dokumen Proyek</h6>
                                <p class="text-sm text-muted mb-0">{{ $proyek->nama_proyek }}</p>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('dokumen.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                                <a href="{{ route('proyek.dokumen.create', $proyek) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-1"></i> Upload Baru
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Info Proyek -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Informasi Proyek
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="info-item mb-3">
                            <small class="text-muted">Nama Proyek</small>
                            <p class="mb-0 font-weight-bold text-dark">{{ $proyek->nama_proyek }}</p>
                        </div>
                        <div class="info-item mb-3">
                            <small class="text-muted">Deskripsi</small>
                            <p class="mb-0 text-dark">{{ $proyek->deskripsi ?: '-' }}</p>
                        </div>
                        <div class="info-item mb-3">
                            <small class="text-muted">Kantor</small>
                            <p class="mb-0 text-dark">{{ $proyek->kantor->nama_kantor }}</p>
                        </div>
                        <div class="info-item">
                            <small class="text-muted">Status Proyek</small>
                            <div class="mt-1">
                                @if($proyek->progress_terendah == 100)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle me-1"></i>Selesai 100%
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-clock me-1"></i>Progress {{ $proyek->progress_terendah }}%
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Kelengkapan -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>Status Kelengkapan Dokumen
                        </h6>
                    </div>
                    <div class="card-body">
                        @php
                            $dokumenStatus = $proyek->dokumen_status;
                            $totalDokumen = count($dokumenStatus);
                            $dokumenUploaded = count(array_filter($dokumenStatus));
                            $persentaseLengkap = ($dokumenUploaded / $totalDokumen) * 100;
                        @endphp

                        <div class="completion-status text-center mb-4">
                            <div class="completion-chart position-relative d-inline-block mb-3">
                                <div class="chart-circle"
                                     data-percent="{{ $persentaseLengkap }}"
                                     data-color="{{ $proyek->dokumen_lengkap ? 'success' : 'warning' }}">
                                    <span class="percent">{{ round($persentaseLengkap) }}%</span>
                                </div>
                            </div>
                            <h6 class="mb-1 text-dark">Kelengkapan Dokumen</h6>
                            <p class="text-sm text-muted mb-0">{{ $dokumenUploaded }} dari {{ $totalDokumen }} dokumen</p>
                        </div>

                        <div class="status-details">
                            @foreach($dokumenStatus as $jenis => $status)
                                <div class="status-item d-flex justify-content-between align-items-center p-3 border rounded mb-2 bg-light">
                                    <div class="d-flex align-items-center">
                                        <i class="fas {{ $status ? 'fa-check-circle text-success' : 'fa-times-circle text-warning' }} me-3 fs-6"></i>
                                        <div>
                                            <span class="font-weight-bold text-dark d-block">
                                                {{ getJenisDokumenLabel($jenis) }}
                                            </span>
                                            <small class="text-muted">
                                                @if($status)
                                                    <i class="fas fa-calendar-check me-1"></i>
                                                    {{ \Carbon\Carbon::parse($proyek->dokumen->where('jenis_dokumen', $jenis)->first()->tanggal_upload)->format('d/m/Y') }}
                                                @else
                                                    Belum diupload
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <span class="badge badge-{{ $status ? 'success' : 'warning' }} badge-lg py-2 px-3">
                                        {{ $status ? 'LENGKAP' : 'BELUM' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        @if($proyek->dokumen_lengkap)
                            <div class="alert alert-success mt-4 mb-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle me-2 fs-5"></i>
                                    <span class="fw-bold">Semua dokumen sudah lengkap!</span>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning mt-4 mb-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                                    <span class="fw-bold">Masih ada dokumen yang belum diupload.</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Daftar Dokumen -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h6 class="mb-0 text-dark">
                                <i class="fas fa-file-alt me-2"></i>Daftar Dokumen
                                <span class="badge badge-primary ms-2">{{ $proyek->dokumen->count() }} file</span>
                            </h6>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm" onclick="toggleFilter()">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-download me-1"></i> Export
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2"></i>PDF</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2"></i>Excel</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Section -->
                    <div class="card-body border-bottom d-none" id="filterSection">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label text-sm fw-bold">Jenis Dokumen</label>
                                <select class="form-select form-select-sm" id="filterJenis">
                                    <option value="">Semua Jenis</option>
                                    <option value="bast_i">BAST I</option>
                                    <option value="bast_ii">BAST II</option>
                                    <option value="kwitansi">Kwitansi</option>
                                    <option value="spp">SPP</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-sm fw-bold">Kontrak</label>
                                <select class="form-select form-select-sm" id="filterKontrak">
                                    <option value="">Semua Kontrak</option>
                                    @foreach($proyek->kontraks as $kontrak)
                                        <option value="{{ $kontrak->id }}">{{ $kontrak->bagian }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-sm fw-bold">&nbsp;</label>
                                <div>
                                    <button class="btn btn-primary btn-sm" onclick="applyFilter()">
                                        <i class="fas fa-check me-1"></i> Terapkan
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="resetFilter()">
                                        <i class="fas fa-refresh me-1"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        @if($proyek->dokumen->count() > 0)
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0" id="dokumenTable">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Dokumen</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Kontrak</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Jenis</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Ukuran</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Tanggal Upload</th>
                                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($proyek->dokumen->sortByDesc('tanggal_upload') as $dokumen)
                                        <tr class="dokumen-item"
                                            data-jenis="{{ $dokumen->jenis_dokumen }}"
                                            data-kontrak="{{ $dokumen->kontrak_id }}">
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas {{ getFileIcon($dokumen->original_filename) }} text-primary me-3 fs-5"></i>
                                                        <div class="d-flex flex-column">
                                                            <h6 class="mb-0 text-sm text-dark">{{ $dokumen->original_filename }}</h6>
                                                            @if($dokumen->keterangan)
                                                                <p class="text-xs text-secondary mb-0 mt-1">{{ Str::limit($dokumen->keterangan, 50) }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 text-dark">{{ $dokumen->kontrak->bagian }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ Str::limit($dokumen->kontrak->mitra, 30) }}</p>
                                            </td>
                                            <td>
                                                <span class="badge jenis-dokumen-badge jenis-{{ $dokumen->jenis_dokumen }} py-2 px-3 fw-bold">
                                                    {{ getJenisDokumenLabel($dokumen->jenis_dokumen) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0 text-dark">{{ formatFileSize($dokumen->file_size) }}</p>
                                            </td>
                                            <td class="text-center">
                                                <p class="text-xs font-weight-bold mb-0 text-dark">{{ $dokumen->tanggal_upload->format('d/m/Y') }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ $dokumen->tanggal_upload->format('H:i') }}</p>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <a href="{{ route('dokumen.download', $dokumen) }}"
                                                       class="btn btn-info btn-sm"
                                                       data-bs-toggle="tooltip"
                                                       title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <a href="#"
                                                       class="btn btn-warning btn-sm"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#previewModal{{ $dokumen->id }}"
                                                       title="Preview">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form action="{{ route('dokumen.destroy', $dokumen) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Hapus dokumen ini?')"
                                                                data-bs-toggle="tooltip"
                                                                title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Preview Modal -->
                                        <div class="modal fade" id="previewModal{{ $dokumen->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title text-dark">Preview: {{ $dokumen->original_filename }}</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        @if(isImageFile($dokumen->original_filename))
                                                            <img src="{{ Storage::url('dokumen-proyek/' . $dokumen->nama_file) }}"
                                                                 class="img-fluid"
                                                                 alt="Preview {{ $dokumen->original_filename }}"
                                                                 style="max-height: 70vh;">
                                                        @else
                                                            <div class="preview-pdf">
                                                                <i class="fas {{ getFileIcon($dokumen->original_filename) }} fa-4x text-primary mb-3"></i>
                                                                <h6 class="text-muted">{{ $dokumen->original_filename }}</h6>
                                                                <p class="text-muted mb-3">File tidak dapat dipreview secara langsung</p>
                                                                <a href="{{ route('dokumen.download', $dokumen) }}"
                                                                   class="btn btn-primary">
                                                                    <i class="fas fa-download me-2"></i>Download untuk Melihat
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        <a href="{{ route('dokumen.download', $dokumen) }}" class="btn btn-primary">
                                                            <i class="fas fa-download me-2"></i>Download
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-file-upload fa-3x text-secondary mb-3"></i>
                                <h6 class="text-secondary mb-2">Belum Ada Dokumen</h6>
                                <p class="text-muted mb-4">Upload dokumen pertama untuk proyek ini</p>
                                <a href="{{ route('proyek.dokumen.create', $proyek) }}" class="btn btn-primary">
                                    <i class="fas fa-upload me-2"></i>Upload Dokumen
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFilter() {
            const filterSection = document.getElementById('filterSection');
            filterSection.classList.toggle('d-none');
        }

        function applyFilter() {
            const jenis = document.getElementById('filterJenis').value;
            const kontrak = document.getElementById('filterKontrak').value;
            const items = document.querySelectorAll('.dokumen-item');

            items.forEach(item => {
                const itemJenis = item.getAttribute('data-jenis');
                const itemKontrak = item.getAttribute('data-kontrak');

                const showJenis = !jenis || itemJenis === jenis;
                const showKontrak = !kontrak || itemKontrak === kontrak;

                if (showJenis && showKontrak) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function resetFilter() {
            document.getElementById('filterJenis').value = '';
            document.getElementById('filterKontrak').value = '';
            applyFilter();
        }

        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
        });
    </script>

    <style>
        /* Styling untuk badge jenis dokumen yang lebih jelas */
        .jenis-dokumen-badge {
            font-size: 0.75rem;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            min-width: 80px;
            text-align: center;
            border: 2px solid transparent;
        }

        .jenis-bast_i {
            background-color: #007bff;
            color: white;
            border-color: #0056b3;
        }

        .jenis-bast_ii {
            background-color: #28a745;
            color: white;
            border-color: #1e7e34;
        }

        .jenis-kwitansi {
            background-color: #ffc107;
            color: #212529;
            border-color: #d39e00;
        }

        .jenis-spp {
            background-color: #dc3545;
            color: white;
            border-color: #bd2130;
        }

        /* Hover effect untuk badge */
        .jenis-dokumen-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            transition: all 0.2s ease;
        }

        /* Chart circle styling */
        .chart-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: conic-gradient(
                var(--chart-color) 0% calc(var(--percent) * 1%),
                #e9ecef calc(var(--percent) * 1%) 100%
            );
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .chart-circle::before {
            content: '';
            position: absolute;
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
        }

        .chart-circle .percent {
            position: relative;
            font-weight: bold;
            font-size: 1.2rem;
            color: #333;
        }

        .chart-circle[data-color="success"] {
            --chart-color: #28a745;
        }

        .chart-circle[data-color="warning"] {
            --chart-color: #ffc107;
        }

        .info-item {
            border-left: 3px solid #007bff;
            padding-left: 1rem;
        }

        .status-item {
            transition: all 0.3s ease;
            border: 1px solid #dee2e6 !important;
        }

        .status-item:hover {
            background-color: #f8f9fa !important;
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .dokumen-item:hover {
            background-color: #f8f9fa;
        }

        .preview-pdf {
            padding: 2rem;
        }

        .badge-lg {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
        }

        /* Memastikan teks di badge mudah dibaca */
        .badge {
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .chart-circle {
                width: 80px;
                height: 80px;
            }

            .chart-circle::before {
                width: 60px;
                height: 60px;
            }

            .chart-circle .percent {
                font-size: 1rem;
            }

            .table-responsive {
                font-size: 0.875rem;
            }

            .btn-sm {
                padding: 0.25rem 0.5rem;
            }

            .status-item {
                padding: 1rem !important;
            }

            .jenis-dokumen-badge {
                font-size: 0.7rem;
                padding: 0.4rem 0.6rem;
                min-width: 70px;
            }
        }
    </style>
</x-layout>
