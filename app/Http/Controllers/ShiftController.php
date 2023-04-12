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
    public function checkStatus($instoreId)
    {
        try {

            $today = Carbon::today();

            $shift = Shift::where('ambassador_id', $user->id)
                ->where('instore_id', $instoreId)
                ->where('created_at', '>=', $today)
                ->first();

            if (!$shift){
                return response()->json([
                    'id' => 1,
                    'name' => 'clock in'
                ], 200);
            }else if(!$shift->hasPersonalPhoto) {
                return response()->json([
                    'id' => 2,
                    'name' => 'selfie',
                ], 200);
            }else if(!$shift->hasShelfPhoto) {
                return response()->json([
                    'id' => 3,
                    'name' => 'shelf photo'
                ], 200);
            }else if(!$shift->hasUpdatedStock) {
                return response()->json([
                    'id' => 4,
                    'name' => 'update stock'
                ], 200);
            }else {
                return response()->json([
                    'error' => false,
                    'message' => 'Completed onboarding process. Good luck on your shift.'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function checkClockIn($uuid)
    {
        $user = Ambassador::where('id', Auth::user()->id)->first();

        $shift = Shift::where('ambassador_id', $user->id)
            ->where('uuid', $uuid)
            ->whereDate('created_at', Carbon::today())
            ->first();

        if (!$shift){
            return response()->json([
                'id' => 1,
                'name' => 'clock in'
            ], 200);
        }else {
            return response()->json([
                'error' => false,
                'message' => 'Already clocked in.'
            ], 200);
        }
    }

    public function checkSelfie($uuid)
    {
        $user = Ambassador::where('id', Auth::user()->id)->first();

        $shift = Shift::where('ambassador_id', $user->id)
            ->where('uuid', $uuid)
            ->whereDate('created_at', Carbon::today())
            ->first();

        if(!$shift->hasPersonalPhoto) {
            return response()->json([
                'id' => 2,
                'name' => 'selfie',
            ], 200);
        }else {
            return response()->json([
                'error' => false,
                'message' => 'Already taken a selfie.',
            ], 200);
        }
    }

    public function checkShelfPhoto($uuid)
    {
        $user = Ambassador::where('id', Auth::user()->id)->first();

        $shift = Shift::where('ambassador_id', $user->id)
            ->where('uuid', $uuid)
            ->whereDate('created_at', Carbon::today())
            ->first();

        if(!$shift->hasShelfPhoto) {
            return response()->json([
                'id' => 2,
                'name' => 'shelf',
            ], 200);
        }else {
            return response()->json([
                'error' => false,
                'message' => 'Already taken a photo of shelf.',
            ], 200);
        }
    }

    public function checkStockUpdate()
    {
        $user = Ambassador::where('id', Auth::user()->id)->first();

        $shift = Shift::where('ambassador_id', $user->id)
            ->where('uuid', $uuid)
            ->whereDate('created_at', Carbon::today())
            ->first();

        if(!$shift->hasUpdatedStock) {
            return response()->json([
                'id' => 2,
                'name' => 'shelf',
            ], 200);
        }else {
            return response()->json([
                'error' => false,
                'message' => 'Already updated stock.',
            ], 200);
        }
    }

    public function index(Request $request)
    {
        try {
            $user = Ambassador::where('id', Auth::user()->id)->first();

            $user->shifts()->create([
                'status' => true,
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
