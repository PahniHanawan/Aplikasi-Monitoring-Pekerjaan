<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontrak extends Model
{
    use HasFactory;

    protected $fillable = [
        'proyek_id',
        'bagian',
        'mitra',
        'pagu_biaya',
        'masa_pemeliharaan',
        'nomor_spk',
        'tanggal',
        'jangka_waktu',
        'nilai_kontrak',
        'file_spk',
        'original_filename',
        'file_size',
    ];

    protected $appends = [
        'nilai_kontrak_formatted',
        'tanggal_formatted',
        'file_spk_url',
        'total_progress_biaya',
        'total_realisasi_biaya',
        'sisa_kontrak',
        'progress_percentage',
        'total_progress',
        'total_realisasi',
    ];

    // Relasi
    public function proyek()
    {
        return $this->belongsTo(Proyek::class);
    }

    public function realisasis()
    {
        return $this->hasMany(Realisasi::class);
    }

    public function biayas()
    {
        return $this->hasMany(Biaya::class);
    }

    public function kantor()
    {
        return $this->hasOneThrough(Kantor::class, Proyek::class, 'id', 'id', 'proyek_id', 'kantor_id');
    }

    // Accessors
    public function getFileSpkUrlAttribute()
    {
        return $this->file_spk ? asset('storage/spk/' . $this->file_spk) : null;
    }

    public function getNilaiKontrakFormattedAttribute()
    {
        return format_currency($this->nilai_kontrak);
    }

    public function getTanggalFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->tanggal)->format('d/m/Y');
    }

    // Method untuk menghitung progress biaya
    public function getTotalProgressBiayaAttribute()
    {
        return $this->biayas->sum('progress');
    }

    public function getTotalRealisasiBiayaAttribute()
    {
        return $this->biayas->sum('nominal_realisasi');
    }

    public function getSisaKontrakAttribute()
    {
        return max(0, $this->nilai_kontrak - $this->total_realisasi_biaya);
    }

    public function getProgressPercentageAttribute()
    {
        return $this->nilai_kontrak > 0 ?
            ($this->total_realisasi_biaya / $this->nilai_kontrak) * 100 : 0;
    }

    // Computed properties untuk compatibility
    public function getTotalProgressAttribute()
    {
        return $this->total_progress_biaya;
    }

    public function getTotalRealisasiAttribute()
    {
        return $this->total_realisasi_biaya;
    }

    // Scope untuk filter
    public function scopeByProyek($query, $proyekId)
    {
        return $query->where('proyek_id', $proyekId);
    }

    public function scopeByKantor($query, $kantorId)
    {
        return $query->whereHas('proyek', function($q) use ($kantorId) {
            $q->where('kantor_id', $kantorId);
        });
    }

    public function scopeByBagian($query, $bagian)
    {
        return $query->where('bagian', $bagian);
    }

    // Validasi bagian unik
    public static function isBagianExists($proyek_id, $bagian, $excludeId = null)
    {
        $query = self::where('proyek_id', $proyek_id)
                    ->where('bagian', $bagian);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    // Method untuk mendapatkan statistik
    public function getStatistikAttribute()
    {
        return [
            'total_termin' => $this->biayas->count(),
            'total_progress' => $this->total_progress_biaya,
            'total_realisasi' => $this->total_realisasi_biaya,
            'sisa_kontrak' => $this->sisa_kontrak,
            'progress_percentage' => $this->progress_percentage,
        ];
    }

    // Method untuk cek apakah kontrak sudah lengkap
    public function getIsCompleteAttribute()
    {
        return $this->total_progress_biaya >= 100;
    }
}
