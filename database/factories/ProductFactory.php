<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->word();
        $internalPrice = fake()->randomNumber(5, true);
        $percentage = fake()->randomElement([10, 20, 30, 40, 50]);

        $salePrice = ceil((($internalPrice * $percentage) / 100) + $internalPrice);
        return [
            'image' => 'https://placehold.co/800@3x.png?text=' . $name,
            'name' => $name,
            'stock' => fake()->randomDigitNot(0),
            'note' => fake()->paragraph(),
            'internal_price' => $internalPrice,
            'profit_percentage' => $percentage,
            'sale_price' => $salePrice,
            'status' => fake()->randomElement([Product::STATUS_ACTIVE, Product::STATUS_INACTIVE])
        ];
    }
}
