<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'for_sale' => $this->faker->boolean(),
            'for_rent' => $this->faker->boolean(),
            'sold' => $this->faker->boolean(),
            'price' => $this->faker->randomFloat(2, 100000, 1000000),
            'currency' => $this->faker->currencyCode(),
            'currency_symbol' => $this->faker->currencyCode(),
            'property_type' => $this->faker->randomElement(['Apartment', 'House', 'Condo', 'Townhouse', 'Villa']),
            'bedrooms' => $this->faker->numberBetween(1, 5),
            'bathrooms' => $this->faker->numberBetween(1, 3),
            'area' => $this->faker->numberBetween(50, 200),
            'area_type' => $this->faker->randomElement(['sqm', 'sqft']),
        ];
    }
}
