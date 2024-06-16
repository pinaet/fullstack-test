<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geo extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'country',
        'province',
        'street',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
