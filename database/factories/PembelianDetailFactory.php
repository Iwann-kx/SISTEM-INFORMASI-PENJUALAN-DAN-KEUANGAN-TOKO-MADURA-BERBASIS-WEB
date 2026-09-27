<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PembelianDetail>
 */
class PembelianDetailFactory extends Factory
{
    protected $model = PembelianDetail::class;

    public function definition(): array
    {
        $qty = fake()->numberBetween(1, 5);
        $harga = fake()->numberBetween(1000, 20000);

        return [
            'pembelian_id' => Pembelian::factory(),
            'barang_id' => Barang::factory(),
            'qty' => $qty,
            'harga' => $harga,
            'subtotal' => $qty * $harga,
        ];
    }
}
