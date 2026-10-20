<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProyekSeeder extends Seeder
{
    public function run(): void
    {
        $proyeks = [
            // Proyek untuk Kanwil (ID 1)
            [
                'kantor_id' => 1,
                'nama_proyek' => 'Pembangunan Gedung Kantor Pusat',
                'izin_prinsip' => 'SI/001/2025',
                'izin_prinsip_file' => 'izin_prinsip/gedung_kantor_pusat.pdf',
                'deskripsi' => 'Pembangunan gedung kantor pusat 5 lantai dengan fasilitas lengkap',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kantor_id' => 1,
                'nama_proyek' => 'Sistem Jaringan TI Terpadu',
                'izin_prinsip' => 'SI/002/2025',
                'izin_prinsip_file' => 'izin_prinsip/sistem_jaringan.pdf',
                'deskripsi' => 'Implementasi sistem jaringan terpadu untuk seluruh kantor cabang',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Proyek untuk Kancab Bandung (ID 2)
            [
                'kantor_id' => 2,
                'nama_proyek' => 'Renovasi Kantor Cabang Bandung',
                'izin_prinsip' => 'SI/003/2025',
                'izin_prinsip_file' => 'izin_prinsip/renovasi_bandung.pdf',
                'deskripsi' => 'Renovasi total kantor cabang Bandung termasuk interior dan eksterior',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kantor_id' => 2,
                'nama_proyek' => 'Pembangunan Area Parkir',
                'izin_prinsip' => 'SI/004/2025',
                'izin_prinsip_file' => 'izin_prinsip/parkir_bandung.pdf',
                'deskripsi' => 'Pembangunan area parkir bertingkat kapasitas 100 kendaraan',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Proyek untuk Kancab Cianjur (ID 3)
            [
                'kantor_id' => 3,
                'nama_proyek' => 'Perbaikan Jalan Akses Kantor',
                'izin_prinsip' => 'SI/005/2025',
                'izin_prinsip_file' => 'izin_prinsip/jalan_cianjur.pdf',
                'deskripsi' => 'Perbaikan dan pelebaran jalan akses menuju kantor cabang Cianjur',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Proyek untuk Kancab Bogor (ID 4)
            [
                'kantor_id' => 4,
                'nama_proyek' => 'Instalasi Listrik Tenaga Surya',
                'izin_prinsip' => 'SI/006/2025',
                'izin_prinsip_file' => 'izin_prinsip/surya_bogor.pdf',
                'deskripsi' => 'Instalasi sistem listrik tenaga surya untuk efisiensi energi',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Proyek untuk Kancab Cirebon (ID 5)
            [
                'kantor_id' => 5,
                'nama_proyek' => 'Pembangunan Ruang Meeting',
                'izin_prinsip' => 'SI/007/2025',
                'izin_prinsip_file' => 'izin_prinsip/meeting_cirebon.pdf',
                'deskripsi' => 'Pembangunan ruang meeting modern kapasitas 50 orang',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('proyeks')->insert($proyeks);
    }
}
