<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_category' => \App\Models\Category::inRandomOrder()->first()->id,
            'name' => 'Product ' . $this->faker->unique()->numberBetween(1),
            'sku' => strtoupper($this->faker->bothify('********')),
            'price' => $this->faker->randomFloat(2, 1, 10000),
            'quantity' => $this->faker->numberBetween(1, 15),
            'created_by' => \App\Models\User::inRandomOrder()->first()->id,
            'created_at' => now(),
            'updated_at' => null
        ];
    }
}
