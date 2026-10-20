<?php

namespace App\Http\Controllers;

use App\Models\Biaya;
use App\Models\Kantor;
use App\Models\Kontrak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BiayaController extends Controller
{
    public function getKontrak($kantorId)
    {
        Log::info('getKontrak called with kantorId: ' . $kantorId);

        try {
            $kontraks = Kontrak::whereHas('proyek', function ($q) use ($kantorId) {
                    $q->where('kantor_id', $kantorId);
                })
                ->with('proyek')
                ->select('id', 'nomor_spk', 'nilai_kontrak', 'proyek_id', 'bagian')
                ->get()
                ->map(function($kontrak) {
                    return [
                        'id' => $kontrak->id,
                        'nomor_spk' => $kontrak->nomor_spk,
                        'nilai_kontrak' => $kontrak->nilai_kontrak,
                        'proyek_nama' => $kontrak->proyek->nama_proyek ?? 'N/A',
                        'bagian' => $kontrak->bagian
                    ];
                });

            Log::info('Found kontraks: ' . $kontraks->count());

            return response()->json($kontraks);

        } catch (\Exception $e) {
            Log::error('Error in getKontrak: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    public function index()
    {
        $biayas = Biaya::with(['kantor', 'kontrak.proyek'])
            ->orderBy('tanggal_realisasi', 'desc')
            ->orderBy('kantor_id')
            ->orderBy('kontrak_id')
            ->orderBy('termin')
            ->get();

        $years = Biaya::selectRaw('YEAR(tanggal_realisasi) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $months = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        $kantors = Kantor::orderBy('nama_kantor')->get();
        $proyeks = \App\Models\Proyek::orderBy('nama_proyek')->get();

        $totalRealisasi = $biayas->sum('nominal_realisasi');
        $averageProgress = $biayas->avg('progress');
        $totalTermin = $biayas->count();
        $totalProyek = $biayas->groupBy('kontrak.proyek_id')->count();

        return view('biaya.index', compact(
            'biayas', 'years', 'months', 'kantors', 'proyeks',
            'totalRealisasi', 'averageProgress', 'totalTermin', 'totalProyek'
        ));
    }

    public function create()
    {
        $kantors = Kantor::all();
        return view('biaya.create', compact('kantors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kontrak_id' => 'required|exists:kontraks,id',
            'kantor_id' => 'required|exists:kantors,id',
            'jenis.*' => 'required|in:Masa Pelaksanaan,Masa Pemeliharaan',
            'termin.*' => 'required|integer|min:1',
            'progress.*' => 'required|numeric|min:0|max:100',
            'tanggal_realisasi.*' => 'required|date|before_or_equal:today',
            'nominal_realisasi.*' => 'required|numeric|min:0',
        ], [
            'tanggal_realisasi.*.before_or_equal' => 'Tanggal realisasi tidak boleh melebihi hari ini',
            'progress.*.max' => 'Progress tidak boleh lebih dari 100%',
        ]);

        DB::beginTransaction();
        try {
            $kontrak = Kontrak::findOrFail($request->kontrak_id);

            // Validasi 1: Total progress tidak boleh lebih dari 100%
            $totalProgress = array_sum($request->progress);
            if ($totalProgress > 100) {
                return back()->withErrors(['progress' => 'Total progress semua termin tidak boleh melebihi 100%!'])->withInput();
            }

            // Validasi 2: Termin harus unik
            if (count($request->termin) !== count(array_unique($request->termin))) {
                return back()->withErrors(['termin' => 'Terdapat termin duplikat. Harus unik untuk kontrak yang sama.'])->withInput();
            }

            // Validasi 3: Cek termin sudah ada di database
            $existingTermin = Biaya::where('kontrak_id', $request->kontrak_id)
                ->whereIn('termin', $request->termin)
                ->exists();

            if ($existingTermin) {
                return back()->withErrors(['termin' => 'Beberapa termin sudah digunakan untuk kontrak ini!'])->withInput();
            }

            // Validasi 4: Progress harus sesuai dengan nilai kontrak
            $totalRealisasi = 0;
            foreach ($request->progress as $i => $progress) {
                $nominal = ($progress / 100) * $kontrak->nilai_kontrak;
                $totalRealisasi += $nominal;
            }

            // Simpan data
            foreach ($request->jenis as $i => $jenis) {
                $progress = $request->progress[$i];
                $termin = $request->termin[$i];
                $tanggal = $request->tanggal_realisasi[$i];
                $nominal = ($progress / 100) * $kontrak->nilai_kontrak;

                Biaya::create([
                    'kontrak_id' => $kontrak->id,
                    'kantor_id' => $request->kantor_id,
                    'jenis' => $jenis,
                    'termin' => $termin,
                    'progress' => $progress,
                    'nominal_realisasi' => $nominal,
                    'tanggal_realisasi' => $tanggal,
                ]);
            }

            DB::commit();

            return redirect()->route('biaya.index')->with('success', 'Data masa pelaksanaan berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing biaya: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.'])->withInput();
        }
    }

    public function edit($id)
    {
        $biaya = Biaya::findOrFail($id);
        $kantors = Kantor::orderBy('nama_kantor')->get();

        $semuaBiaya = Biaya::where('kontrak_id', $biaya->kontrak_id)
            ->where('kantor_id', $biaya->kantor_id)
            ->get();

        // HANYA ambil kontrak yang sesuai dengan kantor yang dipilih + dengan relasi proyek
        $kontraks = Kontrak::whereHas('proyek', function($q) use ($biaya) {
                $q->where('kantor_id', $biaya->kantor_id);
            })
            ->with(['proyek' => function($query) {
                $query->select('id', 'nama_proyek', 'kantor_id');
            }])
            ->select('id', 'nomor_spk', 'nilai_kontrak', 'proyek_id', 'bagian', 'mitra')
            ->get();

        return view('biaya.edit', compact('biaya', 'kantors', 'kontraks', 'semuaBiaya'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'kontrak_id' => 'required|exists:kontraks,id',
            'kantor_id' => 'required|exists:kantors,id',
            'jenis.*' => 'required|in:Masa Pelaksanaan,Masa Pemeliharaan',
            'termin.*' => 'required|integer|min:1',
            'progress.*' => 'required|numeric|min:0|max:100',
            'tanggal_realisasi.*' => 'required|date|before_or_equal:today',
            'nominal_realisasi.*' => 'nullable|numeric|min:0',
        ], [
            'tanggal_realisasi.*.before_or_equal' => 'Tanggal realisasi tidak boleh melebihi hari ini',
            'progress.*.max' => 'Progress tidak boleh lebih dari 100%',
        ]);

        DB::beginTransaction();
        try {
            $kontrak = Kontrak::findOrFail($request->kontrak_id);

            // Validasi total progress
            $totalProgress = array_sum($request->progress);
            if ($totalProgress > 100) {
                return back()->withErrors(['progress' => 'Total progress semua termin tidak boleh melebihi 100%!'])->withInput();
            }

            // Validasi termin unik dalam request
            if (count($request->termin) !== count(array_unique($request->termin))) {
                return back()->withErrors(['termin' => 'Terdapat termin duplikat dalam data yang dikirim!'])->withInput();
            }

            // Hapus semua termin lama untuk kontrak & kantor ini
            Biaya::where('kontrak_id', $kontrak->id)
                ->where('kantor_id', $request->kantor_id)
                ->delete();

            // Simpan ulang semua termin yang dikirim
            foreach ($request->jenis as $i => $jenis) {
                $progress = $request->progress[$i];
                $termin = $request->termin[$i];
                $tanggal = $request->tanggal_realisasi[$i];
                $nominal = ($progress / 100) * $kontrak->nilai_kontrak;

                Biaya::create([
                    'kontrak_id' => $kontrak->id,
                    'kantor_id' => $request->kantor_id,
                    'jenis' => $jenis,
                    'termin' => $termin,
                    'progress' => $progress,
                    'nominal_realisasi' => $nominal,
                    'tanggal_realisasi' => $tanggal,
                ]);
            }

            DB::commit();

            return redirect()->route('biaya.index')->with('success', 'Semua data termin masa pelaksanaan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating biaya: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data.'])->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $biaya = Biaya::findOrFail($id);
            $biaya->delete();

            DB::commit();

            return back()->with('success', 'Data masa pelaksanaan berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting biaya: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus data.']);
        }
    }

    // Method tambahan untuk dashboard dan reporting
    public function getSummaryByKantor($kantorId = null)
    {
        $query = Biaya::with('kontrak');

        if ($kantorId) {
            $query->where('kantor_id', $kantorId);
        }

        return $query->selectRaw('
            kantor_id,
            COUNT(*) as total_termin,
            SUM(progress) as total_progress,
            SUM(nominal_realisasi) as total_realisasi,
            AVG(progress) as average_progress
        ')
        ->groupBy('kantor_id')
        ->get();
    }

    public function getProgressByKontrak($kontrakId)
    {
        return Biaya::where('kontrak_id', $kontrakId)
            ->selectRaw('
                SUM(progress) as total_progress,
                SUM(nominal_realisasi) as total_realisasi,
                MAX(tanggal_realisasi) as last_realisasi
            ')
            ->first();
    }
}
