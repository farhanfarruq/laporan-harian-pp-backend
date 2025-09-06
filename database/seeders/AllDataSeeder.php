<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bidang;
use App\Models\User;
use App\Models\Pengurus;
use App\Models\Jobdesk;
use Illuminate\Support\Facades\Hash;

class AllDataSeeder extends Seeder
{
    public function run(): void
    {
        // --- BIDANG ---
        $bapakamar = Bidang::create(['slug' => 'bapakamar', 'name' => 'Pengurus Bapak Kamar', 'icon' => 'fa-bed', 'color' => 'bg-blue-500']);
        $keamanan = Bidang::create(['slug' => 'bk_keamanan', 'name' => 'Pengurus BK dan Keamanan', 'icon' => 'fa-shield-alt', 'color' => 'bg-green-500']);
        Bidang::create(['slug' => 'minat_bakat', 'name' => 'Pengurus Minat Bakat', 'icon' => 'fa-palette', 'color' => 'bg-purple-500']);
        Bidang::create(['slug' => 'kebersihan', 'name' => 'Pengurus Kebersihan', 'icon' => 'fa-broom', 'color' => 'bg-yellow-500']);
        Bidang::create(['slug' => 'sarpras', 'name' => 'Pengurus Sarpras', 'icon' => 'fa-tools', 'color' => 'bg-orange-500']);
        Bidang::create(['slug' => 'kesehatan', 'name' => 'Pengurus Kesehatan', 'icon' => 'fa-heartbeat', 'color' => 'bg-red-500']);

        // --- USERS ---
        User::create(['name' => 'Pengurus Umum', 'username' => 'pengurus', 'password' => Hash::make('pengurus123'), 'role' => 'pengurus']);
        User::create(['name' => 'Admin Utama', 'username' => 'adminutama', 'password' => Hash::make('adminutama2025'), 'role' => 'admin_utama']);
        User::create(['name' => 'Admin Bapak Kamar', 'username' => 'adminbapakamar', 'password' => Hash::make('admin123'), 'role' => 'admin_bidang', 'bidang_id' => $bapakamar->id]);

        // --- PENGURUS BAPAK KAMAR ---
        $pengurusBapakamar = [
            ['nama' => 'Arif Hermawan', 'kelas' => '7'], ['nama' => 'Gading Wandira Putra Khoiri', 'kelas' => '7'],
            ['nama' => 'Hasan Ngulwi Mufti', 'kelas' => '7'], ['nama' => 'Iqbal Rofiqul Azhar', 'kelas' => '8'],
            ['nama' => 'Mohamad Mauludul Fadilah', 'kelas' => '8'], ['nama' => 'Muhammad Sulthan Maulana Asy Syauqi', 'kelas' => '8'],
            ['nama' => 'M Iqbal Mirza Hidayat', 'kelas' => '9'], ['nama' => 'Muhammad Ngainun Najib', 'kelas' => '9'],
            ['nama' => 'Muhammad Dzunnurain', 'kelas' => '10'], ['nama' => 'Syahrizal Nur Faizin', 'kelas' => '10'],
            ['nama' => 'Arif Herianto', 'kelas' => '11'], ['nama' => 'Miftahurroyyan', 'kelas' => '11'],
            ['nama' => 'Muhammad Latif Baharuddin', 'kelas' => '12'], ['nama' => 'Rafi Luthfan Zaky', 'kelas' => '12'],
            ['nama' => 'Muhamad Dafa Nur Rohman', 'kelas' => 'tahfidz'], ['nama' => 'Muhamad Dafi Nur Rohim', 'kelas' => 'tahfidz']
        ];
        foreach ($pengurusBapakamar as $p) {
            Pengurus::create(['bidang_id' => $bapakamar->id, 'nama' => $p['nama'], 'kelas' => $p['kelas']]);
        }
        Pengurus::create(['bidang_id' => $keamanan->id, 'nama' => 'Ustad Hasan']);

        // --- JOBDESK BAPAK KAMAR ---
        $jobdeskMalam = [
            'Mengkondisikan santri piket kamar dan sekitarnya bakda jamaah Asar', 'Mengkondisikan semua santri mandi dan makan sore pukul 17 sampai Magrib',
            'Memastikan santri berangkat jamaah Magrib sebelum adzan', 'Mengunci gerbang kamar setelah santri berangkat jamaah Magrib',
            'Mendampingi dan mengabsen santri jamaah Magrib', 'Memastikan santri berangkat sorogan Quran dan Muhafazhah',
            'Memastikan santri berangkat Madin', 'Memastikan santri mengikuti bandongan talaqqi dan mengabsennya',
            'Mendampingi dan mengabsen santri jamaah Isya', 'Mendampingi belajar malam bakda Isya',
            'Mengkondisikan santri agar segera tidur pukul 23', 'Mengabsen santri yang berada di pondok pukul 23', 'Lainnya'
        ];
        foreach ($jobdeskMalam as $j) {
            Jobdesk::create(['bidang_id' => $bapakamar->id, 'description' => $j, 'type' => 'malam']);
        }
        $jobdeskSubuh = [
            'Membangunkan santri untuk jamaah Subuh sebelum Subuh', 'Menata bantal selimut dan memastikan kamar bersih setelah Subuh',
            'Mengunci gerbang setelah santri berangkat Subuh', 'Mendampingi dan mengabsen santri jamaah Subuh',
            'Memastikan sorogan Quran dan Kitab bakda Subuh', 'Mengkondisikan santri piket kamar bakda atau saat sorogan',
            'Mengkondisikan mandi dan sarapan bakda sorogan', 'Memastikan santri berangkat sekolah sebelum pukul 07', 'Lainnya'
        ];
        foreach ($jobdeskSubuh as $j) {
            Jobdesk::create(['bidang_id' => $bapakamar->id, 'description' => $j, 'type' => 'subuh']);
        }
    }
}