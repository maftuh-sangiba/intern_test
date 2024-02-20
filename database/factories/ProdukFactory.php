<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produk>
 */
class ProdukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product_name' => $this->faker->unique()->word,
            'product_description' => $this->faker->sentence,
            'product_price_capital' => $this->faker->numberBetween(100, 100000),
            'product_price_sell' => $this->faker->numberBetween(100, 100000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
