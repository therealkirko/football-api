<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Shift;
use App\Models\Engagement;
use App\Models\Ambassador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EngagementController extends Controller
{
    public function index($instoreId)
    {
        try {
            $user = Ambassador::where('id', Auth::user()->id)->first();
            $engagements = Engagement::where('instore_id', $instoreId)
                ->where('ambassador_id', $user->id)
                ->get();

            return response()->json([
                'engagements' => $engagements
            ], 200);

        } catch (\Exception $e) {
            Log::error("Message: {$e->getMessage()} Line {$e->getLine()}");
            return response()->json([
                'error' => true,
                'message' => "Oops!! Something went wrong while fetching customer engagements. Please try again later." 
            ], 500);
        }
        
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'phone' => 'required',
                'feedback' => 'required',
                'instore' => 'required',
            ]);
    
            $user = Ambassador::where('id', Auth::user()->id)->first();
            $shift = Shift::where('ambassador_id', $user->id)
                ->where('instore_id', $request->instore)
                ->where('created_at', '>=', Carbon::today())
                ->first();
    
            $user->engagements()->create([
                'name' => $request->name,
                'phone' => $request->phone,
                'feedback' => $request->feedback,
                'shift_id' => $shift->id,
                'instore_id' => $request->instore,
            ]);
        } catch (\Exception $e) {
            Log::error("Message: {$e->getMessage()} Line {$e->getLine()}");
            return response()->json([
                'error' => true,
                'message' => "Oops!! Something went wrong while submitting customer engagements. Please try again later." 
            ], 500);
        }
    }
}
