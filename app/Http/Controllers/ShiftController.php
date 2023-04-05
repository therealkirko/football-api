<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Stock;
use App\Models\Shift;
use App\Models\Ambassador;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Ambassador::where('id', Auth::user()->id)->first();

            $user->shifts()->create([
                'status' => true,
                'speed' => $request->speed,
                'isMock' => $request->isMock,
                'accuracy' => $request->accuracy,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'clockin_time' => $request->clockin,
                'instore_id' => $request->instore_id,
                'uuid' => Str::upper(Str::random(6)),
            ]);

            return response()->json([
                'error' => false,
                'message' => 'You have successfully started your shift today.'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function stockTake(Request $request)
    {
        try {
            $user = Ambassador::where('id', Auth::user()->id)->first();

            $shift = Shift::where('ambassador_id', $user->id)
                ->whereDate('created_at', Carbon::today())
                ->first();

            $user->stocks()->create([
                'product_id' => $request->product,
                'quantity' => $request->quantity,
                'remarks' => $request->remarks,
                'shift_id' => $shift->id,
                'instore_id' => $request->instore
            ]);

            $shift->hasUpdatedStock = true;
            $shift->update();

            return response()->json([
                'error' => false,
                'message' => "You have successfully taken today's stock take."
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}