<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];

    // One customer will have only one reward
    public function reward()
    {
        return $this->hasMany(Reward::class);
    }

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
}
