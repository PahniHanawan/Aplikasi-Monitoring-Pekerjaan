<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenProyek extends Model
{
    use HasFactory;

    protected $fillable = [
        'proyek_id',
        'kontrak_id',
        'jenis_dokumen', // 'bast_i', 'bast_ii', 'kwitansi', 'spp'
        'nama_file',
        'original_filename',
        'file_size',
        'keterangan',
        'tanggal_upload'
    ];

    protected $casts = [
        'tanggal_upload' => 'datetime',
        'file_size' => 'integer'
    ];

    // Relasi ke Proyek
    public function proyek()
    {
        return $this->belongsTo(Proyek::class);
    }

    // Relasi ke Kontrak
    public function kontrak()
    {
        return $this->belongsTo(Kontrak::class);
    }

    // Accessor untuk URL file
    public function getFileUrlAttribute()
    {
        return $this->nama_file ? asset('storage/dokumen-proyek/' . $this->nama_file) : null;
    }

    // Accessor untuk jenis dokumen formatted
    public function getJenisDokumenFormattedAttribute()
    {
        $jenis = [
            'bast_i' => 'BAST I',
            'bast_ii' => 'BAST II',
            'kwitansi' => 'Kwitansi',
            'spp' => 'Surat Permohonan Pembayaran'
        ];

        return $jenis[$this->jenis_dokumen] ?? $this->jenis_dokumen;
    }

    // Scope untuk filter berdasarkan jenis dokumen
    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis_dokumen', $jenis);
    }
}
