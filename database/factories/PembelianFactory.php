<?php

namespace Database\Factories;

use App\Models\Pembelian;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pembelian>
 */
class PembelianFactory extends Factory
{
    protected $model = Pembelian::class;

    public function definition(): array
    {
        return [
            'no_faktur' => fake()->unique()->bothify('FK########'),
            'supplier_id' => Supplier::factory(),
            'tanggal' => fake()->dateTimeBetween('-30 days'),
            'total' => fake()->numberBetween(1000, 100000),
        ];
    }
}
