<?php

namespace App\Http\Controllers\Api;

use App\Models\Property;
use App\Http\Filters\PropertyFilter;
use App\Http\Resources\PropertyResource;
use App\Http\Controllers\Controller;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PropertyFilter $filters)
    {
        return PropertyResource::collection(Property::filter($filters)->paginate());
    }

    /**
     * Display the specified resource.
     */
    public function show($province, PropertyFilter $filters)
    {
        $properties = Property::whereHas('geo', function ($query) use ($province) {
            $query->where('province', $province);
        });
        return PropertyResource::collection($properties->filter($filters)->paginate());

    }
}
