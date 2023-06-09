<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Generate 8 random products with chance 10
        $products = [
            [
                'name' => 'Try Again',
                'slug' => 'try-again',
                'chance' => 40,
                'uuid' => Str::uuid()
            ],
            [
                'name' => 'Product Two',
                'slug' => 'product-two',
                'chance' => 10,
                'uuid' => Str::uuid()
            ],
            [
                'name' => 'Product Three',
                'slug' => 'product-three',
                'chance' => 10,
                'uuid' => Str::uuid()
            ],
            [
                'name' => 'Product Four',
                'slug' => 'product-four',
                'chance' => 10,
                'uuid' => Str::uuid()
            ],
            [
                'name' => 'Product Five',
                'slug' => 'product-five',
                'chance' => 10,
                'uuid' => Str::uuid()
            ],
            [
                'name' => 'Product Six',
                'slug' => 'product-six',
                'chance' => 10,
                'uuid' => Str::uuid()
            ],
            [
                'name' => 'Product Seven',
                'slug' => 'product-seven',
                'chance' => 10,
                'uuid' => Str::uuid()
            ],
            [
                'name' => 'Product Eight',
                'slug' => 'product-eight',
                'chance' => 10,
                'uuid' => Str::uuid()
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

    }
}
