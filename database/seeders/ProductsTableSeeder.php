<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductProp;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::factory(100)->create();

        foreach ($products as $product) {
            ProductProp::factory(3)->create(['product_id' => $product->id]);
        }
    }
}
