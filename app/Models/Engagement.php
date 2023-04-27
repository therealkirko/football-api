<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Engagement extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function instore()
    {
        return $this->belongsTo(Instore::class);
    }

    public function ambassador()
    {
        return $this->belongsTo(Ambassador::class);
    }
}
