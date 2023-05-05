<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        try {            
            $products = Product::where('status', 1)->get();
            if(empty($products)) {
                return response()->json([
                    'error' => 'No products found.'
                ], 404);
            }
            $tryAgainProducts = $products->where('slug', 'try-again');
            $otherProducts = $products->where('slug', '!=', 'try-again');

            // Select one "try again" product
            $selectedProducts = collect([$tryAgainProducts->random()]);
            $attempts = 0;
    
            // Select two other products
            while ($selectedProducts->count() < 3 && $attempts < 200) {
                $randomProduct = $otherProducts->random();
                if (!$selectedProducts->contains('id', $randomProduct->id) && rand(1, 100) <= $randomProduct->chance) {
                    $selectedProducts->push($randomProduct);
                }
                $attempts++;
            }

            // Randomize the order of the products
            $selectedProducts = $selectedProducts->shuffle();

            return response()->json([
                'products' => $selectedProducts
            ], 200);

        } catch (\Exception $exception) {
            Log::error("Messgae: {$exception->getMessage()}, File: {$exception->getFile()}, Line: {$exception->getLine()}");
            return response()->json([
                'error' => 'We encountered an error while fetching the products. Try again later.'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'chance' => 'required|integer'
            ]);
    
            Product::create([
                'uuid' => Str::uuid(),
                'name' => $request->name,
                'slug' => Str::slug($request->name, $separator = '-', $language = 'en'),
                'chance' => $request->chance
            ]);
    
            return response()->json([
                'message' => 'You have successfully created the product.'
            ]);
        } catch (\Exception $exception) {
            Log::error("Messgae: {$exception->getMessage()}, File: {$exception->getFile()}, Line: {$exception->getLine()}");
            return response()->json([
                'error' => 'We encountered an error while fetching the products. Try again later.'
            ], 500);
        }
    }
}
