<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin Account (Full Access)
        User::updateOrCreate(
            ['email' => 'admin@formadika.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('admin'),
                'role' => 'admin',
            ]
        );

        // 2. Petugas Account (Can toggle payment status, but no App Settings access)
        User::updateOrCreate(
            ['email' => 'petugas@formadika.com'],
            [
                'name' => 'petugas',
                'password' => Hash::make('petugas'),
                'role' => 'petugas',
            ]
        );

        // 3. Warga Account (Read-Only access for residents)
        User::updateOrCreate(
            ['email' => 'warga@formadika.com'],
            [
                'name' => 'warga',
                'password' => Hash::make('warga'),
                'role' => 'warga',
            ]
        );

        // Default App Settings
        AppSetting::set('app_title', 'REKAPITULASI TAGIHAN LISTRIK');
        AppSetting::set('app_subtitle', 'FORMADIKA');
        AppSetting::set('app_address', 'Sekretariat: Kentolan Lor, Guwosari, Pajangan, Bantul');
        AppSetting::set('app_periode', 'AGUSTUS 2026');
    }
}
