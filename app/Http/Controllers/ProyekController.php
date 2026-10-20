<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\Kantor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProyekController extends Controller
{
    public function index()
    {
        $proyeks = Proyek::with('kantor')->get();
        return view('proyek.index', compact('proyeks'));
    }

    public function create()
    {
        $kantors = Kantor::all();
        return view('proyek.create', compact('kantors'));
    }


    public function downloadIzin(Proyek $proyek)
{
    if (!$proyek->izin_prinsip_file) {
        return redirect()->back()->with('error', '❌ File izin prinsip tidak ditemukan.');
    }

    // Path lengkap file
    $filePath = storage_path('app/public/' . $proyek->izin_prinsip_file);

    // Cek apakah file ada
    if (!file_exists($filePath)) {
        // Coba cari dengan basename (antisipasi path ganda)
        $fileName = basename($proyek->izin_prinsip_file);
        $altPath = storage_path('app/public/izin_prinsip/' . $fileName);

        if (file_exists($altPath)) {
            $filePath = $altPath;
        } else {
            return redirect()->back()->with('error', '❌ File tidak ditemukan di server.');
        }
    }

    // Ambil nama file asli (tanpa path)
    $originalName = basename($proyek->izin_prinsip_file);

    return response()->file($filePath, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $originalName . '"'
    ]);
}

    public function store(Request $request)
    {
        $request->validate([
            'kantor_id'   => 'required|exists:kantors,id',
            'nama_proyek' => 'required|string|max:255',
            'izin_prinsip'=> 'nullable|string',
            'deskripsi'   => 'nullable|string',
            'izin_prinsip_file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('izin_prinsip_file')) {
            $filePath = $request->file('izin_prinsip_file')->store('izin_prinsip', 'public');
        }

        Proyek::create([
            'kantor_id'         => $request->kantor_id,
            'nama_proyek'       => $request->nama_proyek,
            'izin_prinsip'      => $request->izin_prinsip,
            'deskripsi'         => $request->deskripsi,
            'izin_prinsip_file' => $filePath,
        ]);

        return redirect()->route('proyek.index')->with('success', 'Proyek berhasil ditambahkan');
    }

    public function update(Request $request, Proyek $proyek)
    {
        $request->validate([
            'kantor_id'   => 'required|exists:kantors,id',
            'nama_proyek' => 'required|string|max:255',
            'izin_prinsip'=> 'nullable|string',
            'deskripsi'   => 'nullable|string',
            'izin_prinsip_file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $data = $request->only(['kantor_id','nama_proyek','izin_prinsip','deskripsi']);

        // Handle file upload - hanya update jika ada file baru
        if ($request->hasFile('izin_prinsip_file')) {
            // hapus file lama jika ada
            if ($proyek->izin_prinsip_file && Storage::disk('public')->exists($proyek->izin_prinsip_file)) {
                Storage::disk('public')->delete($proyek->izin_prinsip_file);
            }
            $data['izin_prinsip_file'] = $request->file('izin_prinsip_file')->store('izin_prinsip', 'public');
        } elseif ($request->has('hapus_file') && $request->hapus_file) {
            // Jika checkbox hapus file dicentang
            if ($proyek->izin_prinsip_file && Storage::disk('public')->exists($proyek->izin_prinsip_file)) {
                Storage::disk('public')->delete($proyek->izin_prinsip_file);
            }
            $data['izin_prinsip_file'] = null;
        }
        // Jika tidak ada file baru dan tidak hapus file, pertahankan file yang ada

        $proyek->update($data);

        return redirect()->route('proyek.index')->with('success', 'Proyek berhasil diperbarui');
    }

    public function edit(Proyek $proyek)
    {
        $kantors = Kantor::all();
        return view('proyek.edit', compact('proyek','kantors'));
    }

    public function show(Proyek $proyek)
    {
        $proyek->load('kantor');
        return view('proyek.show', compact('proyek'));
    }

    public function destroy(Proyek $proyek)
    {
        // hapus file juga biar tidak numpuk
        if ($proyek->izin_prinsip_file && Storage::disk('public')->exists($proyek->izin_prinsip_file)) {
            Storage::disk('public')->delete($proyek->izin_prinsip_file);
        }

        $proyek->delete();

        return redirect()->route('proyek.index')->with('success', 'Proyek berhasil dihapus');
    }
}
