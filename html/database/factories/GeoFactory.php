<?php

namespace Database\Factories;

use App\Models\Geo;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class GeoFactory extends Factory
{
    protected $model = Geo::class;

    public function definition()
    {
        return [
            'property_id' => Property::factory(),
            'country' => $this->faker->country(),
            'province' => $this->faker->state(),
            'street' => $this->faker->streetAddress(),
        ];
    }
}
