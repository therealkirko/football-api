<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ambassador;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function phoneVerify(Request $request)
    {
        try {
            $user = Ambassador::where('phone', $request->phone)->first();
            if(empty($user)){
                return response()->json([
                    'error' => true,
                    'message' => "Oops!! We couldn't found data for provided phone number"
                ], 400);
            }
            
            if(empty($user->password)) {
                return response()->json([
                    'error' => true,
                    'message' => "Your account is not properly set. Kindly set up your 4 digit pin to proceed"
                ], 405);
            }

            return response()->json([
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request){
        try {
            $user = Ambassador::where('phone', $request->phone)->first();

            if(!$user || !Hash::check($request['pin'], $user->password)) {
                return response()->json([
                    'error' => true,
                    'message' => 'We could not match any account with the credentials provided'
                ], 401);
            }

            $token = $user->createToken('auth')->plainTextToken;

            return response()->json([
                'access-token' => $token
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function confirmPin(Request $request)
    {
        try {
            $user = Ambassador::where('phone', $request->phone)->first();
            
            if(empty($user)){
                return response()->json([
                    'error' => true,
                    'message' => "Oops!! We couldn't found data for provided phone number"
                ], 400);
            }

            if(!Hash::check($request->pin, $user->password)) {
                return response()->json([
                    'error' => true,
                    'message' => "Oops!! You entered the wrong pin code. Try again"
                ], 400);
            }

            $token = $user->createToken('auth')->plainTextToken;
            
            $accessToken = base64_encode($token);

            return response()->json([
                'token' => $accessToken
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function setPin(Request $request)
    {
        try {
            $user = Ambassador::where('phone', $request->phone)->first();

            if(empty($user)){
                return response()->json([
                    'error' => true,
                    'message' => "Oops!! We couldn't found data for provided phone number"
                ], 400);
            }

            $user->password = Hash::make($request->pin);
            $user->update();

            return response()->json([
                'error' => false,
                'message' => "Your have successfully updated your account. Proceed to login."
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
