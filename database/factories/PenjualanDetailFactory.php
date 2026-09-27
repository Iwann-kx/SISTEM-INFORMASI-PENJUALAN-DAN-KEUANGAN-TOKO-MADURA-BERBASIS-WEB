<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PenjualanDetail>
 */
class PenjualanDetailFactory extends Factory
{
    protected $model = PenjualanDetail::class;

    public function definition(): array
    {
        $qty = fake()->numberBetween(1, 5);
        $harga = fake()->numberBetween(1000, 20000);

        return [
            'penjualan_id' => Penjualan::factory(),
            'barang_id' => Barang::factory(),
            'qty' => $qty,
            'harga' => $harga,
            'subtotal' => $qty * $harga,
        ];
    }
}
