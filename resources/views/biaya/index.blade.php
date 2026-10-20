<x-layout>
    <x-slot:title>Masa Pelaksanaan</x-slot:title> <!-- Ganti title -->

    <div class="container-fluid mt-3">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title m-0">
                    <i class="bi bi-calendar-range me-2"></i> Masa Pelaksanaan <!-- Ganti ikon dan teks -->
                </h3>
                <a href="{{ route('biaya.create') }}" class="btn btn-light btn-sm shadow-sm">
                    <i class="bi bi-plus-circle"></i> Tambah Data
                </a>
            </div>

            <div class="card-body">
                <!-- Alert Success -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Alert Error -->
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tahun</label>
                        <select class="form-select" id="yearFilter">
                            <option value="">Semua Tahun</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Bulan</label>
                        <select class="form-select" id="monthFilter">
                            <option value="">Semua Bulan</option>
                            @foreach($months as $key => $month)
                                <option value="{{ $key }}">{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Kantor</label>
                        <select class="form-select" id="kantorFilter">
                            <option value="">Semua Kantor</option>
                            @foreach($kantors as $kantor)
                                <option value="{{ $kantor->id }}">{{ $kantor->nama_kantor }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Proyek</label>
                        <select class="form-select" id="proyekFilter">
                            <option value="">Semua Proyek</option>
                            @foreach($proyeks as $proyek)
                                <option value="{{ $proyek->id }}">{{ $proyek->nama_proyek }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Stats Summary -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card border-0 bg-light shadow-sm">
                            <div class="card-body text-center">
                                <small class="text-muted d-block">Total Termin</small>
                                <h4 class="text-primary mb-0">{{ $totalTermin }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 bg-light shadow-sm">
                            <div class="card-body text-center">
                                <small class="text-muted d-block">Persentase Rata-rata</small>
                                <h4 class="text-success mb-0">{{ number_format($averageProgress, 1) }}%</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 bg-light shadow-sm">
                            <div class="card-body text-center">
                                <small class="text-muted d-block">Total Nominal</small>
                                <h4 class="text-warning mb-0">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 bg-light shadow-sm">
                            <div class="card-body text-center">
                                <small class="text-muted d-block">Jumlah Proyek</small>
                                <h4 class="text-info mb-0">{{ $totalProyek }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grouped Data -->
                <div id="groupedData">
                    @php
                        $groupedData = $biayas->groupBy(function($item) {
                            return \Carbon\Carbon::parse($item->tanggal_realisasi)->format('Y-m');
                        })->sortDesc();
                    @endphp

                    @foreach($groupedData as $yearMonth => $biayasPerMonth)
                        @php
                            $date = \Carbon\Carbon::parse($yearMonth);
                            $year = $date->format('Y');
                            $month = $date->format('F');
                            $monthNum = $date->format('m');
                        @endphp

                        <div class="card mb-4 border-0 shadow-sm year-month-group" data-year="{{ $year }}" data-month="{{ $monthNum }}">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-primary">
                                    <i class="bi bi-calendar-month me-2"></i>
                                    {{ $month }} {{ $year }}
                                    <span class="badge bg-primary ms-2">{{ $biayasPerMonth->count() }} Data</span>
                                </h5>
                                <button class="btn btn-sm btn-outline-primary toggle-group" data-bs-toggle="collapse" data-bs-target="#collapse{{ $yearMonth }}">
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                            </div>

                            <div class="collapse show" id="collapse{{ $yearMonth }}">
                                <div class="card-body p-0">
                                    @php
                                        $groupedByKantor = $biayasPerMonth->groupBy('kantor_id');
                                    @endphp

                                    @foreach($groupedByKantor as $kantorId => $biayasPerKantor)
                                        @php $kantor = $biayasPerKantor->first()->kantor; @endphp
                                        <div class="kantor-group border-bottom" data-kantor="{{ $kantorId }}">
                                            <div class="p-3 bg-light-subtle">
                                                <h6 class="mb-0 d-flex justify-content-between align-items-center">
                                                    <span>
                                                        <i class="bi bi-building me-2"></i>
                                                        {{ $kantor->nama_kantor }}
                                                        <span class="badge bg-info ms-2">{{ $biayasPerKantor->count() }} Termin</span>
                                                    </span>
                                                    <small class="text-muted">
                                                        Total: Rp {{ number_format($biayasPerKantor->sum('nominal_realisasi'), 0, ',', '.') }}
                                                    </small>
                                                </h6>
                                            </div>

                                            @php
                                                $groupedByProyek = $biayasPerKantor->groupBy('kontrak.proyek_id');
                                            @endphp

                                            @foreach($groupedByProyek as $proyekId => $biayasPerProyek)
                                                @php $proyek = $biayasPerProyek->first()->kontrak->proyek; @endphp
                                                <div class="proyek-group" data-proyek="{{ $proyekId }}">
                                                    <div class="p-3 border-bottom bg-white">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <h6 class="mb-0 text-success">
                                                                <i class="bi bi-diagram-3 me-2"></i>
                                                                {{ $proyek->nama_proyek }}
                                                            </h6>
                                                            <div class="text-end">
                                                                <small class="text-muted d-block">
                                                                    Kontrak: Rp {{ number_format($biayasPerProyek->first()->kontrak->nilai_kontrak, 0, ',', '.') }}
                                                                </small>
                                                                <small class="text-primary fw-bold">
                                                                    Total: Rp {{ number_format($biayasPerProyek->sum('nominal_realisasi'), 0, ',', '.') }}
                                                                </small>
                                                            </div>
                                                        </div>

                                                        <!-- Progress Bar Overall -->
                                                        @php
                                                            $totalProgressProyek = $biayasPerProyek->sum('progress');
                                                            $totalRealisasiProyek = $biayasPerProyek->sum('nominal_realisasi');
                                                            $nilaiKontrak = $biayasPerProyek->first()->kontrak->nilai_kontrak;
                                                            $percentage = $nilaiKontrak > 0 ? ($totalRealisasiProyek / $nilaiKontrak) * 100 : 0;
                                                        @endphp
                                                        <div class="mb-3">
                                                            <div class="d-flex justify-content-between mb-1">
                                                                <small class="text-muted">Persentase Keseluruhan</small> <!-- Ganti Progress jadi Persentase -->
                                                                <small class="text-muted">{{ number_format($percentage, 1) }}%</small>
                                                            </div>
                                                            <div class="progress" style="height: 8px;">
                                                                <div class="progress-bar bg-success" role="progressbar"
                                                                     style="width: {{ $percentage }}%;"
                                                                     aria-valuenow="{{ $percentage }}"
                                                                     aria-valuemin="0"
                                                                     aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Detail Termin -->
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-hover mb-0">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th width="80">Termin</th>
                                                                        <th width="100">Jenis</th>
                                                                        <th width="120">Persentase</th> <!-- Ganti Progress jadi Persentase -->
                                                                        <th width="150">Nominal</th> <!-- Hapus Tanggal, ganti Nominal -->
                                                                        <th width="100" class="text-center">Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($biayasPerProyek->sortBy('termin') as $b)
                                                                    <tr>
                                                                        <td class="fw-bold text-primary">#{{ $b->termin }}</td>
                                                                        <td>
                                                                            @if ($b->jenis == 'Pelaksanaan')
                                                                                <span class="badge bg-success bg-opacity-25 text-success border border-success">
                                                                                    <i class="bi bi-gear-fill me-1"></i> {{ $b->jenis }}
                                                                                </span>
                                                                            @else
                                                                                <span class="badge bg-warning bg-opacity-25 text-warning border border-warning">
                                                                                    <i class="bi bi-tools me-1"></i> {{ $b->jenis }}
                                                                                </span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <div class="d-flex align-items-center">
                                                                                <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                                                    <div class="progress-bar bg-primary" style="width: {{ $b->progress }}%"></div>
                                                                                </div>
                                                                                <small class="fw-bold">{{ $b->progress }}%</small>
                                                                            </div>
                                                                        </td>
                                                                        <td class="fw-bold text-success">
                                                                            Rp {{ number_format($b->nominal_realisasi, 0, ',', '.') }}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <div class="btn-group btn-group-sm">
                                                                                <a href="{{ route('biaya.edit', $b->id) }}"
                                                                                   class="btn btn-outline-warning btn-sm"
                                                                                   title="Edit">
                                                                                    <i class="bi bi-pencil"></i>
                                                                                </a>
                                                                                <button class="btn btn-outline-danger btn-sm"
                                                                                        onclick="confirmDelete({{ $b->id }})"
                                                                                        title="Hapus">
                                                                                    <i class="bi bi-trash"></i>
                                                                                </button>
                                                                                <form id="delete-form-{{ $b->id }}"
                                                                                      action="{{ route('biaya.destroy', $b->id) }}"
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
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Empty State -->
                @if($biayas->count() == 0)
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">Tidak ada data masa pelaksanaan</h4> <!-- Ganti teks -->
                    <p class="text-muted">Mulai dengan menambahkan data pertama Anda</p>
                    <a href="{{ route('biaya.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Data
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .year-month-group {
            transition: all 0.3s ease;
        }
        .kantor-group {
            transition: all 0.3s ease;
        }
        .proyek-group {
            transition: all 0.3s ease;
        }
        .table th {
            font-weight: 600;
            font-size: 0.85rem;
        }
        .progress {
            border-radius: 10px;
        }
        .progress-bar {
            border-radius: 10px;
        }
        .badge {
            font-size: 0.7rem;
        }
        .card-header {
            border-radius: 0.5rem 0.5rem 0 0 !important;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log('🚀 Masa Pelaksanaan Index - DOM Ready');

            // Filter functionality menggunakan vanilla JavaScript
            const yearFilter = document.getElementById('yearFilter');
            const monthFilter = document.getElementById('monthFilter');
            const kantorFilter = document.getElementById('kantorFilter');
            const proyekFilter = document.getElementById('proyekFilter');

            if (yearFilter) yearFilter.addEventListener('change', filterData);
            if (monthFilter) monthFilter.addEventListener('change', filterData);
            if (kantorFilter) kantorFilter.addEventListener('change', filterData);
            if (proyekFilter) proyekFilter.addEventListener('change', filterData);

            function filterData() {
                const year = yearFilter ? yearFilter.value : '';
                const month = monthFilter ? monthFilter.value : '';
                const kantor = kantorFilter ? kantorFilter.value : '';
                const proyek = proyekFilter ? proyekFilter.value : '';

                const yearMonthGroups = document.querySelectorAll('.year-month-group');

                yearMonthGroups.forEach(group => {
                    const groupYear = group.getAttribute('data-year');
                    const groupMonth = group.getAttribute('data-month');
                    let showGroup = true;

                    if (year && groupYear !== year) showGroup = false;
                    if (month && groupMonth !== month) showGroup = false;

                    if (showGroup) {
                        group.style.display = 'block';

                        // Filter kantor within group
                        let kantorVisible = false;
                        const kantorGroups = group.querySelectorAll('.kantor-group');

                        kantorGroups.forEach(kantorGroup => {
                            const kantorId = kantorGroup.getAttribute('data-kantor');
                            const showKantor = !kantor || kantorId === kantor;

                            if (showKantor) {
                                kantorGroup.style.display = 'block';
                                kantorVisible = true;

                                // Filter proyek within kantor
                                let proyekVisible = false;
                                const proyekGroups = kantorGroup.querySelectorAll('.proyek-group');

                                proyekGroups.forEach(proyekGroup => {
                                    const proyekId = proyekGroup.getAttribute('data-proyek');
                                    const showProyek = !proyek || proyekId === proyek;

                                    if (showProyek) {
                                        proyekGroup.style.display = 'block';
                                        proyekVisible = true;
                                    } else {
                                        proyekGroup.style.display = 'none';
                                    }
                                });

                                // Hide kantor if no visible proyek
                                if (!proyekVisible) {
                                    kantorGroup.style.display = 'none';
                                }
                            } else {
                                kantorGroup.style.display = 'none';
                            }
                        });

                        // Hide group if no visible kantor
                        if (!kantorVisible) {
                            group.style.display = 'none';
                        }
                    } else {
                        group.style.display = 'none';
                    }
                });
            }

            // Toggle group collapse
            const toggleButtons = document.querySelectorAll('.toggle-group');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const icon = this.querySelector('i');
                    if (icon.classList.contains('bi-chevron-down')) {
                        icon.classList.remove('bi-chevron-down');
                        icon.classList.add('bi-chevron-up');
                    } else {
                        icon.classList.remove('bi-chevron-up');
                        icon.classList.add('bi-chevron-down');
                    }
                });
            });
        });

        function confirmDelete(id) {
            if (typeof Swal === 'undefined') {
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    document.getElementById('delete-form-' + id).submit();
                }
                return;
            }

            Swal.fire({
                title: "Konfirmasi Hapus",
                text: "Apakah Anda yakin ingin menghapus data ini?",
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
</x-layout>
