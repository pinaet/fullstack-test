<?php

namespace App\Models;

use App\Http\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'for_sale',
        'for_rent',
        'sold',
        'price',
        'currency',
        'currency_symbol',
        'property_type',
        'bedrooms',
        'bathrooms',
        'area',
        'area_type',
    ];

    public function geo()
    {
        return $this->hasOne(Geo::class);
    }

    public function photos()
    {
        return $this->hasOne(Photo::class);
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters){
        return $filters->apply($builder);
    }
}
