<?php

namespace App\Http\Controllers;

use App\Models\Kantor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KantorController extends Controller
{
    public function index()
    {
        $kantors = Kantor::withCount(['proyeks', 'biayas'])
                    ->orderBy('nama_kantor')
                    ->get()
                    ->map(function($kantor) {
                        // Hitung total nilai kontrak manual
                        $totalNilaiKontrak = DB::table('kontraks')
                            ->join('proyeks', 'kontraks.proyek_id', '=', 'proyeks.id')
                            ->where('proyeks.kantor_id', $kantor->id)
                            ->sum('kontraks.nilai_kontrak');

                        $kantor->total_nilai_proyek = $totalNilaiKontrak;
                        return $kantor;
                    });

        return view('kantor.index', compact('kantors'));
    }

    public function create()
    {
        return view('kantor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kantor' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'unique:kantors,nama_kantor',
                'regex:/^[a-zA-Z0-9\s\-\/\.\,]+$/'
            ],
            'alamat' => [
                'nullable',
                'string',
                'max:500',
                'min:10'
            ],
            'pagu_biaya' => [
                'required',
                'numeric',
                'min:1000000', // Minimal 1 juta
                'max:1000000000000' // Maksimal 1 triliun
            ]
        ], [
            'nama_kantor.required' => 'Nama kantor wajib diisi',
            'nama_kantor.unique' => 'Nama kantor sudah digunakan',
            'nama_kantor.min' => 'Nama kantor minimal 3 karakter',
            'nama_kantor.max' => 'Nama kantor maksimal 255 karakter',
            'nama_kantor.regex' => 'Nama kantor hanya boleh berisi huruf, angka, spasi, dan karakter -/.,',
            'alamat.min' => 'Alamat minimal 10 karakter jika diisi',
            'alamat.max' => 'Alamat maksimal 500 karakter',
            'pagu_biaya.required' => 'Pagu biaya wajib diisi',
            'pagu_biaya.numeric' => 'Pagu biaya harus berupa angka',
            'pagu_biaya.min' => 'Pagu biaya minimal Rp 1.000.000',
            'pagu_biaya.max' => 'Pagu biaya maksimal Rp 1.000.000.000.000'
        ]);

        try {
            DB::beginTransaction();

            // Clean pagu biaya - remove formatting dan konversi ke integer
            $pagu_biaya = $this->cleanCurrency($request->pagu_biaya);

            Kantor::create([
                'nama_kantor' => trim($request->nama_kantor),
                'alamat' => $request->alamat ? trim($request->alamat) : null,
                'pagu_biaya' => $pagu_biaya
            ]);

            DB::commit();

            return redirect()->route('kantor.index')
                ->with('success', 'Kantor ' . $request->nama_kantor . ' berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error store kantor: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return redirect()->back()
                ->with('error', 'Gagal menambahkan kantor. Silakan coba lagi.')
                ->withInput()
                ->withErrors(['system_error' => 'Terjadi kesalahan sistem. Silakan coba lagi.']);
        }
    }

    public function edit(Kantor $kantor)
    {
        $kantor->loadCount(['proyeks', 'biayas']);

        // Hitung total nilai kontrak
        $totalNilaiKontrak = DB::table('kontraks')
            ->join('proyeks', 'kontraks.proyek_id', '=', 'proyeks.id')
            ->where('proyeks.kantor_id', $kantor->id)
            ->sum('kontraks.nilai_kontrak');

        return view('kantor.edit', compact('kantor', 'totalNilaiKontrak'));
    }

    public function update(Request $request, Kantor $kantor)
    {
        $request->validate([
            'nama_kantor' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'unique:kantors,nama_kantor,' . $kantor->id,
                'regex:/^[a-zA-Z0-9\s\-\/\.\,]+$/'
            ],
            'alamat' => [
                'nullable',
                'string',
                'max:500',
                'min:10'
            ],
            'pagu_biaya' => [
                'required',
                'numeric',
                'min:1000000',
                'max:1000000000000'
            ]
        ], [
            'nama_kantor.required' => 'Nama kantor wajib diisi',
            'nama_kantor.unique' => 'Nama kantor sudah digunakan',
            'nama_kantor.min' => 'Nama kantor minimal 3 karakter',
            'nama_kantor.max' => 'Nama kantor maksimal 255 karakter',
            'nama_kantor.regex' => 'Nama kantor hanya boleh berisi huruf, angka, spasi, dan karakter -/.,',
            'alamat.min' => 'Alamat minimal 10 karakter jika diisi',
            'alamat.max' => 'Alamat maksimal 500 karakter',
            'pagu_biaya.required' => 'Pagu biaya wajib diisi',
            'pagu_biaya.numeric' => 'Pagu biaya harus berupa angka',
            'pagu_biaya.min' => 'Pagu biaya minimal Rp 1.000.000',
            'pagu_biaya.max' => 'Pagu biaya maksimal Rp 1.000.000.000.000'
        ]);

        try {
            DB::beginTransaction();

            // Clean pagu biaya - remove formatting
            $pagu_biaya = $this->cleanCurrency($request->pagu_biaya);

            $kantor->update([
                'nama_kantor' => trim($request->nama_kantor),
                'alamat' => $request->alamat ? trim($request->alamat) : null,
                'pagu_biaya' => $pagu_biaya
            ]);

            DB::commit();
            return redirect()->route('kantor.index')->with('success', 'Kantor berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error update kantor: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Gagal mengupdate kantor. Silakan coba lagi.')->withInput();
        }
    }

    public function destroy(Kantor $kantor)
    {
        // Cek apakah kantor memiliki proyek
        if ($kantor->proyeks()->count() > 0) {
            return redirect()->route('kantor.index')->with('error', 'Tidak dapat menghapus kantor karena masih memiliki proyek');
        }

        try {
            DB::beginTransaction();

            $kantor->delete();

            DB::commit();
            return redirect()->route('kantor.index')->with('success', 'Kantor berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error destroy kantor: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('kantor.index')->with('error', 'Gagal menghapus kantor. Silakan coba lagi.');
        }
    }

    // Method untuk membersihkan format currency
    private function cleanCurrency($value)
    {
        if (is_numeric($value)) {
            return (int) $value;
        }

        // Hapus semua karakter non-digit kecuali koma dan titik
        $cleaned = preg_replace('/[^\d,.]/', '', $value);

        // Jika ada koma sebagai desimal, ganti dengan titik
        $cleaned = str_replace(',', '.', $cleaned);

        // Hapus semua titik (kecuali yang sebagai desimal)
        $parts = explode('.', $cleaned);
        if (count($parts) > 1) {
            $integerPart = str_replace('.', '', $parts[0]);
            $decimalPart = $parts[1];
            $cleaned = $integerPart . '.' . $decimalPart;
        } else {
            $cleaned = str_replace('.', '', $cleaned);
        }

        return (int) round((float) $cleaned);
    }

    // Method untuk mendapatkan total nilai kontrak per kantor
    private function getTotalNilaiKontrak($kantorId)
    {
        return DB::table('kontraks')
            ->join('proyeks', 'kontraks.proyek_id', '=', 'proyeks.id')
            ->where('proyeks.kantor_id', $kantorId)
            ->sum('kontraks.nilai_kontrak');
    }
}
