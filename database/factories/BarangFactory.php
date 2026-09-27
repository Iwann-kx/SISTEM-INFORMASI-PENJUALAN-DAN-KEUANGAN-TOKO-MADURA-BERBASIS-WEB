<?php

namespace Database\Factories;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Barang>
 */
class BarangFactory extends Factory
{
    protected $model = Barang::class;

    public function definition(): array
    {
        return [
            'kode_barang' => fake()->unique()->bothify('BR####'),
            'nama_barang' => fake()->unique()->words(2, true),
            'harga_beli' => fake()->numberBetween(1000, 10000),
            'harga_jual' => fake()->numberBetween(11000, 20000),
            'stok_barang' => fake()->numberBetween(0, 100),
            'satuan' => fake()->randomElement(['pcs', 'Kg', 'pack']),
        ];
    }
}
