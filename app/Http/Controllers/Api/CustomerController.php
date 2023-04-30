<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'phone' => 'required|unique:customers,phone',
            ]);
    
            $customer = Customer::create($request->validated());
    
            return response()->json([
                'message' => 'Customer created successfully',
                'customer' => $customer
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'We encountered an error while creating the customer. Try again later.'
            ], 500);
        }
    }
}
