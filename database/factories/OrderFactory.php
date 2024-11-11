<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'product_type_id' => ProductType::factory(),
            'need_by_date' => fake()->dateTimeBetween('+1 day', '+1 year')
        ];
    }
}
