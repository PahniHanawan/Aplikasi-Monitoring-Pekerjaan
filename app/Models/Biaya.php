<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Biaya extends Model
{
    protected $fillable = [
        'kantor_id',
        'kontrak_id',
        'jenis',
        'termin',
        'progress',
        'nominal_realisasi',
        'tanggal_realisasi',
    ];

    protected $casts = [
        'progress' => 'decimal:2',
        'nominal_realisasi' => 'decimal:2',
        'tanggal_realisasi' => 'date',
    ];

    // Relasi
    public function kantor()
    {
        return $this->belongsTo(Kantor::class);
    }

    public function kontrak()
    {
        return $this->belongsTo(Kontrak::class);
    }

    // Scope untuk filter
    public function scopeByKantor(Builder $query, $kantorId)
    {
        return $kantorId ? $query->where('kantor_id', $kantorId) : $query;
    }

    public function scopeByKontrak(Builder $query, $kontrakId)
    {
        return $kontrakId ? $query->where('kontrak_id', $kontrakId) : $query;
    }

    public function scopeByTahun(Builder $query, $tahun)
    {
        return $tahun ? $query->whereYear('tanggal_realisasi', $tahun) : $query;
    }

    public function scopeByBulan(Builder $query, $bulan)
    {
        return $bulan ? $query->whereMonth('tanggal_realisasi', $bulan) : $query;
    }

    // Method untuk menghitung total progress per kontrak
    public static function getTotalProgressByKontrak($kontrakId)
    {
        return self::where('kontrak_id', $kontrakId)->sum('progress');
    }

    // Method untuk menghitung total realisasi per kontrak
    public static function getTotalRealisasiByKontrak($kontrakId)
    {
        return self::where('kontrak_id', $kontrakId)->sum('nominal_realisasi');
    }

    // Validasi termin unik per kontrak
    public static function isTerminExists($kontrakId, $termin, $excludeId = null)
    {
        $query = self::where('kontrak_id', $kontrakId)
                    ->where('termin', $termin);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
