<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    // One product can have many rewards
    public function rewards()
    {
        return $this->belongsToMany(Reward::class);
    }
}
