<?php

namespace Database\Factories;

use App\Models\Penjualan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Penjualan>
 */
class PenjualanFactory extends Factory
{
    protected $model = Penjualan::class;

    public function definition(): array
    {
        $total = fake()->numberBetween(1000, 100000);

        return [
            'no_transaksi' => fake()->unique()->bothify('TRX########'),
            'tanggal' => fake()->dateTimeBetween('-30 days'),
            'total' => $total,
            'bayar' => $total,
            'kembalian' => 0,
        ];
    }
}
