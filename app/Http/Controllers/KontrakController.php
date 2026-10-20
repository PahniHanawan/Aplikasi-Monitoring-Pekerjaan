<?php

namespace App\Http\Controllers;

use App\Models\Kontrak;
use App\Models\Proyek;
use App\Models\Biaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class KontrakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kontraks = Kontrak::with(['proyek.kantor', 'biayas'])
                    ->orderBy('tanggal', 'desc')
                    ->get();

        return view('kontrak.index', compact('kontraks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proyeks = Proyek::with('kantor')->get();
        return view('kontrak.create', compact('proyeks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Bersihkan input currency
            $requestData = $request->all();

            if ($request->filled('pagu_biaya')) {
                $requestData['pagu_biaya'] = clean_currency_input($request->pagu_biaya);
            }

            if ($request->filled('nilai_kontrak')) {
                $requestData['nilai_kontrak'] = clean_currency_input($request->nilai_kontrak);
            }

            // Validasi
            $validator = Validator::make($requestData, [
                // Data Kontrak
                'proyek_id' => 'required|exists:proyeks,id',
                'bagian' => 'required|in:Perencana,Pelaksana,Pengawas,Pengelola Teknis',
                'mitra' => 'required|string|max:255',
                'nomor_spk' => 'required|string|max:100|unique:kontraks,nomor_spk',
                'tanggal' => 'required|date',
                'pagu_biaya' => 'nullable|numeric|min:0',
                'nilai_kontrak' => 'required|numeric|min:1',
                'jangka_waktu' => 'nullable|integer|min:0',
                'masa_pemeliharaan' => 'nullable|integer|min:0',
                'file_spk' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',

                // Data Biaya
                'biaya' => 'sometimes|array|min:1',
                'biaya.*.jenis' => 'required_with:biaya|in:Masa Pelaksanaan,Masa Pemeliharaan',
                'biaya.*.termin' => 'required_with:biaya|integer|min:1',
                'biaya.*.progress' => 'required_with:biaya|numeric|min:0|max:100',
                'biaya.*.tanggal_realisasi' => 'required_with:biaya|date',
            ], [
                'biaya.*.progress.max' => 'Progress tidak boleh lebih dari 100%',
                'nomor_spk.unique' => 'Nomor SPK sudah digunakan',
                'nilai_kontrak.min' => 'Nilai kontrak harus lebih dari 0',
                'biaya.min' => 'Minimal harus ada 1 termin biaya',
                'file_spk.max' => 'Ukuran file maksimal 10MB',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Terdapat kesalahan dalam pengisian form.');
            }

            $validated = $validator->validated();

            // Cek duplikasi bagian
            if (Kontrak::isBagianExists($validated['proyek_id'], $validated['bagian'])) {
                return back()->withInput()
                    ->with('error', "Bagian <strong>{$validated['bagian']}</strong> sudah ada untuk proyek ini!");
            }

            // Validasi total progress biaya
            if (isset($validated['biaya'])) {
                $totalProgress = collect($validated['biaya'])->sum('progress');
                if ($totalProgress > 100) {
                    return back()->withInput()
                        ->with('error', 'Total progress semua termin <strong>tidak boleh melebihi 100%</strong>! Total saat ini: ' . $totalProgress . '%');
                }

                // Validasi termin unik
                $terminList = collect($validated['biaya'])->pluck('termin')->toArray();
                if (count($terminList) !== count(array_unique($terminList))) {
                    return back()->withInput()
                        ->with('error', 'Terdapat termin yang duplikat!');
                }
            }

            // 1. Simpan Kontrak
            $kontrakData = [
                'proyek_id' => $validated['proyek_id'],
                'bagian' => $validated['bagian'],
                'mitra' => $validated['mitra'],
                'nomor_spk' => $validated['nomor_spk'],
                'tanggal' => $validated['tanggal'],
                'pagu_biaya' => $validated['pagu_biaya'] ?? null,
                'nilai_kontrak' => $validated['nilai_kontrak'],
                'jangka_waktu' => $validated['jangka_waktu'] ?? null,
                'masa_pemeliharaan' => $validated['masa_pemeliharaan'] ?? null,
            ];

            // Handle file upload
            if ($request->hasFile('file_spk') && $request->file('file_spk')->isValid()) {
                $file = $request->file('file_spk');
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
                $filePath = $file->storeAs('spk', $filename, 'public');

                $kontrakData['file_spk'] = $filename;
                $kontrakData['original_filename'] = $file->getClientOriginalName();
                $kontrakData['file_size'] = formatFileSize($file->getSize());
            }

            $kontrak = Kontrak::create($kontrakData);

            // 2. Simpan Biaya
            if (isset($validated['biaya']) && !empty($validated['biaya'])) {
                foreach ($validated['biaya'] as $biayaData) {
                    $kontrak->biayas()->create([
                        'kantor_id' => $kontrak->proyek->kantor_id,
                        'jenis' => $biayaData['jenis'],
                        'termin' => $biayaData['termin'],
                        'progress' => $biayaData['progress'],
                        'nominal_realisasi' => ($biayaData['progress'] / 100) * $kontrak->nilai_kontrak,
                        'tanggal_realisasi' => $biayaData['tanggal_realisasi'],
                    ]);
                }
            }

            DB::commit();
                return redirect()->route('kontrak.index')
                ->with('success', '✅ Kontrak berhasil ditambah');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating kontrak: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', '❌ Gagal menyimpan kontrak: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kontrak $kontrak)
    {
        $kontrak->load(['proyek.kantor', 'biayas', 'realisasis', 'proyek.kontraks.biayas']);

        return view('kontrak.show', compact('kontrak'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kontrak $kontrak)
    {
        $proyeks = Proyek::with('kantor')->get();
        $kontrak->load(['biayas']);

        return view('kontrak.edit', compact('kontrak', 'proyeks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kontrak $kontrak)
    {
        DB::beginTransaction();
        try {
            // Bersihkan input currency
            $requestData = $request->all();

            if ($request->filled('pagu_biaya')) {
                $requestData['pagu_biaya'] = clean_currency_input($request->pagu_biaya);
            }

            if ($request->filled('nilai_kontrak')) {
                $requestData['nilai_kontrak'] = clean_currency_input($request->nilai_kontrak);
            }

            $validator = Validator::make($requestData, [
                // Data Kontrak
                'proyek_id' => 'required|exists:proyeks,id',
                'bagian' => 'required|in:Perencana,Pelaksana,Pengawas,Pengelola Teknis',
                'mitra' => 'required|string|max:255',
                'nomor_spk' => 'required|string|max:100|unique:kontraks,nomor_spk,' . $kontrak->id,
                'tanggal' => 'required|date',
                'pagu_biaya' => 'nullable|numeric|min:0',
                'nilai_kontrak' => 'required|numeric|min:1',
                'jangka_waktu' => 'nullable|integer|min:0',
                'masa_pemeliharaan' => 'nullable|integer|min:0',
                'file_spk' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
                'hapus_file' => 'nullable|boolean',

                // Data Biaya
                'biaya' => 'sometimes|array|min:1',
                'biaya.*.id' => 'sometimes|exists:biayas,id',
                'biaya.*.jenis' => 'required_with:biaya|in:Masa Pelaksanaan,Masa Pemeliharaan',
                'biaya.*.termin' => 'required_with:biaya|integer|min:1',
                'biaya.*.progress' => 'required_with:biaya|numeric|min:0|max:100',
                'biaya.*.tanggal_realisasi' => 'required_with:biaya|date',
                'biaya.*._delete' => 'sometimes|boolean',
            ], [
                'biaya.*.progress.max' => 'Progress tidak boleh lebih dari 100%',
                'nomor_spk.unique' => 'Nomor SPK sudah digunakan',
                'nilai_kontrak.min' => 'Nilai kontrak harus lebih dari 0',
                'biaya.min' => 'Minimal harus ada 1 termin biaya',
                'file_spk.max' => 'Ukuran file maksimal 10MB',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Terdapat kesalahan dalam pengisian form.');
            }

            $validated = $validator->validated();

            // Cek duplikasi bagian (kecuali untuk kontrak ini)
            if (Kontrak::isBagianExists($validated['proyek_id'], $validated['bagian'], $kontrak->id)) {
                return back()->withInput()
                    ->with('error', "Bagian <strong>{$validated['bagian']}</strong> sudah ada untuk proyek ini!");
            }

            // Validasi total progress biaya
            if (isset($validated['biaya'])) {
                $biayaToKeep = array_filter($validated['biaya'], function($item) {
                    return !isset($item['_delete']) || !$item['_delete'];
                });

                $totalProgress = collect($biayaToKeep)->sum('progress');
                if ($totalProgress > 100) {
                    return back()->withInput()
                        ->with('error', 'Total progress semua termin <strong>tidak boleh melebihi 100%</strong>! Total saat ini: ' . $totalProgress . '%');
                }

                // Validasi termin unik
                $terminList = collect($biayaToKeep)->pluck('termin')->toArray();
                if (count($terminList) !== count(array_unique($terminList))) {
                    return back()->withInput()
                        ->with('error', 'Terdapat termin yang duplikat!');
                }
            }

            // 1. Update Kontrak
            $kontrakData = [
                'proyek_id' => $validated['proyek_id'],
                'bagian' => $validated['bagian'],
                'mitra' => $validated['mitra'],
                'nomor_spk' => $validated['nomor_spk'],
                'tanggal' => $validated['tanggal'],
                'pagu_biaya' => $validated['pagu_biaya'] ?? null,
                'nilai_kontrak' => $validated['nilai_kontrak'],
                'jangka_waktu' => $validated['jangka_waktu'] ?? null,
                'masa_pemeliharaan' => $validated['masa_pemeliharaan'] ?? null,
            ];

            // Handle file upload
            if ($request->hasFile('file_spk') && $request->file('file_spk')->isValid()) {
                // Hapus file lama
                if ($kontrak->file_spk) {
                    Storage::disk('public')->delete('spk/' . $kontrak->file_spk);
                }

                $file = $request->file('file_spk');
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
                $filePath = $file->storeAs('spk', $filename, 'public');

                $kontrakData['file_spk'] = $filename;
                $kontrakData['original_filename'] = $file->getClientOriginalName();
                $kontrakData['file_size'] = formatFileSize($file->getSize());
            } elseif ($request->has('hapus_file') && $request->hapus_file) {
                // Hapus file jika diminta
                if ($kontrak->file_spk) {
                    Storage::disk('public')->delete('spk/' . $kontrak->file_spk);
                }
                $kontrakData['file_spk'] = null;
                $kontrakData['original_filename'] = null;
                $kontrakData['file_size'] = null;
            }

            $kontrak->update($kontrakData);

            // 2. Update/Delete Biaya
            if (isset($validated['biaya'])) {
                $existingBiayaIds = $kontrak->biayas->pluck('id')->toArray();
                $updatedBiayaIds = [];

                foreach ($validated['biaya'] as $biayaData) {
                    // Skip jika marked for deletion
                    if (isset($biayaData['_delete']) && $biayaData['_delete']) {
                        continue;
                    }

                    // Jika ada ID, update existing
                    if (isset($biayaData['id']) && in_array($biayaData['id'], $existingBiayaIds)) {
                        $biaya = Biaya::find($biayaData['id']);
                        if ($biaya) {
                            $biaya->update([
                                'jenis' => $biayaData['jenis'],
                                'termin' => $biayaData['termin'],
                                'progress' => $biayaData['progress'],
                                'nominal_realisasi' => ($biayaData['progress'] / 100) * $kontrak->nilai_kontrak,
                                'tanggal_realisasi' => $biayaData['tanggal_realisasi'],
                            ]);
                            $updatedBiayaIds[] = $biaya->id;
                        }
                    } else {
                        // Create new biaya
                        $newBiaya = $kontrak->biayas()->create([
                            'kantor_id' => $kontrak->proyek->kantor_id,
                            'jenis' => $biayaData['jenis'],
                            'termin' => $biayaData['termin'],
                            'progress' => $biayaData['progress'],
                            'nominal_realisasi' => ($biayaData['progress'] / 100) * $kontrak->nilai_kontrak,
                            'tanggal_realisasi' => $biayaData['tanggal_realisasi'],
                        ]);
                        $updatedBiayaIds[] = $newBiaya->id;
                    }
                }

                // Hapus biaya yang tidak ada dalam request
                $biayaToDelete = array_diff($existingBiayaIds, $updatedBiayaIds);
                if (!empty($biayaToDelete)) {
                    Biaya::whereIn('id', $biayaToDelete)->delete();
                }
            }

            DB::commit();

            // REDIRECT KE INDEX BUKAN SHOW
            return redirect()->route('kontrak.index')
                ->with('success', '✅ Kontrak berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating kontrak: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', '❌ Gagal memperbarui kontrak: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kontrak $kontrak)
    {
        DB::beginTransaction();
        try {
            // Hapus file SPK
            if ($kontrak->file_spk) {
                Storage::disk('public')->delete('spk/' . $kontrak->file_spk);
            }

            // Hapus biaya terkait
            $kontrak->biayas()->delete();

            $kontrak->delete();
            DB::commit();

            return redirect()->route('kontrak.index')
                ->with('success', '✅ Kontrak berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('kontrak.index')
                ->with('error', '❌ Gagal menghapus kontrak: ' . $e->getMessage());
        }
    }

    /**
     * Download SPK file
     */
    public function downloadSpk(Kontrak $kontrak)
    {
        if (!$kontrak->file_spk) {
            return redirect()->back()->with('error', '❌ File SPK tidak ditemukan.');
        }

        $filePath = storage_path('app/public/spk/' . $kontrak->file_spk);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', '❌ File SPK tidak ditemukan di server.');
        }

        return response()->download($filePath, $kontrak->original_filename);
    }

    /**
     * API: Cek duplikasi bagian
     */
    public function checkBagianDuplicate(Request $request)
    {
        $request->validate([
            'proyek_id' => 'required|exists:proyeks,id',
            'bagian' => 'required|string',
            'kontrak_id' => 'nullable|exists:kontraks,id'
        ]);

        $exists = Kontrak::where('proyek_id', $request->proyek_id)
                        ->where('bagian', $request->bagian)
                        ->when($request->kontrak_id, function($query, $kontrakId) {
                            return $query->where('id', '!=', $kontrakId);
                        })
                        ->exists();

        return response()->json(['exists' => $exists]);
    }

    /**
     * Tambah termin setelah kontrak dibuat
     */
    public function tambahTermin(Request $request, Kontrak $kontrak)
    {
        $request->validate([
            'jenis' => 'required|in:Masa Pelaksanaan,Masa Pemeliharaan',
            'termin' => 'required|integer|min:1',
            'progress' => 'required|numeric|min:0|max:100',
            'tanggal_realisasi' => 'required|date',
        ]);

        // Cek total progress
        $totalProgress = $kontrak->biayas->sum('progress') + $request->progress;
        if ($totalProgress > 100) {
            return back()->with('error', '❌ Total progress tidak boleh melebihi 100%!');
        }

        // Cek termin unik
        $existingTermin = $kontrak->biayas()->where('termin', $request->termin)->exists();
        if ($existingTermin) {
            return back()->with('error', '❌ Termin #' . $request->termin . ' sudah ada!');
        }

        try {
            $kontrak->biayas()->create([
                'kantor_id' => $kontrak->proyek->kantor_id,
                'jenis' => $request->jenis,
                'termin' => $request->termin,
                'progress' => $request->progress,
                'nominal_realisasi' => ($request->progress / 100) * $kontrak->nilai_kontrak,
                'tanggal_realisasi' => $request->tanggal_realisasi,
            ]);

            return back()->with('success', '✅ Termin berhasil ditambahkan');

        } catch (\Exception $e) {
            return back()->with('error', '❌ Gagal menambah termin: ' . $e->getMessage());
        }
    }

    /**
     * Hapus termin biaya
     */
    public function hapusTermin(Biaya $biaya)
    {
        try {
            $biaya->delete();
            return back()->with('success', '✅ Termin berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Gagal menghapus termin: ' . $e->getMessage());
        }
    }

    /**
     * Get kontrak by proyek (API)
     */
    public function getByProyek($proyekId)
    {
        $kontraks = Kontrak::where('proyek_id', $proyekId)
                    ->with(['biayas', 'realisasis'])
                    ->get();

        return response()->json($kontraks);
    }
}
