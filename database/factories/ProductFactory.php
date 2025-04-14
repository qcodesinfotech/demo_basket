<?php
namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'sku' => $this->faker->unique()->bothify('SKU-###'),
            'name' => $this->faker->words(2, true),
            'price' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}
