<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Client::factory()
            ->count(3)
            ->sequence(
                ['name' => 'Client 1'],
                ['name' => 'Client 2'],
                ['name' => 'Client 3'],
            )
            ->create();

        ProductType::factory()
            ->count(3)
            ->sequence(
                [
                    'name' => 'Type 1',
                    'production_speed' => 715
                ],
                [
                    'name' => 'Type 2',
                    'production_speed' => 770
                ],
                [
                    'name' => 'Type 3',
                    'production_speed' => 1000
                ],
            )
            ->create();

            Product::factory()
            ->count(6)
            ->sequence(
                [
                    'product_type_id' => 1,
                    'name' => 'Product A'
                ],
                [
                    'product_type_id' => 1,
                    'name' => 'Product B'
                ],
                [
                    'product_type_id' => 2,
                    'name' => 'Product C'
                ],
                [
                    'product_type_id' => 3,
                    'name' => 'Product D'
                ],
                [
                    'product_type_id' => 3,
                    'name' => 'Product E'
                ],
                [
                    'product_type_id' => 1,
                    'name' => 'Product F'
                ],
            )
            ->create();
    }
}
