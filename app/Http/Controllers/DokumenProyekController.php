<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\DokumenProyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DokumenProyekController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua proyek yang sudah selesai (semua kontrak mencapai 100% di kedua aspek)
        $proyeks = Proyek::with(['kantor', 'kontraks.realisasis', 'kontraks.biayas', 'dokumen'])
            ->get()
            ->filter(function($proyek) {
                return $proyek->ready_for_dokumen;
            });

        // Proyek yang butuh dokumen (sudah 100% tapi dokumen belum lengkap)
        $proyekButuhDokumen = $proyeks->filter(function($proyek) {
            return !$proyek->dokumen_lengkap;
        });

        // Proyek dengan dokumen lengkap
        $proyekLengkap = $proyeks->filter(function($proyek) {
            return $proyek->dokumen_lengkap;
        });

        return view('dokumen.index', compact('proyeks', 'proyekButuhDokumen', 'proyekLengkap'));
    }

    public function create(Proyek $proyek)
    {
        // Load data yang diperlukan
        $proyek->load(['kontraks.realisasis', 'kontraks.biayas', 'dokumen']);

        // Cek apakah proyek ready untuk upload dokumen
        if (!$proyek->ready_for_dokumen) {
            $detail = "Progress Summary:\n";
            $detail .= "Total Kontrak: {$proyek->progress_summary['total_kontrak']}\n";
            $detail .= "Kontrak Complete: {$proyek->progress_summary['kontrak_complete']}\n";
            $detail .= "Kontrak Incomplete: {$proyek->progress_summary['kontrak_incomplete']}\n\n";

            $detail .= "Detail Progress:\n";
            foreach ($proyek->progress_detail as $item) {
                $status = $item['is_complete'] ? '✓' : '✗';
                $detail .= "{$status} {$item['kontrak']}: Fisik {$item['progress_fisik']}%, Biaya {$item['progress_biaya']}% (R:{$item['realisasi_count']}, B:{$item['biaya_count']})\n";
            }

            return redirect()->back()
                ->with('error', "Proyek belum selesai 100%.\n{$detail}");
        }

        return view('dokumen.create', compact('proyek'));
    }

    public function store(Request $request, Proyek $proyek)
    {
        // Validasi
        $request->validate([
            'kontrak_id' => 'required|exists:kontraks,id',
            'jenis_dokumen' => 'required|in:bast_i,bast_ii,kwitansi,spp',
            'dokumen' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'keterangan' => 'nullable|string|max:500'
        ]);

        // Cek apakah kontrak belongs to proyek
        $kontrak = $proyek->kontraks()->findOrFail($request->kontrak_id);

        // Cek apakah sudah ada dokumen dengan jenis yang sama untuk kontrak yang sama
        $existingDokumen = DokumenProyek::where('proyek_id', $proyek->id)
            ->where('kontrak_id', $kontrak->id)
            ->where('jenis_dokumen', $request->jenis_dokumen)
            ->first();

        if ($existingDokumen) {
            // Hapus file lama
            Storage::delete('dokumen-proyek/' . $existingDokumen->nama_file);
            $existingDokumen->delete();
        }

        // Upload file dengan sanitasi nama file
        $file = $request->file('dokumen');
        $sanitizedOriginalName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
        $filename = time() . '_' . $sanitizedOriginalName;
        $path = $file->storeAs('dokumen-proyek', $filename);

        // Simpan ke database
        DokumenProyek::create([
            'proyek_id' => $proyek->id,
            'kontrak_id' => $kontrak->id,
            'jenis_dokumen' => $request->jenis_dokumen,
            'nama_file' => $filename,
            'original_filename' => $sanitizedOriginalName,
            'file_size' => $file->getSize(),
            'keterangan' => $request->keterangan,
            'tanggal_upload' => now(),
        ]);

        return redirect()->route('dokumen.index')
            ->with('success', 'Dokumen berhasil diupload!');
    }

    public function show(Proyek $proyek)
    {
        $proyek->load(['kontraks', 'dokumen.kontrak']);

        return view('dokumen.show', compact('proyek'));
    }

    public function destroy(DokumenProyek $dokumen)
    {
        // Hapus file dari storage
        Storage::delete('dokumen-proyek/' . $dokumen->nama_file);

        // Hapus dari database
        $dokumen->delete();

        return redirect()->back()
            ->with('success', 'Dokumen berhasil dihapus!');
    }

    public function download(DokumenProyek $dokumen)
    {
        return Storage::download('dokumen-proyek/' . $dokumen->nama_file, $dokumen->original_filename);
    }
}
