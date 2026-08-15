<?php

namespace Database\Seeders;

use App\Models\Warga;
use Illuminate\Database\Seeder;

class WargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wargas = [
            ['no' => 1, 'nama' => 'AGUS SENJOYO', 'rek' => '521031153794', 'tagihan' => 29000, 'lunas' => false],
            ['no' => 2, 'nama' => 'AMATJUMALI', 'rek' => '521030282735', 'tagihan' => 50000, 'lunas' => true],
            ['no' => 3, 'nama' => 'AMAT WAKIT', 'rek' => '521030289557', 'tagihan' => 41000, 'lunas' => false],
            ['no' => 4, 'nama' => 'DIDIK SUTARNO', 'rek' => '521031160904', 'tagihan' => 122000, 'lunas' => true],
            ['no' => 5, 'nama' => 'DOLAH KOMARI', 'rek' => '521030953431', 'tagihan' => 24000, 'lunas' => false],
            ['no' => 6, 'nama' => 'EFENDI', 'rek' => '521031208923', 'tagihan' => 88000, 'lunas' => true],
            ['no' => 7, 'nama' => 'M ZAINURI', 'rek' => '521030823515', 'tagihan' => 51000, 'lunas' => false],
            ['no' => 8, 'nama' => 'PAIMIN', 'rek' => '521030283425', 'tagihan' => 19000, 'lunas' => true],
            ['no' => 9, 'nama' => 'PENDI', 'rek' => '521030283409', 'tagihan' => 36000, 'lunas' => true],
            ['no' => 10, 'nama' => 'SARINDI', 'rek' => '521031050740', 'tagihan' => 47000, 'lunas' => false],
            ['no' => 11, 'nama' => 'SOMODIMEJO/KAMIJAN', 'rek' => '521031090865', 'tagihan' => 49000, 'lunas' => false],
            ['no' => 12, 'nama' => 'SOMODIMEJO', 'rek' => '521030289532', 'tagihan' => 78000, 'lunas' => false],
            ['no' => 13, 'nama' => 'SUDARMAN', 'rek' => '521031036658', 'tagihan' => 53000, 'lunas' => false],
            ['no' => 14, 'nama' => 'UMAT MUH SUPRIYANTO', 'rek' => '521030852058', 'tagihan' => 40000, 'lunas' => true],
            ['no' => 15, 'nama' => 'TUMPRADIYONO', 'rek' => '521031377731', 'tagihan' => 31000, 'lunas' => true],
            ['no' => 16, 'nama' => 'WAHYUDI', 'rek' => '521031427914', 'tagihan' => 27000, 'lunas' => true],
            ['no' => 17, 'nama' => 'SARJILAH', 'rek' => '521031537564', 'tagihan' => 36000, 'lunas' => false],
            ['no' => 18, 'nama' => 'M MUNAJI', 'rek' => '521031630599', 'tagihan' => 22000, 'lunas' => true],
        ];

        foreach ($wargas as $data) {
            Warga::updateOrCreate(['no' => $data['no']], $data);
        }
    }
}
