<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log; 
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'phone' => 'required|unique:customers,phone',
            ]);

            $data['uuid'] = Str::uuid();
            $data['status'] = true;
    
            $customer = Customer::create($data);
    
            return response()->json($customer, 201);
        } catch (\Exception $exception) {
            Log::error("Messgae: {$exception->getMessage()}, File: {$exception->getFile()}, Line: {$exception->getLine()}");
            return response()->json([
                'error' => 'We encountered an error while creating the customer. Try again later.'
            ], 500);
        }
    }
}
