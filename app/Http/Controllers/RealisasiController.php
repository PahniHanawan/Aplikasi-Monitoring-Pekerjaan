<?php

namespace App\Http\Controllers;

use App\Models\Realisasi;
use App\Models\Kontrak;
use App\Models\Kantor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log; // TAMBAHKAN INI

class RealisasiController extends Controller
{
    public function index(Request $request)
    {
        // Data dropdown filter (cache 1 jam)
        $kantorList = Cache::remember('kantor_list', 3600, function () {
            return Kantor::orderBy('nama_kantor')->pluck('nama_kantor', 'id');
        });

        $tahunList = Cache::remember('tahun_list', 3600, function () {
            return Realisasi::selectRaw('YEAR(tanggal_realisasi) as tahun')
                ->distinct()
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');
        });

        $bulanList = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        // Jika request AJAX → panggil filter
        if ($request->ajax()) {
            return $this->getFilteredData($request);
        }

        // Load awal dengan pagination
        $realisasis = Realisasi::with(['kontrak.proyek.kantor'])
            ->orderBy('tanggal_realisasi', 'desc')
            ->paginate(100);

        // Hitung statistik
        $stats = $this->calculateStatistics($realisasis);

        return view('realisasi.index', compact(
            'realisasis',
            'kantorList',
            'tahunList',
            'bulanList'
        ))->with($stats);
    }

