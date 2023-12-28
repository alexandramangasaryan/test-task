<?php

namespace App\Http\Services;

use App\Models\Product;

class ProductService
{
    public function getAllProducts(): array
    {
        $perPage = 40;
        $products = Product::query()->with('productProps')->paginate($perPage);

        return [
            'data' => $products->items(),
            'page' => $products->currentPage(),
            'pages' => $products->lastPage(),
            'limit' => $perPage,
            'total' => $products->total(),
        ];
    }

    public function filterByName($name): \Illuminate\Database\Eloquent\Collection|array
    {
        return Product::query()->where('name', 'like', "%$name%")->with('productProps')->get();
    }
}
