<x-layout>
    <x-slot:title>{{ $title ?? 'Dashboard - Monitoring Proyek Bulog' }}</x-slot:title>

    <!-- Header Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2 text-gray-800 fw-bold">Dashboard Monitoring</h1>
                    <p class="text-muted mb-0">Overview lengkap proyek dan aktivitas Bulog</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-primary fs-6 px-3 py-2">
                        <i class="bi bi-calendar3 me-2"></i>
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Utama -->
    <div class="row g-3 mb-4">
        @foreach ([
            ['kantorCount', 'primary', 'Total Kantor', 'building', 'Kantor aktif'],
            ['proyekCount', 'success', 'Total Proyek', 'briefcase', 'Proyek berjalan'],
            ['kontrakCount', 'warning', 'Total Kontrak', 'file-earmark-text', 'Kontrak aktif'],
            ['totalNilaiKontrak', 'info', 'Total Nilai Kontrak', 'cash-stack', 'Nilai keseluruhan'],
            ['totalPaguBiaya', 'secondary', 'Total Pagu Biaya', 'pie-chart', 'Anggaran tersedia'],
            ['totalRealisasi', 'danger', 'Total Realisasi', 'graph-up-arrow', 'Realisasi terkini']
        ] as [$key, $color, $label, $bsIcon, $subtitle])
            <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="card stat-card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h3 class="card-title fw-bold mb-2 text-{{ $color }} display-6">
                                    @if(in_array($key, ['totalNilaiKontrak', 'totalPaguBiaya', 'totalRealisasi']))
                                        Rp {{ number_format($$key ?? 0, 0, ',', '.') }}
                                    @else
                                        {{ number_format($$key ?? 0, 0, ',', '.') }}
                                    @endif
                                </h3>
                                <p class="card-text fw-semibold text-dark mb-1">{{ $label }}</p>
                                <p class="text-muted small mb-0">{{ $subtitle }}</p>

                                @if($key === 'totalRealisasi' && isset($rataRataRealisasi))
                                    <div class="progress mt-3" style="height: 6px;">
                                        <div class="progress-bar bg-{{ $color }}"
                                             role="progressbar"
                                             style="width: {{ min($rataRataRealisasi, 100) }}%"
                                             aria-valuenow="{{ $rataRataRealisasi }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <small class="text-muted">Progress</small>
                                        <small class="fw-bold text-{{ $color }}">{{ number_format($rataRataRealisasi, 1) }}%</small>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-shrink-0">
                                <div class="icon-wrapper bg-{{ $color }} bg-opacity-10 rounded-3 p-3">
                                    <i class="bi bi-{{ $bsIcon }} text-{{ $color }} fs-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Charts Section -->
    <div class="row g-4">
        <!-- Main Charts -->
        <div class="col-lg-8">
            <div class="row g-4">
                <!-- Chart Proyek per Kantor -->
                <div class="col-xl-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent border-0 pb-0 pt-3 px-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-1 text-primary fw-bold">
                                        <i class="bi bi-building me-2"></i>Distribusi Proyek
                                    </h5>
                                    <p class="text-muted mb-0 small">Jumlah proyek per kantor</p>
                                </div>
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    {{ array_sum($proyekPerKantor ?? []) }} Total
                                </span>
                            </div>
                        </div>
                        <div class="card-body pt-2 px-3">
                            <div class="chart-container" style="position: relative; height: 280px; width: 100%;">
                                <canvas id="proyekKantorChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Nilai Kontrak per Kantor -->
                <div class="col-xl-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent border-0 pb-0 pt-3 px-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-1 text-success fw-bold">
                                        <i class="bi bi-currency-dollar me-2"></i>Nilai Kontrak
                                    </h5>
                                    <p class="text-muted mb-0 small">Total nilai kontrak per kantor (Miliar)</p>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    Rp {{ number_format(array_sum($nilaiKontrakPerKantor ?? []) * 1000000000, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body pt-2 px-3">
                            <div class="chart-container" style="position: relative; height: 280px; width: 100%;">
                                <canvas id="nilaiKontrakChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Status Proyek -->
                <div class="col-xl-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent border-0 pb-0 pt-3 px-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-1 text-warning fw-bold">
                                        <i class="bi bi-pie-chart me-2"></i>Status Proyek
                                    </h5>
                                    <p class="text-muted mb-0 small">Distribusi status proyek</p>
                                </div>
                                <span class="badge bg-warning bg-opacity-10 text-warning">
                                    {{ array_sum($statusCounts ?? [0,0,0]) }} Proyek
                                </span>
                            </div>
                        </div>
                        <div class="card-body pt-2 px-3">
                            <div class="chart-container" style="position: relative; height: 250px; width: 100%;">
                                <canvas id="statusProyekChart"></canvas>
                            </div>

                            <!-- Status Legend -->
                            <div class="mt-3">
                                <div class="row g-2">
                                    <div class="col-4">
                                        <div class="d-flex align-items-center p-2 rounded bg-light">
                                            <span class="status-indicator bg-secondary me-2"></span>
                                            <div class="flex-grow-1">
                                                <small class="fw-semibold d-block">Belum</small>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold text-dark">{{ $statusCounts[0] ?? 0 }}</span>
                                                    <small class="text-muted">{{ $statusPercentages[0] ?? 0 }}%</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="d-flex align-items-center p-2 rounded bg-light">
                                            <span class="status-indicator bg-warning me-2"></span>
                                            <div class="flex-grow-1">
                                                <small class="fw-semibold d-block">Berjalan</small>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold text-dark">{{ $statusCounts[1] ?? 0 }}</span>
                                                    <small class="text-muted">{{ $statusPercentages[1] ?? 0 }}%</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="d-flex align-items-center p-2 rounded bg-light">
                                            <span class="status-indicator bg-success me-2"></span>
                                            <div class="flex-grow-1">
                                                <small class="fw-semibold d-block">Selesai</small>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold text-dark">{{ $statusCounts[2] ?? 0 }}</span>
                                                    <small class="text-muted">{{ $statusPercentages[2] ?? 0 }}%</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Trend Bulanan -->
                <div class="col-xl-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent border-0 pb-0 pt-3 px-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-1 text-info fw-bold">
                                        <i class="bi bi-graph-up me-2"></i>Trend Kontrak {{ date('Y') }}
                                    </h5>
                                    <p class="text-muted mb-0 small">Kontrak baru per bulan</p>
                                </div>
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    {{ array_sum($trendData ?? array_fill(0, 12, 0)) }} Kontrak
                                </span>
                            </div>
                        </div>
                        <div class="card-body pt-2 px-3">
                            <div class="chart-container" style="position: relative; height: 280px; width: 100%;">
                                <canvas id="trendKontrakChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Informasi -->
        <div class="col-lg-4">
            <div class="d-flex flex-column h-100 gap-3">
                <!-- Top Kantor -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 pb-0 pt-3 px-3">
                        <h5 class="card-title mb-1 text-primary fw-bold">
                            <i class="bi bi-trophy me-2"></i>Top 5 Kantor
                        </h5>
                        <p class="text-muted mb-0 small">Berdasarkan pagu biaya</p>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($topKantors ?? [] as $index => $kantor)
                                <div class="list-group-item border-0 py-2 px-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rank-badge bg-primary text-white rounded-2 me-3 d-flex align-items-center justify-content-center"
                                             style="width: 32px; height: 32px; font-size: 0.8rem; font-weight: 700;">
                                            {{ $index + 1 }}
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold text-dark">{{ $kantor['nama'] }}</h6>
                                            <small class="text-muted">
                                                <i class="bi bi-folder2 me-1"></i>{{ $kantor['proyek_count'] }} proyek
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-success">Rp {{ number_format($kantor['pagu_biaya'] / 1000000000, 1) }}M</div>
                                            <small class="text-muted">Pagu</small>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                    <p class="mb-0 small">Tidak ada data kantor</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Proyek Terbaru -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 pb-0 pt-3 px-3">
                        <h5 class="card-title mb-1 text-success fw-bold">
                            <i class="bi bi-clock-history me-2"></i>Proyek Terbaru
                        </h5>
                        <p class="text-muted mb-0 small">5 proyek terbaru</p>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($recentProyeks ?? [] as $proyek)
                                <div class="list-group-item border-0 py-2 px-3">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1 me-2">
                                            <h6 class="mb-1 fw-semibold text-truncate" title="{{ $proyek['nama'] }}">{{ $proyek['nama'] }}</h6>
                                            <div class="d-flex flex-wrap gap-2 mt-1">
                                                <small class="text-muted">
                                                    <i class="bi bi-building me-1"></i>{{ $proyek['kantor'] }}
                                                </small>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar me-1"></i>{{ $proyek['tanggal'] }}
                                                </small>
                                            </div>
                                        </div>
                                        <span class="badge bg-{{ $proyek['status_color'] }} px-2 py-1">{{ $proyek['status'] }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-folder-x fs-1 d-block mb-2 opacity-50"></i>
                                    <p class="mb-0 small">Tidak ada proyek</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0 pt-3 px-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1 text-primary fw-bold">
                                <i class="bi bi-calendar-event me-2"></i>Kalender Proyek
                            </h5>
                            <p class="text-muted mb-0 small">Jadwal dan deadline proyek</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary rounded-pill" id="prevMonth">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-primary rounded-pill" id="nextMonth">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                            <button class="btn btn-sm btn-primary rounded-pill" id="todayBtn">
                                <i class="bi bi-calendar-check me-1"></i>Hari Ini
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <!-- Calendar -->
                        <div class="col-lg-8">
                            <div id="calendar" style="min-height: 600px;"></div>
                        </div>

                        <!-- Upcoming Events -->
                        <div class="col-lg-4">
                            <div class="sticky-top" style="top: 20px;">
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center py-3">
                                        <h6 class="fw-bold mb-0 text-primary">
                                            <i class="bi bi-bell me-2"></i>Event Mendatang
                                        </h6>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-outline-secondary toggle-events" title="Sembunyikan/Tampilkan">
                                                <i class="bi bi-dash-lg"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary close-events" title="Tutup">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body p-0 events-container">
                                        <div class="upcoming-events" style="max-height: 400px; overflow-y: auto;">
                                            @php
                                                $upcomingEvents = collect($calendarEvents)
                                                    ->filter(function($event) {
                                                        return \Carbon\Carbon::parse($event['start'])->gte(now()->startOfDay());
                                                    })
                                                    ->sortBy('start')
                                                    ->take(10);
                                            @endphp

                                            @forelse($upcomingEvents as $event)
                                                <div class="event-item p-3 border-bottom position-relative">
                                                    <button type="button" class="btn-close btn-close-sm event-close"
                                                            aria-label="Close"
                                                            style="position: absolute; top: 12px; right: 12px; font-size: 0.6rem; padding: 4px;"></button>

                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                                 style="width: 40px; height: 40px; background-color: {{ $event['color'] }};">
                                                                <i class="bi bi-{{ $event['icon'] }} text-white fs-6"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1" style="padding-right: 25px;">
                                                            <h6 class="mb-1 fw-semibold text-dark small lh-sm">{{ Str::limit($event['title'], 40) }}</h6>
                                                            <p class="mb-1 text-muted small lh-sm">{{ $event['description'] }}</p>
                                                            <div class="d-flex align-items-center mt-2">
                                                                <small class="text-primary fw-semibold">
                                                                    <i class="bi bi-calendar me-1"></i>
                                                                    {{ \Carbon\Carbon::parse($event['start'])->translatedFormat('d M Y') }}
                                                                </small>
                                                                @if(isset($event['type']))
                                                                    <small class="badge bg-light text-dark border ms-2" style="font-size: 0.65rem;">{{ ucfirst($event['type']) }}</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center text-muted py-4">
                                                    <i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-50"></i>
                                                    <p class="mb-0 small">Tidak ada event mendatang</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                <!-- Event Legend -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-transparent border-0 py-3">
                                        <h6 class="fw-bold mb-0 text-primary">Keterangan Event</h6>
                                    </div>
                                    <div class="card-body py-3">
                                        <div class="legend-items">
                                            @foreach ([
                                                ['#3b82f6', 'Kontrak/SPK', 'file-earmark-text'],
                                                ['#ef4444', 'Deadline', 'calendar-check'],
                                                ['#10b981', 'Realisasi', 'graph-up'],
                                                ['#f59e0b', 'Biaya', 'currency-dollar'],
                                                ['#8b5cf6', 'Dokumen', 'file-earmark']
                                            ] as [$color, $label, $icon])
                                                <div class="d-flex align-items-center mb-2 p-2 rounded hover-light">
                                                    <div class="flex-shrink-0">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                                             style="width: 24px; height: 24px; background-color: {{ $color }};">
                                                            <i class="bi bi-{{ $icon }} text-white" style="font-size: 0.7rem;"></i>
                                                        </div>
                                                    </div>
                                                    <small class="fw-semibold text-dark">{{ $label }}</small>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Chart Proyek per Kantor
            const ctxProyek = document.getElementById('proyekKantorChart').getContext('2d');
            new Chart(ctxProyek, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($kantorNames ?? []) !!},
                    datasets: [{
                        label: 'Jumlah Proyek',
                        data: {!! json_encode($proyekPerKantor ?? []) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 },
                            grid: { drawBorder: false }
                        },
                        x: {
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // Chart Nilai Kontrak per Kantor
            const ctxNilai = document.getElementById('nilaiKontrakChart').getContext('2d');
            new Chart(ctxNilai, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($kantorNames ?? []) !!},
                    datasets: [{
                        label: 'Nilai Kontrak (Miliar)',
                        data: {!! json_encode($nilaiKontrakPerKantor ?? []) !!},
                        backgroundColor: 'rgba(34, 197, 94, 0.8)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value + 'M';
                                }
                            },
                            grid: { drawBorder: false }
                        },
                        x: {
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // Chart Status Proyek
            const ctxStatus = document.getElementById('statusProyekChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($statusLabels ?? ['Belum Realisasi', 'Sedang Berjalan', 'Selesai']) !!},
                    datasets: [{
                        data: {!! json_encode($statusCounts ?? [0,0,0]) !!},
                        backgroundColor: [
                            'rgba(107, 114, 128, 0.9)',
                            'rgba(245, 158, 11, 0.9)',
                            'rgba(34, 197, 94, 0.9)'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${label}: ${value} proyek (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Chart Trend Kontrak
            const ctxTrend = document.getElementById('trendKontrakChart').getContext('2d');
            new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: {!! json_encode($trendLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']) !!},
                    datasets: [{
                        label: 'Kontrak Baru',
                        data: {!! json_encode($trendData ?? array_fill(0, 12, 0)) !!},
                        backgroundColor: 'rgba(14, 165, 233, 0.1)',
                        borderColor: 'rgba(14, 165, 233, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgba(14, 165, 233, 1)',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 },
                            grid: { drawBorder: false }
                        },
                        x: {
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // Initialize Calendar
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                firstDay: 1,
                headerToolbar: {
                    left: 'title',
                    center: '',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay today prev,next'
                },
                buttonText: {
                    today: 'Hari Ini',
                    month: 'Bulan',
                    week: 'Minggu',
                    day: 'Hari'
                },
                events: {!! json_encode($calendarEvents) !!},
                eventContent: function(arg) {
                    return {
                        html: `
                            <div class="fc-event-content p-1">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-${arg.event.extendedProps.icon} me-1 small"></i>
                                    <small class="fw-semibold">${arg.event.title}</small>
                                </div>
                            </div>
                        `
                    };
                },
                eventClick: function(info) {
                    const event = info.event;
                    const eventDate = new Date(event.start).toLocaleDateString('id-ID', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    Swal.fire({
                        title: event.title,
                        html: `
                            <div class="text-start">
                                <p class="mb-2"><strong>Tanggal:</strong> ${eventDate}</p>
                                <p class="mb-2"><strong>Keterangan:</strong> ${event.extendedProps.description}</p>
                                <p class="mb-0"><strong>Jenis:</strong> ${event.extendedProps.type}</p>
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#3b82f6'
                    });
                }
            });

            calendar.render();

            // Calendar Navigation
            document.getElementById('prevMonth').addEventListener('click', function() {
                calendar.prev();
            });

            document.getElementById('nextMonth').addEventListener('click', function() {
                calendar.next();
            });

            document.getElementById('todayBtn').addEventListener('click', function() {
                calendar.today();
            });

            // Event Mendatang Scroll dan Close Functionality
            function initializeEventFeatures() {
                const eventsContainer = document.querySelector('.events-container');
                const toggleBtn = document.querySelector('.toggle-events');
                const closeBtn = document.querySelector('.close-events');
                const eventCloseBtns = document.querySelectorAll('.event-close');
                const upcomingEvents = document.querySelector('.upcoming-events');

                // Toggle show/hide events
                if (toggleBtn) {
                    toggleBtn.addEventListener('click', function() {
                        if (eventsContainer.style.display === 'none') {
                            eventsContainer.style.display = 'block';
                            this.innerHTML = '<i class="bi bi-dash-lg"></i>';
                            this.title = 'Sembunyikan';
                        } else {
                            eventsContainer.style.display = 'none';
                            this.innerHTML = '<i class="bi bi-plus-lg"></i>';
                            this.title = 'Tampilkan';
                        }
                    });
                }

                // Close seluruh section events
                if (closeBtn) {
                    closeBtn.addEventListener('click', function() {
                        const eventsCard = this.closest('.card');
                        if (eventsCard) {
                            eventsCard.style.display = 'none';

                            // Show restore button
                            setTimeout(() => {
                                if (!document.querySelector('.restore-events')) {
                                    const restoreBtn = document.createElement('button');
                                    restoreBtn.className = 'btn btn-sm btn-outline-primary restore-events';
                                    restoreBtn.innerHTML = '<i class="bi bi-bell me-1"></i>Tampilkan Events';
                                    restoreBtn.style.marginBottom = '15px';
                                    restoreBtn.style.width = '100%';
                                    restoreBtn.style.borderRadius = '8px';
                                    restoreBtn.style.padding = '8px';

                                    const legendCard = document.querySelector('.legend-items').closest('.card');
                                    if (legendCard) {
                                        legendCard.parentNode.insertBefore(restoreBtn, legendCard);
                                    }

                                    restoreBtn.addEventListener('click', function() {
                                        eventsCard.style.display = 'block';
                                        this.remove();
                                    });
                                }
                            }, 300);
                        }
                    });
                }

                // Close individual events
                eventCloseBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const eventItem = this.closest('.event-item');
                        if (eventItem) {
                            // Smooth close animation
                            eventItem.style.transition = 'all 0.3s ease';
                            eventItem.style.opacity = '0';
                            eventItem.style.maxHeight = '0';
                            eventItem.style.paddingTop = '0';
                            eventItem.style.paddingBottom = '0';
                            eventItem.style.margin = '0';
                            eventItem.style.overflow = 'hidden';

                            setTimeout(() => {
                                eventItem.remove();

                                // Check if no events left
                                const remainingEvents = upcomingEvents.querySelectorAll('.event-item');
                                if (remainingEvents.length === 0) {
                                    upcomingEvents.innerHTML = `
                                        <div class="text-center text-muted py-4">
                                            <i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-50"></i>
                                            <p class="mb-0 small">Tidak ada event yang ditampilkan</p>
                                            <button class="btn btn-sm btn-outline-primary mt-2 reset-events">
                                                <i class="bi bi-arrow-clockwise me-1"></i>Reset Events
                                            </button>
                                        </div>
                                    `;

                                    document.querySelector('.reset-events')?.addEventListener('click', function() {
                                        location.reload();
                                    });
                                }
                            }, 300);
                        }
                    });
                });

                // Add hover effects for event items
                document.querySelectorAll('.event-item').forEach(item => {
                    item.addEventListener('mouseenter', function() {
                        this.style.backgroundColor = '#f8fafc';
                    });
                    item.addEventListener('mouseleave', function() {
                        this.style.backgroundColor = '';
                    });
                });
            }

            // Initialize event features
            initializeEventFeatures();
        });
    </script>

    <style>
        .stat-card {
            transition: all 0.3s ease;
            background: white;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 12px;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .icon-wrapper {
            transition: all 0.3s ease;
        }

        .stat-card:hover .icon-wrapper {
            transform: scale(1.1);
        }

        .card {
            background: white;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 12px;
        }

        .list-group-item {
            transition: all 0.2s ease;
            border-color: rgba(226, 232, 240, 0.8);
        }

        .list-group-item:hover {
            background-color: #f8fafc;
        }

        .rank-badge {
            transition: all 0.3s ease;
        }

        .list-group-item:hover .rank-badge {
            transform: scale(1.1);
        }

        .progress {
            background-color: #e2e8f0;
            border-radius: 6px;
        }

        .progress-bar {
            transition: width 0.6s ease;
            border-radius: 6px;
        }

        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }

        /* Calendar Styles */
        .fc {
            font-family: inherit;
        }

        .fc .fc-toolbar-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1f2937;
        }

        .fc .fc-button {
            background-color: #3b82f6;
            border-color: #3b82f6;
            font-weight: 500;
            border-radius: 6px;
        }

        .fc .fc-button:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .fc .fc-daygrid-day-number {
            color: #374151;
            font-weight: 500;
        }

        .fc .fc-day-today {
            background-color: rgba(59, 130, 246, 0.1) !important;
        }

        .fc-event {
            border: none;
            border-radius: 4px;
            padding: 2px 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* Event Styles */
        .event-item {
            transition: all 0.3s ease;
            position: relative;
        }

        .event-item:hover {
            background-color: #f8fafc;
        }

        .event-close {
            opacity: 0.5;
            transition: all 0.2s ease;
            border: none;
            background: transparent;
        }

        .event-close:hover {
            opacity: 1;
            background-color: rgba(0, 0, 0, 0.1);
        }

        .event-item:hover .event-close {
            opacity: 0.7;
        }

        .legend-color {
            display: inline-block;
            width: 14px;
            height: 14px;
            border-radius: 3px;
        }

        .hover-light:hover {
            background-color: #f8fafc;
        }

        /* Custom Scrollbar untuk Events */
        .upcoming-events {
            scrollbar-width: thin;
            scrollbar-color: #c1c1c1 #f1f1f1;
        }

        .upcoming-events::-webkit-scrollbar {
            width: 6px;
        }

        .upcoming-events::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .upcoming-events::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .upcoming-events::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Button Styles */
        .toggle-events,
        .close-events {
            border: none;
            background: transparent;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
            font-size: 0.8rem;
        }

        .toggle-events:hover,
        .close-events:hover {
            background-color: #f1f1f1;
        }

        .restore-events {
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .restore-events:hover {
            transform: translateY(-1px);
        }
    </style>
</x-layout>
