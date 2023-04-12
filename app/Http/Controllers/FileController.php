<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Shift;
use App\Models\Instore;
use App\Models\Ambassador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    public function storeInstoreFile(Request $request)
    {
        try {
            $request->validate([
                'instore_id' => 'required',
                'description' => 'required',
                'file' => 'required|mimes:jpg,png,jpeg|max:5048'
            ]);
    
            $filename = time().'.'.$request->file->extension();
            $user = Ambassador::where('id', Auth::user()->id)->first();
            $instore =  Instore::where('id', $request->instore_id)->first();
            $shift = Shift::where('ambassador_id', $user->id)
                    ->whereDate('created_at', Carbon::today())
                    ->first();
    
            if(!$instore) {
                return response()->json([
                    'error' => true,
                    'message' => "Instore campaign could not be found."
                ], 400);
            }
    
            $request->file->move(public_path('files'), $filename);
    
            $shift->images()->create([
                'url' => $filename,
                'ambassador_id' => $user->id,
                'description' => $request->description,
            ]);
    
            return response()->json([
                'error' => false,
                'message' => "Successfully uploaded file."
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => "Oops!! Failed while trying to upload file. Try again"
            ], 500);
        }
    }
}
