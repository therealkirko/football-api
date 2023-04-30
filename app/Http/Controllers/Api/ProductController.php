<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        try {
            // Fetch all products from the database
            $products = Product::all();

            // Assign a random chance to each product
            $result = $products->map(function ($product) {
                // Assign a 10% chance to all products except "Try Again"
                if ($product->name !== "Try Again") {
                    $product->chance = $product->chance ?: 10;
                }
                // Assign a 40% chance to "Try Again"
                else {
                    $product->chance = $product->chance ?: 40;
                }
                return $product;
            });

            // Shuffle the products
            $result = $result->shuffle();

            // Filter out the products that didn't make the cut
            $result = $result->filter(function ($product, $index) {
                // Only include up to 5 of the same product
                if ($index < 5 || $result->where('name', $product->name)->count() < 5) {
                    return true;
                }
                return false;
            });

            // Limit the result to 6 products
            $result = $result->take(6);


            return response()->json([
                'products' => $result
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'We encountered an error while fetching the products. Try again later.'
            ], 500);
        }
    }
}
