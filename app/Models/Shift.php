<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shift extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function images(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function engagements()
    {
        return $this->hasMany(Engagement::class);
    }
}
