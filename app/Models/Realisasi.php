<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realisasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kontrak_id',
        'progress_persen',
        'nilai_realisasi',
        'tanggal_realisasi',
        'keterangan'
    ];

    protected $casts = [
        'progress_persen' => 'decimal:2',
        'nilai_realisasi' => 'decimal:2',
        'tanggal_realisasi' => 'date'
    ];

    public function kontrak()
    {
        return $this->belongsTo(Kontrak::class);
    }
    public function getNilaiRealisasiFormattedAttribute()
    {
        $nilai = $this->nilai_realisasi ?? 0;
        return 'Rp ' . number_format($nilai, 0, ',', '.');
    }

    public function getTanggalRealisasiFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->tanggal_realisasi)->format('d/m/Y');
    }

    public function scopeAktif($query)
    {
        return $query->where('progress_persen', '<', 100);
    }

    public function scopeSelesai($query)
    {
        return $query->where('progress_persen', '>=', 100);
    }
}
