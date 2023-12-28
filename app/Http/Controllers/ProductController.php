<?php

namespace App\Http\Controllers;

use App\Http\Services\ProductService;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="Api v1",
 *     version="1.0",
 *     description="API backend on Laravel 10 with MySQL database",
 *     @OA\Contact(
 *         email="sandra.mangasaryan@gmail.com",
 *         name="Alexandra"
 *     )
 * )
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Get a list of products",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="price", type="integer"),
     *                     @OA\Property(property="count", type="integer"),
     *                     @OA\Property(
     *                         property="product_props",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer"),
     *                             @OA\Property(property="product_id", type="integer"),
     *                             @OA\Property(property="name", type="string"),
     *                             @OA\Property(property="value", type="string"),
     *                             @OA\Property(property="created_at", type="string"),
     *                             @OA\Property(property="updated_at", type="string"),
     *                         )
     *                     ),
     *                 )
     *             ),
     *             @OA\Property(property="page", type="integer"),
     *             @OA\Property(property="pages", type="integer"),
     *             @OA\Property(property="limit", type="integer"),
     *             @OA\Property(property="total", type="integer"),
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     security={{ "jwt": {} }}
     * )
     */
    public function index(): array
    {
        return (new ProductService)->getAllProducts();
    }

    /**
     * @OA\Get(
     *     path="/api/products/filter",
     *     summary="Filter products by name",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=true,
     *         description="Filter products by name",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="price", type="integer"),
     *                 @OA\Property(property="count", type="integer"),
     *                 @OA\Property(property="created_at", type="string"),
     *                 @OA\Property(property="updated_at", type="string"),
     *                 @OA\Property(
     *                     property="product_props",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="product_id", type="integer"),
     *                         @OA\Property(property="name", type="string"),
     *                         @OA\Property(property="value", type="string"),
     *                         @OA\Property(property="created_at", type="string"),
     *                         @OA\Property(property="updated_at", type="string"),
     *                     )
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     security={{ "jwt": {} }}
     * )
     */
    public function filterByName(Request $request): Collection|array
    {
        $search = $request->input('search');

        return (new ProductService)->filterByName($search);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
