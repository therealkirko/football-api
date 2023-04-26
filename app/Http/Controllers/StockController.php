<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Stock;
use App\Models\Shift;
use App\Models\Ambassador;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function update(Request $request)
    {
        try {
            Log::info($request->all());
            $request->validate([
                'type' => 'required',
                'product' => 'required',
                'instore' => 'required',
                'quantity' => 'required',
                'remarks' => 'required',
                'file' => 'mimes:jpg,png,jpeg|max:5048'
            ]);
    
            $user = Ambassador::where('id', Auth::user()->id)->first();
            $shift = Shift::where('ambassador_id', $user->id)
                ->where('instore_id', $request->instore)
                ->where('created_at', '>=', Carbon::today())
                ->first();
    
            $user->stocks()->create([
                'type' => $request->type,
                'shift_id' => $shift->id,
                'remarks' => $request->remarks,
                'quantity' => $request->quantity,
                'product_id' => $request->product,
                'instore_id' => $request->instore,
            ]);
    
            if($request->file) {
                $stock = Stock::where('shift_id', $shift->id)
                    ->where('product_id', $request->product)
                    ->where('instore_id', $request->instore)
                    ->first();
    
                $filename = time().'.'.$request->file->extension();
    
                $request->file->move(public_path('files'), $filename);
    
                $stock->images()->create([
                    'url' => $filename,
                    'ambassador_id' => $user->id,
                    'description' => 'stock update',
                ]);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => "Message: {$e->getMessage()} Line {$e->getLine()}" 
            ], 500);
        }
    }
}
