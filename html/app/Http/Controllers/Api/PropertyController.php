<?php

namespace App\Http\Controllers\Api;

use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Filters\PropertyFilter;
use App\Http\Resources\PropertyResource;
use App\Http\Controllers\Controller;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, PropertyFilter $filters)
    {
        $perPage = $request->input('per_page', 25); // Default to 25 if not provided
        return PropertyResource::collection(Property::filter($filters)->paginate($perPage));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $province, PropertyFilter $filters)
    {
        $properties = Property::whereHas('geo', function ($query) use ($province) {
            $query->where('province', $province);
        });

        // dd($properties->get(), count($properties->get()));

        $perPage = $request->input('per_page', 25); // Default to 25 if not provided
        return PropertyResource::collection($properties->filter($filters)->paginate($perPage));

    }
}
