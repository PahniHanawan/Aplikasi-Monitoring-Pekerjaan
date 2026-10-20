<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KontrakSeeder extends Seeder
{
    public function run(): void
    {
        $kontraks = [
            // Kontrak untuk Proyek 1 (Kanwil - Gedung Kantor Pusat)
            [
                'proyek_id' => 1,
                'bagian' => 'Perencana',
                'mitra' => 'PT Arsitek Indonesia',
                'pagu_biaya' => 5000000000,
                'masa_pemeliharaan' => 365,
                'nomor_spk' => 'SPK/001/PER/2025',
                'tanggal' => '2025-01-15',
                'jangka_waktu' => 180,
                'nilai_kontrak' => 4500000000,
                'file_spk' => 'spk/spk_001_perencana.pdf',
                'original_filename' => 'SPK Perencana.pdf',
                'file_size' => '2.5 MB',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'proyek_id' => 1,
                'bagian' => 'Pelaksana',
                'mitra' => 'PT Bangun Jaya Konstruksi',
                'pagu_biaya' => 45000000000,
                'masa_pemeliharaan' => 730,
                'nomor_spk' => 'SPK/002/PEL/2025',
                'tanggal' => '2025-02-01',
                'jangka_waktu' => 540,
                'nilai_kontrak' => 42000000000,
                'file_spk' => 'spk/spk_002_pelaksana.pdf',
                'original_filename' => 'SPK Pelaksana.pdf',
                'file_size' => '3.1 MB',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'proyek_id' => 1,
                'bagian' => 'Pengawas',
                'mitra' => 'PT Supervisi Mandiri',
                'pagu_biaya' => 2000000000,
                'masa_pemeliharaan' => 730,
                'nomor_spk' => 'SPK/003/SUP/2025',
                'tanggal' => '2025-01-20',
                'jangka_waktu' => 540,
                'nilai_kontrak' => 1800000000,
                'file_spk' => 'spk/spk_003_pengawas.pdf',
                'original_filename' => 'SPK Pengawas.pdf',
                'file_size' => '1.8 MB',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Kontrak untuk Proyek 2 (Kanwil - Sistem Jaringan)
            [
                'proyek_id' => 2,
                'bagian' => 'Pelaksana',
                'mitra' => 'PT Teknologi Informasi Nusantara',
                'pagu_biaya' => 8000000000,
                'masa_pemeliharaan' => 365,
                'nomor_spk' => 'SPK/004/TI/2025',
                'tanggal' => '2025-03-10',
                'jangka_waktu' => 120,
                'nilai_kontrak' => 7500000000,
                'file_spk' => 'spk/spk_004_ti.pdf',
                'original_filename' => 'SPK TI.pdf',
                'file_size' => '2.2 MB',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Kontrak untuk Proyek 3 (Bandung - Renovasi)
            [
                'proyek_id' => 3,
                'bagian' => 'Pelaksana',
                'mitra' => 'CV Renovasi Bandung',
                'pagu_biaya' => 3500000000,
                'masa_pemeliharaan' => 180,
                'nomor_spk' => 'SPK/005/REN/2025',
                'tanggal' => '2025-02-15',
                'jangka_waktu' => 90,
                'nilai_kontrak' => 3200000000,
                'file_spk' => 'spk/spk_005_renovasi.pdf',
                'original_filename' => 'SPK Renovasi.pdf',
                'file_size' => '1.5 MB',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Kontrak untuk Proyek 4 (Bandung - Parkir)
            [
                'proyek_id' => 4,
                'bagian' => 'Pelaksana',
                'mitra' => 'PT Konstruksi Parkir',
                'pagu_biaya' => 2500000000,
                'masa_pemeliharaan' => 365,
                'nomor_spk' => 'SPK/006/PARK/2025',
                'tanggal' => '2025-03-01',
                'jangka_waktu' => 150,
                'nilai_kontrak' => 2300000000,
                'file_spk' => 'spk/spk_006_parkir.pdf',
                'original_filename' => 'SPK Parkir.pdf',
                'file_size' => '1.9 MB',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Kontrak untuk Proyek 5 (Cianjur - Jalan)
            [
                'proyek_id' => 5,
                'bagian' => 'Pelaksana',
                'mitra' => 'PT Jalan Raya Cianjur',
                'pagu_biaya' => 1200000000,
                'masa_pemeliharaan' => 180,
                'nomor_spk' => 'SPK/007/JAL/2025',
                'tanggal' => '2025-04-01',
                'jangka_waktu' => 60,
                'nilai_kontrak' => 1100000000,
                'file_spk' => 'spk/spk_007_jalan.pdf',
                'original_filename' => 'SPK Jalan.pdf',
                'file_size' => '1.2 MB',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('kontraks')->insert($kontraks);
    }
}
