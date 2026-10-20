<div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
    <table id="realisasiTable" class="table align-middle table-hover table-striped">
        <thead class="table-light sticky-top" style="position: sticky; top: 0; z-index: 10; background: #f8f9fa;">
            <tr class="text-primary">
                <th width="5%" class="bg-light">No</th>
                <th width="15%" class="bg-light">No. SPK & Proyek</th>
                <th width="10%" class="bg-light">Kantor</th>
                <th width="15%" class="bg-light">Progress</th>
                <th width="12%" class="bg-light">Nilai Kontrak</th>
                <th width="12%" class="bg-light">Nilai Realisasi</th>
                <th width="8%" class="bg-light">Persentase</th>
                <th width="10%" class="bg-light">Tanggal Realisasi</th>
                <th width="8%" class="bg-light">Status</th>
                <th width="5%" class="bg-light">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($realisasis as $key => $r)
                @php
                    $nilaiKontrak = $r->kontrak->nilai_kontrak ?? 0;
                    $persentaseRealisasi = $nilaiKontrak > 0 ? ($r->nilai_realisasi / $nilaiKontrak) * 100 : 0;
                    $isSelesai = $r->progress_persen >= 100;
                    $isBerjalan = $r->progress_persen > 0 && $r->progress_persen < 100;
                    $statusColor = $isSelesai ? 'success' : ($isBerjalan ? 'warning' : 'secondary');
                    $statusText = $isSelesai ? 'Selesai' : ($isBerjalan ? 'Berjalan' : 'Belum');
                @endphp
                <tr>
                    <td class="fw-bold text-muted text-center">
                        {{ ($realisasis->currentPage() - 1) * $realisasis->perPage() + $key + 1 }}
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold text-dark">
                                <i class="bi bi-file-earmark-text text-primary me-1"></i>
                                {{ $r->kontrak->nomor_spk ?? '-' }}
                            </span>
                            <small class="text-muted">
                                {{ Str::limit($r->kontrak->proyek->nama_proyek ?? 'N/A', 50) }}
                            </small>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark">
                            <i class="bi bi-building me-1"></i>
                            {{ $r->kontrak->proyek->kantor->nama_kantor ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar
                                @if($r->progress_persen >= 100) bg-success
                                @elseif($r->progress_persen >= 50) bg-info
                                @elseif($r->progress_persen > 0) bg-warning
                                @else bg-secondary @endif
                                fw-bold"
                                 role="progressbar"
                                 style="width: {{ $r->progress_persen }}%"
                                 aria-valuenow="{{ $r->progress_persen }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                                 {{ $r->progress_persen }}%
                            </div>
                        </div>
                        <small class="text-muted d-block mt-1">
                            Update: {{ \Carbon\Carbon::parse($r->updated_at)->format('d/m/Y') }}
                        </small>
                    </td>
                    <td class="fw-bold text-dark">
                        Rp {{ number_format($nilaiKontrak, 0, ',', '.') }}
                    </td>
                    <td class="fw-bold text-success">
                        Rp {{ number_format($r->nilai_realisasi, 0, ',', '.') }}
                    </td>
                    <td>
                        <span class="badge bg-{{ $statusColor }}">
                            {{ number_format($persentaseRealisasi, 1) }}%
                        </span>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">
                                {{ \Carbon\Carbon::parse($r->tanggal_realisasi)->format('d/m/Y') }}
                            </span>
                            <small class="text-muted">
                                {{ $r->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-{{ $statusColor }}">
                            <i class="bi
                                @if($isSelesai) bi-check-circle
                                @elseif($isBerjalan) bi-clock
                                @else bi-dash-circle @endif
                                me-1"></i>
                            {{ $statusText }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('realisasi.edit', $r->id) }}"
                               class="btn btn-warning text-white"
                               title="Edit Realisasi">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button class="btn btn-danger"
                                onclick="confirmDelete({{ $r->id }}, '{{ $r->kontrak->nomor_spk ?? 'Realisasi' }}')"
                                title="Hapus Realisasi">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                            <form id="delete-form-{{ $r->id }}"
                                action="{{ route('realisasi.destroy', $r->id) }}"
                                method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center py-4">
                        <div class="text-muted">
                            <i class="bi bi-inbox display-4 d-block mb-2"></i>
                            <strong>Tidak ada data realisasi ditemukan</strong>
                            <div class="small mt-2">Silakan ubah filter atau tambahkan data realisasi baru.</div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Info -->
<div class="row mt-3">
    <div class="col-md-6">
        <div class="text-muted small">
            Menampilkan {{ $realisasis->firstItem() ?? 0 }} - {{ $realisasis->lastItem() ?? 0 }} dari {{ $realisasis->total() }} data
            (Halaman {{ $realisasis->currentPage() }} dari {{ $realisasis->lastPage() }})
        </div>
    </div>
    <div class="col-md-6">
        <div class="d-flex justify-content-end">
            <!-- Pagination akan dimasukkan di sini oleh JavaScript -->
        </div>
    </div>
</div>