    private function getFilteredData(Request $request)
    {
        try {
            $query = Realisasi::with(['kontrak.proyek.kantor']);

            // Pencarian umum
            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('progress_persen', 'LIKE', "%{$search}%")
                        ->orWhere('nilai_realisasi', 'LIKE', "%{$search}%")
                        ->orWhere('keterangan', 'LIKE', "%{$search}%")
                        ->orWhereHas('kontrak', function ($q2) use ($search) {
                            $q2->where('nomor_spk', 'LIKE', "%{$search}%")
                                ->orWhere('mitra', 'LIKE', "%{$search}%")
                                ->orWhere('nilai_kontrak', 'LIKE', "%{$search}%");
                        })
                        ->orWhereHas('kontrak.proyek', function ($q3) use ($search) {
                            $q3->where('nama_proyek', 'LIKE', "%{$search}%")
                                ->orWhere('deskripsi', 'LIKE', "%{$search}%");
                        })
                        ->orWhereHas('kontrak.proyek.kantor', function ($q4) use ($search) {
                            $q4->where('nama_kantor', 'LIKE', "%{$search}%")
                                ->orWhere('alamat', 'LIKE', "%{$search}%");
                        });
                });
            }

            // Filter status progress
            if ($request->filled('status')) {
                $status = $request->status;
                if ($status === 'selesai') {
                    $query->where('progress_persen', '>=', 100);
                } elseif ($status === 'berjalan') {
                    $query->whereBetween('progress_persen', [1, 99]);
                } elseif ($status === 'belum') {
                    $query->where('progress_persen', '=', 0);
                }
            }

            // Filter berdasarkan kantor
            if ($request->filled('kantor_id')) {
                $query->whereHas('kontrak.proyek.kantor', function ($q) use ($request) {
                    $q->where('id', $request->kantor_id);
                });
            }

            // Filter tahun
            if ($request->filled('tahun')) {
                $query->whereYear('tanggal_realisasi', $request->tahun);
            }

            // Filter bulan
            if ($request->filled('bulan')) {
                $query->whereMonth('tanggal_realisasi', $request->bulan);
            }

            // Pagination dan sorting
            $realisasis = $query->orderBy('tanggal_realisasi', 'desc')->paginate(50);

            // Hitung statistik
            $stats = $this->calculateStatistics($realisasis);

            // Render partial view
            $html = view('realisasi.partials.table', compact('realisasis'))->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'stats' => $stats,
                'pagination' => (string) $realisasis->links(),
                'search_count' => $realisasis->total(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Error getFilteredData: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses data.',
            ], 500);
        }
    }

    private function calculateStatistics($realisasis)
    {
        $collection = $realisasis instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $realisasis->getCollection()
            : $realisasis;

        return [
            'totalRealisasi' => (float) $collection->sum('nilai_realisasi'),
            'totalData' => $collection->count(),
            'proyekSelesai' => $collection->where('progress_persen', '>=', 100)->count(),
            'proyekBerjalan' => $collection->where('progress_persen', '>', 0)
                ->where('progress_persen', '<', 100)
                ->count(),
        ];
    }

    public function create()
    {
        $kontraks = Kontrak::with(['proyek.kantor'])
            ->whereDoesntHave('realisasis', function ($query) {
                $query->where('progress_persen', '>=', 100);
            })
            ->orderBy('nomor_spk')
            ->get();

        return view('realisasi.create', compact('kontraks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kontrak_id' => 'required|exists:kontraks,id',
            'progress_persen' => 'required|numeric|min:0|max:100',
            'nilai_realisasi' => 'required|numeric|min:0',
            'tanggal_realisasi' => 'required|date',
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $existingRealisasi = Realisasi::where('kontrak_id', $request->kontrak_id)->first();

            if ($existingRealisasi) {
                return back()->withInput()->with('error', 'Kontrak ini sudah memiliki realisasi. Gunakan fitur edit untuk mengubah realisasi.');
            }

            $realisasi = Realisasi::create($request->only([
                'kontrak_id',
                'progress_persen',
                'nilai_realisasi',
                'tanggal_realisasi',
                'keterangan',
            ]));

            DB::commit();

            return redirect()->route('realisasi.index')->with('success', 'Realisasi berhasil ditambahkan!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error store Realisasi: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Gagal menambahkan realisasi. Silakan periksa kembali data Anda atau hubungi administrator.');
        }
    }

    public function edit(Realisasi $realisasi)
    {
        $kontraks = Kontrak::with(['proyek.kantor'])
            ->where(function($query) use ($realisasi) {
                $query->where('id', $realisasi->kontrak_id)
                      ->orWhereDoesntHave('realisasis', function($q) {
                          $q->where('progress_persen', '>=', 100);
                      });
            })
            ->orderBy('nomor_spk')
            ->get();

        return view('realisasi.edit', compact('realisasi', 'kontraks'));
    }

    public function update(Request $request, Realisasi $realisasi)
    {
        $request->validate([
            'kontrak_id' => 'required|exists:kontraks,id',
            'progress_persen' => 'required|numeric|min:0|max:100',
            'nilai_realisasi' => 'required|numeric|min:0',
            'tanggal_realisasi' => 'required|date',
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $realisasi->update($request->only([
                'kontrak_id',
                'progress_persen',
                'nilai_realisasi',
                'tanggal_realisasi',
                'keterangan',
            ]));

            DB::commit();

            return redirect()->route('realisasi.index')->with('success', 'Realisasi berhasil diupdate!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error update Realisasi: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Gagal mengupdate realisasi. Silakan coba lagi.');
        }
    }

    public function destroy(Realisasi $realisasi)
    {
        try {
            DB::beginTransaction();

            $realisasi->delete();

            DB::commit();
            return redirect()->route('realisasi.index')->with('success', 'Realisasi berhasil dihapus!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error destroy Realisasi: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('realisasi.index')->with('error', 'Gagal menghapus realisasi. Silakan coba lagi.');
        }
    }

    public function getKontrakData($id)
    {
        $kontrak = Kontrak::with('proyek')->find($id);

        if (!$kontrak) {
            return response()->json(['error' => 'Kontrak tidak ditemukan'], 404);
        }

        return response()->json([
            'nilai_kontrak' => $kontrak->nilai_kontrak,
            'proyek' => $kontrak->proyek->nama_proyek ?? 'N/A',
            'mitra' => $kontrak->mitra,
        ]);
    }
}
