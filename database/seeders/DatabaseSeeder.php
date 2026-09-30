<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'kasir1'],
            [
                'name' => 'Kasir 1',
                'email' => 'kasir1@tokomadura.local',
                'password' => '1234',
            ],
        );

        $barang = [
            ['BR01', 'Beras', 14113, 50000, 50, 'Kg'],
            ['GR02', 'Garam', 10875, 12000, 50, 'Kg'],
            ['GL03', 'Gula', 17520, 19000, 50, 'Kg'],
            ['KC04', 'Kecap', 14950, 16000, 50, '725 ml'],
            ['KP05', 'Kopi', 17100, 19000, 50, '10 Sachet'],
            ['MS06', 'Mie', 3400, 5000, 50, 'pcs'],
            ['MY07', 'Minyak', 3400, 5000, 50, 'pcs'],
            ['SS08', 'Susu', 9000, 12000, 50, '6 Pcs'],
            ['TL09', 'Telor', 27483, 30000, 50, 'Kg'],
            ['TP10', 'Tepung', 11333, 15000, 50, 'Kg'],
        ];

        foreach ($barang as [$kode, $nama, $hargaBeli, $hargaJual, $stok, $satuan]) {
            Barang::firstOrCreate(
                ['kode_barang' => $kode],
                [
                    'nama_barang' => $nama,
                    'harga_beli' => $hargaBeli,
                    'harga_jual' => $hargaJual,
                    'stok_barang' => $stok,
                    'satuan' => $satuan,
                ],
            );
        }

        Supplier::firstOrCreate(
            ['kode_supplier' => 'SUP001'],
            [
                'nama_supplier' => 'Supplier Utama',
                'alamat' => 'Jl. Supplier No. 123',
                'telepon' => '08123456789',
            ],
        );
    }
}
