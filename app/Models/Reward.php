<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $guarded = [];

    // One customer will have only one reward
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // One reward can have many products
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
