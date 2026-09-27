<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Supplier>
 */
class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition(): array
    {
        return [
            'kode_supplier' => fake()->unique()->bothify('SUP####'),
            'nama_supplier' => fake()->company(),
            'alamat' => fake()->address(),
            'telepon' => fake()->numerify('08##########'),
        ];
    }
}
