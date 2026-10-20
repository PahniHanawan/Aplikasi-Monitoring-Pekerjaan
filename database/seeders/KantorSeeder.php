<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KantorSeeder extends Seeder
{
    public function run(): void
    {
        $kantors = [
            [
                'nama_kantor' => 'Kanwil Jawa Barat',
                'alamat' => 'Jl. Soekarno-Hatta No.711A, Jatisari, Kec. Buahbatu, Kota Bandung, Jawa Barat 40286',
                'pagu_biaya' => 50000000000
            ],
            [
                'nama_kantor' => 'Kancab Bandung',
                'alamat' => 'Jl. Cipamokolan No.1, Cipamokolan, Kec. Rancasari, Kota Bandung, Jawa Barat 40292',
                'pagu_biaya' => 25000000000
            ],
            [
                'nama_kantor' => 'Kancab Cianjur',
                'alamat' => 'Jl. Dr. Muwardi No.175 A, Bojongherang, Kec. Cianjur, Kabupaten Cianjur, Jawa Barat 43216',
                'pagu_biaya' => 15000000000
            ],
            [
                'nama_kantor' => 'Kancab Bogor',
                'alamat' => 'Babakan, Kec. Dramaga, Kabupaten Bogor, Jawa Barat',
                'pagu_biaya' => 20000000000
            ],
            [
                'nama_kantor' => 'Kancab Cirebon',
                'alamat' => 'Sunyaragi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45132',
                'pagu_biaya' => 18000000000
            ],
            [
                'nama_kantor' => 'Kancab Indramayu',
                'alamat' => 'Jl. Di Panjaitan No.68, Karanganyar, Kec. Indramayu, Kabupaten Indramayu, Jawa Barat 45213',
                'pagu_biaya' => 12000000000
            ],
            [
                'nama_kantor' => 'Kancab Karawang',
                'alamat' => 'Jl. Cakradireja No.9, Karangpawitan, Kec. Karawang Bar., Karawang, Jawa Barat 41312',
                'pagu_biaya' => 22000000000
            ],
            [
                'nama_kantor' => 'Kancab Subang',
                'alamat' => 'Jl. Mayjen Sutoyo No.23, Karanganyar, Kec. Subang, Kabupaten Subang, Jawa Barat 41211',
                'pagu_biaya' => 13000000000
            ],
            [
                'nama_kantor' => 'Kancab Ciamis',
                'alamat' => 'Jl. Jend. Sudirman No.216, Sindangrasa, Kec. Ciamis, Kabupaten Ciamis, Jawa Barat 46211',
                'pagu_biaya' => 11000000000
            ]
        ];

        foreach ($kantors as $kantor) {
            DB::table('kantors')->insert([
                'nama_kantor' => $kantor['nama_kantor'],
                'alamat' => $kantor['alamat'],
                'pagu_biaya' => $kantor['pagu_biaya'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Atau menggunakan insert langsung (lebih cepat)
        // DB::table('kantors')->insert($kantors);
    }
}
