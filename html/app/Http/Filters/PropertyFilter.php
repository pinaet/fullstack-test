<?php

namespace App\Http\Filters;

class PropertyFilter extends QueryFilter
{
    protected $sortable = [
        'price',
        'date_listed' => 'created_at',
    ];

    public function for_sale($value) {
        return $this->builder->where('for_sale', $value);
    }

    public function street($value)
    {
        $likeStr = str_replace('*', '%', $value);
        return $this->builder->whereHas('geo', function ($query) use ($likeStr) {
            $query->where('street', 'like', $likeStr);
        });
    }

    public function sold($value) {
        return $this->builder->where('sold', $value);
    }

    public function title($value) {
        $likeStr = str_replace('*','%',$value);
        return $this->builder->where('title','like', $likeStr);
    }
}

