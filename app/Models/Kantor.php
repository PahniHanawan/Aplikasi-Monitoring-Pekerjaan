<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Kantor extends Model
{
    use HasFactory;

    protected $fillable = ['nama_kantor', 'alamat', 'pagu_biaya'];

    // Relasi: 1 Kantor punya banyak Proyek
    public function proyeks()
    {
        return $this->hasMany(Proyek::class);
    }

    public function biayas()
    {
        return $this->hasMany(Biaya::class);
    }

    // Accessor untuk format pagu biaya
    public function getPaguBiayaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->pagu_biaya, 0, ',', '.');
    }

    // Accessor untuk total nilai kontrak
    public function getTotalNilaiKontrakAttribute()
    {
        return DB::table('kontraks')
            ->join('proyeks', 'kontraks.proyek_id', '=', 'proyeks.id')
            ->where('proyeks.kantor_id', $this->id)
            ->sum('kontraks.nilai_kontrak');
    }

    // Scope untuk filter pencarian
    public function scopeSearch($query, $search)
    {
        return $query->where('nama_kantor', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
    }

    // Method untuk mendapatkan statistik kantor
    public function getStatistikAttribute()
    {
        return [
            'total_proyek' => $this->proyeks()->count(),
            'total_nilai_kontrak' => $this->total_nilai_kontrak,
            'pagu_biaya' => $this->pagu_biaya,
            'sisa_pagu' => $this->pagu_biaya - $this->total_nilai_kontrak
        ];
    }
}
