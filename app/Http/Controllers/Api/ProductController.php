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
            $tryAgainProducts = $products->where('slug', 'try-again');

            $selectedProducts = collect();
            $attempts = 0;

            // Select "try again" products until we have at least 2.
            while ($selectedProducts->where('slug', 'try-again')->count() < 4 && $attempts < 100) {
                if (rand(1, 100) <= 40) {
                    $selectedProducts->push($tryAgainProducts->random());
                }
                $attempts++;
            }

            // Select non-"try again" products until we have a total of 6.
            while ($selectedProducts->count() < 6 && $attempts < 200) {
                $randomProduct = $products->random();
                if ($randomProduct->slug !== 'try-again' && rand(1, 100) <= $randomProduct->chance) {
                    $selectedProducts->push($randomProduct);
                } elseif ($selectedProducts->where('slug', 'try-again')->count() < 2 && rand(1, 100) <= 40) {
                    $selectedProducts->push($tryAgainProducts->random());
                }
                $attempts++;
            }

            $selectedProducts = $selectedProducts->shuffle();

            return response()->json([
                'products' => $selectedProducts
            ]);

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
