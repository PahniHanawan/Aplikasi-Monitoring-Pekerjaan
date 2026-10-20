<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RealisasiSeeder extends Seeder
{
    public function run(): void
    {
        $realisasis = [
            // Realisasi untuk Kontrak 1 (Perencana Gedung)
            [
                'kontrak_id' => 1,
                'progress_persen' => 100.00,
                'nilai_realisasi' => 4500000000,
                'tanggal_realisasi' => '2025-04-15',
                'keterangan' => 'Pekerjaan perencanaan telah selesai 100%',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Realisasi untuk Kontrak 2 (Pelaksana Gedung)
            [
                'kontrak_id' => 2,
                'progress_persen' => 45.50,
                'nilai_realisasi' => 19110000000,
                'tanggal_realisasi' => '2025-10-01',
                'keterangan' => 'Progress konstruksi struktur lantai 3',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kontrak_id' => 2,
                'progress_persen' => 30.25,
                'nilai_realisasi' => 12705000000,
                'tanggal_realisasi' => '2025-08-15',
                'keterangan' => 'Progress konstruksi struktur lantai 2',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kontrak_id' => 2,
                'progress_persen' => 15.75,
                'nilai_realisasi' => 6615000000,
                'tanggal_realisasi' => '2025-06-30',
                'keterangan' => 'Progress konstruksi struktur lantai 1',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Realisasi untuk Kontrak 3 (Pengawas Gedung)
            [
                'kontrak_id' => 3,
                'progress_persen' => 50.00,
                'nilai_realisasi' => 900000000,
                'tanggal_realisasi' => '2025-10-01',
                'keterangan' => 'Supervisi tahap konstruksi struktur',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Realisasi untuk Kontrak 4 (Sistem Jaringan)
            [
                'kontrak_id' => 4,
                'progress_persen' => 80.00,
                'nilai_realisasi' => 6000000000,
                'tanggal_realisasi' => '2025-09-20',
                'keterangan' => 'Instalasi jaringan backbone selesai, tahap pengujian',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kontrak_id' => 4,
                'progress_persen' => 60.00,
                'nilai_realisasi' => 4500000000,
                'tanggal_realisasi' => '2025-08-10',
                'keterangan' => 'Instalasi perangkat jaringan core',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Realisasi untuk Kontrak 5 (Renovasi Bandung)
            [
                'kontrak_id' => 5,
                'progress_persen' => 95.00,
                'nilai_realisasi' => 3040000000,
                'tanggal_realisasi' => '2025-10-10',
                'keterangan' => 'Pekerjaan finishing tahap akhir',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kontrak_id' => 5,
                'progress_persen' => 75.00,
                'nilai_realisasi' => 2400000000,
                'tanggal_realisasi' => '2025-09-01',
                'keterangan' => 'Pekerjaan interior dan plumbing',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Realisasi untuk Kontrak 6 (Parkir Bandung)
            [
                'kontrak_id' => 6,
                'progress_persen' => 40.00,
                'nilai_realisasi' => 920000000,
                'tanggal_realisasi' => '2025-09-15',
                'keterangan' => 'Pekerjaan struktur parkir lantai 1',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Realisasi untuk Kontrak 7 (Jalan Cianjur)
            [
                'kontrak_id' => 7,
                'progress_persen' => 100.00,
                'nilai_realisasi' => 1100000000,
                'tanggal_realisasi' => '2025-07-30',
                'keterangan' => 'Pekerjaan perbaikan jalan selesai',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('realisasis')->insert($realisasis);
    }
}
