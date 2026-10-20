<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BiayaSeeder extends Seeder
{
    public function run(): void
    {
        $biayas = [
            // Biaya Pelaksanaan untuk Kontrak 2 (Gedung)
            [
                'kantor_id' => 1,
                'kontrak_id' => 2,
                'jenis' => 'Pelaksanaan',
                'termin' => 1,
                'progress' => 15.75,
                'nominal_realisasi' => 6615000000,
                'tanggal_realisasi' => '2025-06-30',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kantor_id' => 1,
                'kontrak_id' => 2,
                'jenis' => 'Pelaksanaan',
                'termin' => 2,
                'progress' => 30.25,
                'nominal_realisasi' => 12705000000,
                'tanggal_realisasi' => '2025-08-15',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kantor_id' => 1,
                'kontrak_id' => 2,
                'jenis' => 'Pelaksanaan',
                'termin' => 3,
                'progress' => 45.50,
                'nominal_realisasi' => 19110000000,
                'tanggal_realisasi' => '2025-10-01',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Biaya Pemeliharaan untuk Kontrak 1 (Perencana)
            [
                'kantor_id' => 1,
                'kontrak_id' => 1,
                'jenis' => 'Pemeliharaan',
                'termin' => 1,
                'progress' => 100.00,
                'nominal_realisasi' => 4500000000,
                'tanggal_realisasi' => '2025-04-15',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Biaya untuk Kontrak 4 (Sistem Jaringan)
            [
                'kantor_id' => 1,
                'kontrak_id' => 4,
                'jenis' => 'Pelaksanaan',
                'termin' => 1,
                'progress' => 60.00,
                'nominal_realisasi' => 4500000000,
                'tanggal_realisasi' => '2025-08-10',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kantor_id' => 1,
                'kontrak_id' => 4,
                'jenis' => 'Pelaksanaan',
                'termin' => 2,
                'progress' => 80.00,
                'nominal_realisasi' => 6000000000,
                'tanggal_realisasi' => '2025-09-20',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Biaya untuk Kontrak 5 (Renovasi Bandung)
            [
                'kantor_id' => 2,
                'kontrak_id' => 5,
                'jenis' => 'Pelaksanaan',
                'termin' => 1,
                'progress' => 75.00,
                'nominal_realisasi' => 2400000000,
                'tanggal_realisasi' => '2025-09-01',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kantor_id' => 2,
                'kontrak_id' => 5,
                'jenis' => 'Pelaksanaan',
                'termin' => 2,
                'progress' => 95.00,
                'nominal_realisasi' => 3040000000,
                'tanggal_realisasi' => '2025-10-10',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Biaya untuk Kontrak 6 (Parkir Bandung)
            [
                'kantor_id' => 2,
                'kontrak_id' => 6,
                'jenis' => 'Pelaksanaan',
                'termin' => 1,
                'progress' => 40.00,
                'nominal_realisasi' => 920000000,
                'tanggal_realisasi' => '2025-09-15',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Biaya untuk Kontrak 7 (Jalan Cianjur)
            [
                'kantor_id' => 3,
                'kontrak_id' => 7,
                'jenis' => 'Pelaksanaan',
                'termin' => 1,
                'progress' => 100.00,
                'nominal_realisasi' => 1100000000,
                'tanggal_realisasi' => '2025-07-30',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('biayas')->insert($biayas);
    }
}
