<?php

namespace App\Http\Controllers;

use App\Models\Instore;
use App\Models\Ambassador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    public function instore()
    {
        try {

            $user = Ambassador::where('id', Auth::user()->id)->first();
            $campains = $user->instores()->get();

            return response()->json([
                'instores' => $campains
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function showInstore($id)
    {
        try {
            $instore = Instore::where('uuid', $id)->first();

            return response()->json($instore, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
