<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Geo;
use App\Models\Photo;
use App\Models\Property;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PropertySeeder extends Seeder
{
    public function run()
    {
        // Truncate the tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Property::truncate();
        Geo::truncate();
        Photo::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Read the JSON file
        $jsonFile = File::get(storage_path('app/properties.json'));
        $properties = json_decode($jsonFile, true);

        foreach ($properties as $propertyData) {
            // Create a new property
            $property = Property::create([
                'title' => $propertyData['title'],
                'description' => $propertyData['description'],
                'for_sale' => $propertyData['for_sale'],
                'for_rent' => $propertyData['for_rent'],
                'sold' => $propertyData['sold'],
                'price' => $propertyData['price'],
                'currency' => $propertyData['currency'],
                'currency_symbol' => $propertyData['currency_symbol'],
                'property_type' => $propertyData['property_type'],
                'bedrooms' => $propertyData['bedrooms'],
                'bathrooms' => $propertyData['bathrooms'],
                'area' => $propertyData['area'],
                'area_type' => $propertyData['area_type'],
                'created_at' => Carbon::now()->subDays(rand(1, 365))->format('Y-m-d H:i:s'),
            ]);

            // Create the associated geo location
            Geo::create([
                'property_id' => $property->id,
                'country' => $propertyData['geo']['country'],
                'province' => $propertyData['geo']['province'],
                'street' => $propertyData['geo']['street'],
            ]);

            // Create the associated photos
            Photo::create([
                'property_id' => $property->id,
                'thumb' => $propertyData['photos']['thumb'],
                'search' => $propertyData['photos']['search'],
                'full' => $propertyData['photos']['full'],
            ]);
        }
    }
}
