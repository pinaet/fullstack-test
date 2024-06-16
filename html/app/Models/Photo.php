<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'thumb',
        'search',
        'full',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
