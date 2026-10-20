<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Proyek extends Model
{
    use HasFactory;

    protected $fillable = ['kantor_id', 'nama_proyek', 'izin_prinsip','izin_prinsip_file' ,'deskripsi'];

    public function kantor()
    {
        return $this->belongsTo(Kantor::class);
    }

    public function kontraks()
    {
        return $this->hasMany(Kontrak::class);
    }

    // Relationship ke DokumenProyek
    public function dokumen()
    {
        return $this->hasMany(DokumenProyek::class);
    }

    // Method untuk cek apakah bagian sudah ada
    public function hasBagian($bagian)
    {
        return $this->kontraks()->where('bagian', $bagian)->exists();
    }

    // Method untuk mendapatkan total nilai kontrak semua bagian
    public function getTotalNilaiKontrakAttribute()
    {
        return $this->kontraks->sum('nilai_kontrak');
    }

    // Relasi: Proyek -> Realisasi (via kontrak)
    public function realisasis()
    {
        return $this->hasManyThrough(Realisasi::class, Kontrak::class);
    }

    // Helper method untuk cek apakah proyek selesai
    public function getIsSelesaiAttribute()
    {
        return $this->progress_terendah >= 100;
    }

    // Method untuk cek apakah proyek sudah selesai dan ready untuk upload dokumen
 public function getReadyForDokumenAttribute()
    {
        $this->load(['kontraks.realisasis', 'kontraks.biayas']);

        // Jika tidak ada kontrak, tidak ready
        if ($this->kontraks->isEmpty()) {
            return false;
        }

        // Cek setiap kontrak harus mencapai 100% di kedua aspek
        foreach ($this->kontraks as $kontrak) {
            $progressFisik = $kontrak->realisasis->max('progress_persen') ?? 0;
            $progressBiaya = $kontrak->total_progress_biaya; // Pakai accessor dari Kontrak

            Log::info("Kontrak {$kontrak->id} - Progress Fisik: {$progressFisik}%, Progress Biaya: {$progressBiaya}%");

            // Jika ada kontrak yang belum mencapai 100% di salah satu aspek, proyek tidak ready
            if ($progressFisik < 100 || $progressBiaya < 100) {
                return false;
            }
        }

        return true;
    }

    // Method untuk mendapatkan total progress biaya per kontrak (jumlah semua termin)
    private function getTotalProgressBiaya($kontrakId)
    {
        return \App\Models\Biaya::where('kontrak_id', $kontrakId)->sum('progress');
    }

    // Method untuk mendapatkan progress terkecil (bottleneck)
     public function getProgressTerendahAttribute()
    {
        $this->load(['kontraks.realisasis', 'kontraks.biayas']);

        $progresses = [];

        foreach ($this->kontraks as $kontrak) {
            // Progress fisik - ambil yang tertinggi dari realisasis
            $progressFisik = $kontrak->realisasis->max('progress_persen') ?? 0;

            // Progress biaya - total dari semua termin
            $progressBiaya = $kontrak->total_progress_biaya;

            Log::info("Progress Calculation - Kontrak {$kontrak->id}: Fisik {$progressFisik}%, Biaya {$progressBiaya}%");

            $progresses[] = $progressFisik;
            $progresses[] = $progressBiaya;
        }

        $result = count($progresses) > 0 ? min($progresses) : 0;

        Log::info("Proyek {$this->id} - Progress Terendah: {$result}%");

        return $result;
    }


    // Method untuk cek dokumen yang sudah diupload
    public function getDokumenStatusAttribute()
    {
        $dokumenTypes = ['bast_i', 'bast_ii', 'kwitansi', 'spp'];
        $status = [];

        foreach ($dokumenTypes as $type) {
            // Cek apakah ada dokumen dengan jenis ini untuk proyek ini
            $status[$type] = $this->dokumen()->where('jenis_dokumen', $type)->exists();
        }

        return $status;
    }

    // Method untuk cek apakah semua dokumen sudah lengkap
    public function getDokumenLengkapAttribute()
    {
        $status = $this->dokumen_status;
        return !in_array(false, $status, true);
    }

    // Scope untuk proyek yang butuh dokumen
    public function scopeButuhDokumen($query)
    {
        return $query->whereHas('kontraks', function($query) {
                $query->whereHas('biayas');
            })
            ->whereHas('kontraks', function($query) {
                $query->whereHas('realisasis');
            })
            ->with(['kantor', 'kontraks.realisasis', 'kontraks.biayas', 'dokumen'])
            ->get()
            ->filter(function($proyek) {
                return $proyek->ready_for_dokumen && !$proyek->dokumen_lengkap;
            });
    }

    // Method tambahan untuk debugging
    public function getProgressDetailAttribute()
    {
        $this->load(['kontraks.realisasis', 'kontraks.biayas']);

        $detail = [];
        foreach ($this->kontraks as $kontrak) {
            $progressBiaya = $this->getTotalProgressBiaya($kontrak->id);

            $detail[] = [
                'kontrak' => $kontrak->bagian,
                'progress_fisik' => $kontrak->realisasis->max('progress_persen') ?? 0,
                'progress_biaya' => $progressBiaya,
                'realisasi_count' => $kontrak->realisasis->count(),
                'biaya_count' => $kontrak->biayas->count(),
                'is_complete' => ($kontrak->realisasis->max('progress_persen') ?? 0) >= 100 && $progressBiaya >= 100
            ];
        }

        return $detail;
    }

    // Method untuk mendapatkan summary progress proyek
    public function getProgressSummaryAttribute()
    {
        $this->load(['kontraks.realisasis', 'kontraks.biayas']);

        $summary = [
            'total_kontrak' => $this->kontraks->count(),
            'kontrak_complete' => 0,
            'kontrak_incomplete' => 0,
            'ready_for_dokumen' => $this->ready_for_dokumen
        ];

        foreach ($this->kontraks as $kontrak) {
            $progressFisik = $kontrak->realisasis->max('progress_persen') ?? 0;
            $progressBiaya = $this->getTotalProgressBiaya($kontrak->id);

            if ($progressFisik >= 100 && $progressBiaya >= 100) {
                $summary['kontrak_complete']++;
            } else {
                $summary['kontrak_incomplete']++;
            }
        }

        return $summary;
    }
}
