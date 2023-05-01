<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Reward;

class RewardController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'customer' => 'required',
                'product' => 'required'
            ]);
    
            $customer = Customer::where('uuid', $request->customer)->first();
            return $customer;

            $product = Product::where('uuid', $request->product)->first();
            
            if(empty($customer)) {
                return response()->json([
                    'error' => 'We could not find the customer in our records. Try again'
                ], 400);
            }
    
            if(empty($product)) {
                return response()->json([
                    'error' => 'We could not find the product in our records. Try again'
                ], 400);
            }

            Reward::create([
                'uuid' => Str::uuid(),
                'customer_id' => $request->customer,
                'product_id' => $request->product,
                'points' => 100,
            ]);

            return response()->json([
                'message' => 'You have successfully claimed your reward.'
            ]);

        } catch (\Exception $exception) {
            Log::error("Messgae: {$exception->getMessage()}, File: {$exception->getFile()}, Line: {$exception->getLine()}");
            return response()->json([
                'error' => 'We encountered an error while rewarding the customer. Try again later.'
            ], 500);
        }
    }
}
