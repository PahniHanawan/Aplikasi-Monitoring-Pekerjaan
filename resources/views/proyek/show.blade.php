<x-layout>
    <x-slot:title>Detail Proyek - {{ $proyek->nama_proyek }}</x-slot:title>

    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body py-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                                        <i class="fas fa-briefcase text-primary fa-2x"></i>
                                    </div>
                                    <div>
                                        <h2 class="mb-1 text-dark">{{ $proyek->nama_proyek }}</h2>
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-building me-1"></i>
                                            {{ $proyek->kantor->nama_kantor ?? 'Tidak ada kantor' }}
                                        </p>
                                        @if($proyek->deskripsi)
                                            <p class="text-muted mb-0 mt-1">{{ $proyek->deskripsi }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                                    <a href="{{ route('proyek.edit', $proyek->id) }}"
                                       class="btn btn-warning btn-lg px-4">
                                        <i class="fas fa-edit me-2"></i>Edit
                                    </a>
                                    <a href="{{ route('proyek.index') }}"
                                       class="btn btn-outline-secondary btn-lg px-4">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column - Project Information -->
            <div class="col-lg-8">
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Kontrak
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $proyek->kontraks->count() }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-file-contract fa-2x text-gray-300"></i>
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
                                            Total Nilai
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            Rp {{ number_format($proyek->total_nilai_kontrak, 0, ',', '.') }}
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
                                            Progress
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $proyek->progress_terendah }}%
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
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
                                            Status
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            @if($proyek->progress_terendah >= 100)
                                                <span class="text-success">Selesai</span>
                                            @else
                                                <span class="text-warning">Berjalan</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-flag fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Details Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-info-circle text-info"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 text-dark">Informasi Proyek</h5>
                                <small class="text-muted">Detail lengkap mengenai proyek</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item mb-4">
                                    <label class="form-label text-muted small fw-semibold mb-1">
                                        <i class="fas fa-tag me-1"></i>Nama Proyek
                                    </label>
                                    <p class="mb-0 fw-bold text-dark fs-6">{{ $proyek->nama_proyek }}</p>
                                </div>

                                <div class="info-item mb-4">
                                    <label class="form-label text-muted small fw-semibold mb-1">
                                        <i class="fas fa-building me-1"></i>Kantor
                                    </label>
                                    <p class="mb-0 text-dark">
                                        <span class="badge bg-primary bg-opacity-10 text-primary py-2">
                                            {{ $proyek->kantor->nama_kantor ?? 'Tidak ada kantor' }}
                                        </span>
                                    </p>
                                </div>

                                <div class="info-item mb-4">
                                    <label class="form-label text-muted small fw-semibold mb-1">
                                        <i class="fas fa-calendar me-1"></i>Dibuat Pada
                                    </label>
                                    <p class="mb-0 text-dark">{{ $proyek->created_at->format('d F Y H:i') }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-item mb-4">
                                    <label class="form-label text-muted small fw-semibold mb-1">
                                        <i class="fas fa-file-alt me-1"></i>Izin Prinsip
                                    </label>
                                    <p class="mb-0">
                                        @if($proyek->izin_prinsip)
                                            <span class="badge bg-success bg-opacity-10 text-success py-2">
                                                <i class="fas fa-check me-1"></i>{{ $proyek->izin_prinsip }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary py-2">
                                                <i class="fas fa-times me-1"></i>Tidak Ada
                                            </span>
                                        @endif
                                    </p>
                                </div>

                                <div class="info-item mb-4">
                                    <label class="form-label text-muted small fw-semibold mb-1">
                                        <i class="fas fa-file-pdf me-1"></i>File Izin Prinsip
                                    </label>
                                    <div class="mt-1">
                                    @if($proyek->izin_prinsip_file)
                                        <a href="{{ route('proyek.download-izin', $proyek->id) }}"
                                        target="_blank"
                                        class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>Lihat Dokumen
                                        </a>
                                    @endif
                                    </div>
                                </div>

                                <div class="info-item">
                                    <label class="form-label text-muted small fw-semibold mb-1">
                                        <i class="fas fa-sync-alt me-1"></i>Terakhir Diupdate
                                    </label>
                                    <p class="mb-0 text-dark">{{ $proyek->updated_at->format('d F Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        @if($proyek->deskripsi)
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="info-item">
                                    <label class="form-label text-muted small fw-semibold mb-2">
                                        <i class="fas fa-align-left me-1"></i>Deskripsi Proyek
                                    </label>
                                    <div class="bg-light rounded p-3">
                                        <p class="mb-0 text-dark">{{ $proyek->deskripsi }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Kontrak Section -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                    <i class="fas fa-file-contract text-warning"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 text-dark">Daftar Kontrak</h5>
                                    <small class="text-muted">Kontrak yang terkait dengan proyek ini</small>
                                </div>
                            </div>
                            <span class="badge bg-warning bg-opacity-25 text-warning py-2">
                                {{ $proyek->kontraks->count() }} Kontrak
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($proyek->kontraks->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Bagian</th>
                                            <th>Mitra</th>
                                            <th class="text-center">Nilai Kontrak</th>
                                            <th class="text-center">SPK</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($proyek->kontraks as $kontrak)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ $kontrak->bagian }}</div>
                                                <small class="text-muted">Masa: {{ $kontrak->jangka_waktu }} hari</small>
                                            </td>
                                            <td>
                                                <span class="text-dark">{{ $kontrak->mitra }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-bold text-success">
                                                    Rp {{ number_format($kontrak->nilai_kontrak, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <small class="text-muted">{{ $kontrak->nomor_spk }}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="#" class="btn btn-outline-info border-0">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-outline-warning border-0">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Summary Kontrak -->
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="bg-light rounded p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">Total Nilai Kontrak:</span>
                                            <span class="fw-bold text-success fs-5">
                                                Rp {{ number_format($proyek->total_nilai_kontrak, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-light rounded p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">Rata-rata per Kontrak:</span>
                                            <span class="fw-bold text-primary">
                                                Rp {{ number_format($proyek->kontraks->count() > 0 ? $proyek->total_nilai_kontrak / $proyek->kontraks->count() : 0, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-file-contract fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">Belum Ada Kontrak</h6>
                                <p class="text-muted mb-0">Tambahkan kontrak untuk proyek ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Progress & Actions -->
            <div class="col-lg-4">
                <!-- Progress Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-chart-line text-success"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 text-dark">Progress Proyek</h5>
                                <small class="text-muted">Status perkembangan proyek</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <!-- Circular Progress -->
                        <div class="progress-circle mb-4 mx-auto"
                             data-progress="{{ $proyek->progress_terendah }}"
                             data-color="{{ $proyek->progress_terendah >= 100 ? 'success' : ($proyek->progress_terendah >= 80 ? 'warning' : 'danger') }}">
                            <div class="progress-circle-value">
                                <span class="progress-percent">{{ $proyek->progress_terendah }}%</span>
                                <small class="text-muted d-block">Selesai</small>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="mb-3">
                            @if($proyek->progress_terendah >= 100)
                                <span class="badge bg-success py-2 px-3 fs-6">
                                    <i class="fas fa-check-circle me-1"></i>Proyek Selesai
                                </span>
                            @elseif($proyek->progress_terendah >= 80)
                                <span class="badge bg-warning py-2 px-3 fs-6">
                                    <i class="fas fa-clock me-1"></i>Menuju Penyelesaian
                                </span>
                            @else
                                <span class="badge bg-danger py-2 px-3 fs-6">
                                    <i class="fas fa-exclamation-circle me-1"></i>Dalam Pengerjaan
                                </span>
                            @endif
                        </div>

                        <!-- Progress Details -->
                        <div class="progress-details">
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">Progress Terkecil:</small>
                                <small class="fw-bold">{{ $proyek->progress_terendah }}%</small>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-{{ $proyek->progress_terendah >= 100 ? 'success' : ($proyek->progress_terendah >= 80 ? 'warning' : 'danger') }}"
                                     style="width: {{ $proyek->progress_terendah }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-bolt text-primary"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 text-dark">Aksi Cepat</h5>
                                <small class="text-muted">Tindakan cepat untuk proyek</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('proyek.edit', $proyek->id) }}"
                               class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-edit me-2"></i>Edit Proyek
                            </a>
                            <a href="#" class="btn btn-outline-success btn-lg">
                                <i class="fas fa-plus me-2"></i>Tambah Kontrak
                            </a>
                            <a href="#" class="btn btn-outline-info btn-lg">
                                <i class="fas fa-chart-bar me-2"></i>Lihat Laporan
                            </a>
                            <a href="{{ route('proyek.index') }}"
                               class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-list me-2"></i>Daftar Proyek
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Project Timeline -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-history text-info"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 text-dark">Timeline</h5>
                                <small class="text-muted">Riwayat proyek</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Proyek Dibuat</h6>
                                    <small class="text-muted">{{ $proyek->created_at->format('d M Y H:i') }}</small>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Terakhir Diupdate</h6>
                                    <small class="text-muted">{{ $proyek->updated_at->format('d M Y H:i') }}</small>
                                </div>
                            </div>
                            @if($proyek->progress_terendah >= 100)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Proyek Selesai</h6>
                                    <small class="text-muted">Mencapai 100% progress</small>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 0.75rem;
            border: 1px solid #e3e6f0;
        }

        .card-header {
            border-bottom: 1px solid #e3e6f0;
            border-radius: 0.75rem 0.75rem 0 0 !important;
        }

        .info-item {
            border-left: 3px solid #007bff;
            padding-left: 1rem;
        }

        .progress-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: conic-gradient(
                var(--progress-color) 0% calc(var(--progress) * 1%),
                #e9ecef calc(var(--progress) * 1%) 100%
            );
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .progress-circle::before {
            content: '';
            position: absolute;
            width: 90px;
            height: 90px;
            background: white;
            border-radius: 50%;
        }

        .progress-circle-value {
            position: relative;
            text-align: center;
        }

        .progress-percent {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        .progress-circle[data-color="success"] {
            --progress-color: #28a745;
        }

        .progress-circle[data-color="warning"] {
            --progress-color: #ffc107;
        }

        .progress-circle[data-color="danger"] {
            --progress-color: #dc3545;
        }

        .timeline {
            position: relative;
            padding-left: 2rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .timeline-marker {
            position: absolute;
            left: -2rem;
            top: 0.25rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            border: 3px solid white;
        }

        .timeline-content {
            padding-left: 0.5rem;
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

        .table th {
            background-color: #f8f9fc;
            border-bottom: 2px solid #e3e6f0;
            font-weight: 600;
        }

        .btn-group .btn {
            padding: 0.375rem 0.5rem;
        }

        @media (max-width: 768px) {
            .progress-circle {
                width: 100px;
                height: 100px;
            }

            .progress-circle::before {
                width: 70px;
                height: 70px;
            }

            .progress-percent {
                font-size: 1.25rem;
            }

            .card-body {
                padding: 1rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize progress circles
            const progressCircles = document.querySelectorAll('.progress-circle');
            progressCircles.forEach(circle => {
                const progress = circle.dataset.progress;
                const color = circle.dataset.color;
                circle.style.setProperty('--progress', progress);
                circle.style.setProperty('--progress-color', getComputedStyle(circle).getPropertyValue(`--${color}`));
            });

            // Add animation to progress bars
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                    bar.style.transition = 'width 1s ease-in-out';
                }, 100);
            });
        });
    </script>
</x-layout>
