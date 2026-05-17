<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Sekolah;
use App\Models\Petugas;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin
        User::create([
            'name' => 'adminmbg',
            'email' => 'admin@mbg.com',
            'password' => 'password123',
            'role' => 'admin'
        ]);

        // 2. Buat Akun Petugas
        $petugas = User::create([
            'name' => 'Slamet Riyadi',
            'email' => 'petugas@mbg.com',
            'password' => 'password123',
            'role' => 'petugas'
        ]);

        Petugas::create([
            'user_id' => $petugas->id,
            'kode_petugas' => 'PTG-002',
            'kendaraan' => 'Mobil Pick-up',
            'area_tugas' => 'Kecamatan Peterongan',
            'status' => 'Aktif'
        ]);

        // 3. Buat Akun Sekolah
        $sekolah = User::create([
            'name' => 'MIN 4 Darul Ulum',
            'email' => 'sekolah@mbg.com',
            'password' => 'password123',
            'role' => 'sekolah'
        ]);

        Sekolah::create([
            'user_id' => $sekolah->id,
            'npsn' => '20501234',
            'nama_sekolah' => 'MIN 4 Darul Ulum',
            'alamat' => 'Wonokerto Selatan, Peterongan, Jombang',
            'penanggung_jawab' => 'Bapak Ahmad Fadli',
            'status' => 'Aktif'
        ]);
    }
}