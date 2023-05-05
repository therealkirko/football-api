<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Reward;

class AnalyticsController extends Controller
{
    public function index() {
        try {
            $customers = Customer::where('status', true)->count();
            $products = Product::where('status', true)->count();
            $rewards = Reward::where('status', true)->count();

            return response()->json([
                'customers' => $customers,
                'products' => $products,
                'rewards' => $rewards
            ], 200);

        } catch (\Exception $exception) {
            Log::error("Messgae: {$exception->getMessage()}, File: {$exception->getFile()}, Line: {$exception->getLine()}");
            return response()->json([
                'error' => 'We encountered an error while creating the customer. Try again later.'
            ], 500);
        }
    }

    public function recentRewards() {
        try {
            $rewards = Reward::where('status', true)
                ->orderBy('created_at', 'desc')
                ->with('customer')
                ->with('product')
                ->take(5)
                ->get();

            // Format rewards to return reward uuid, customer name, product name and reward date
            $rewards = $rewards->map(function ($reward) {
                return [
                    'uuid' => $reward->uuid,
                    'customer_name' => $reward->customer->name,
                    'product_name' => $reward->product->name,
                    'reward_date' => $reward->created_at->format('d-m-Y')
                ];
            });

            return response()->json([
                'rewards' => $rewards
            ], 200);

        } catch (\Exception $exception) {
            Log::error("Messgae: {$exception->getMessage()}, File: {$exception->getFile()}, Line: {$exception->getLine()}");
            return response()->json([
                'error' => 'We encountered an error while creating the customer. Try again later.'
            ], 500);
        }
    }

    public function rewards() {
        try {
            $rewards = Reward::where('status', true)
                ->orderBy('created_at', 'desc')
                ->get();

            // Format rewards to return reward uuid, customer name, product name and reward date
            $rewards = $rewards->map(function ($reward) {
                return [
                    'uuid' => $reward->uuid,
                    'customer_name' => $reward->customer->name,
                    'product_name' => $reward->product->name,
                    'reward_date' => $reward->created_at->format('d-m-Y')
                ];
            });

            return response()->json([
                'rewards' => $rewards
            ], 200);

        } catch (\Exception $exception) {
            Log::error("Messgae: {$exception->getMessage()}, File: {$exception->getFile()}, Line: {$exception->getLine()}");
            return response()->json([
                'error' => 'We encountered an error while creating the customer. Try again later.'
            ], 500);
        }
    }

    public function customers()
    {
        try {
            // order record by created_at

            $customers = Customer::where('status', true)
                ->orderBy('created_at', 'desc')
                ->get();

            // Group customers by phone number
            $customers = $customers->groupBy('phone');

            return response()->json([
                'customers' => $customers
            ], 200);

        } catch (\Exception $exception) {
            Log::error("Messgae: {$exception->getMessage()}, File: {$exception->getFile()}, Line: {$exception->getLine()}");
            return response()->json([
                'error' => 'We encountered an error while creating the customer. Try again later.'
            ], 500);
        }
    }

    public function products() {
        try {
            $products = Product::where('status', true)->get();

            return response()->json([
                'products' => $products
            ], 200);

        } catch (\Exception $exception) {
            Log::error("Messgae: {$exception->getMessage()}, File: {$exception->getFile()}, Line: {$exception->getLine()}");
            return response()->json([
                'error' => 'We encountered an error while creating the customer. Try again later.'
            ], 500);
        }
    }
}
