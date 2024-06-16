<?php

namespace Tests\Feature\Api;

use App\Models\Geo;
use Tests\TestCase;
use App\Models\Photo;
use App\Models\Property;
use App\Http\Controllers\Api\PropertyController;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PropertyControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexReturnsPropertiesList()
    {
        $response = $this->getJson('/api/properties');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
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
                        'geo' => [
                            'country',
                            'province',
                            'street',
                        ],
                        'photos' => [
                            'thumb',
                            'search',
                            'full',
                        ],
                        'date_listed',
                    ],
                ],
                'links',
                'meta',
            ]);
    }

    public function testPaginationReturns25PropertiesPerPage()
    {
        // Create sample properties
        Property::factory()->count(30)->create();

        $response = $this->getJson('/api/properties');

        $response->assertStatus(200)
            ->assertJsonCount(25, 'data')
            ->assertJsonStructure([
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'to',
                    'total',
                ],
            ]);
    }

    public function testSearchByTitle()
    {
        // Create sample properties
        Property::factory()->create(['title' => 'Amazing Villa']);
        Property::factory()->create(['title' => 'Cozy Apartment']);

        $response = $this->getJson('/api/properties?filter[title]=*villa*');

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Amazing Villa'])
            ->assertJsonMissing(['title' => 'Cozy Apartment']);
    }

    public function testSearchByLocation()
    {
        // Create sample properties with associated Geo records
        $property1 = Property::factory()->create();
        Geo::factory()->create([
            'property_id' => $property1->id,
            'street' => 'Karen Street',
        ]);

        $property2 = Property::factory()->create();
        Geo::factory()->create([
            'property_id' => $property2->id,
            'street' => 'Sukhumvit Road',
        ]);

        $response = $this->getJson('/api/properties?filter[street]=*Karen*');

        $response->assertStatus(200)
            ->assertJsonFragment(['street' => 'Karen Street']) //->assertJsonPath('data.0.geo.street', 'Karen Street')
            ->assertJsonMissing(['street' => 'Sukhumvit Road']);
    }

    public function testSearchByTitleAndLocation()
    {
        // Create sample properties with associated Geo records
        $property1 = Property::factory()->create(['title' => 'Amazing Villa']);
        Geo::factory()->create([
            'property_id' => $property1->id,
            'street' => 'Karen Street',
        ]);

        $property2 = Property::factory()->create(['title' => 'Cozy Apartment']);
        Geo::factory()->create([
            'property_id' => $property2->id,
            'street' => 'Sukhumvit Road',
        ]);

        $property3 = Property::factory()->create(['title' => 'Luxurious Villa']);
        Geo::factory()->create([
            'property_id' => $property3->id,
            'street' => 'Silom Road',
        ]);

        $response = $this->getJson('/api/properties?filter[title]=*villa*&filter[street]=*Karen*');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Amazing Villa')
            ->assertJsonPath('data.0.geo.street', 'Karen Street')
            ->assertJsonMissing(['title' => 'Cozy Apartment'])
            ->assertJsonMissing(['title' => 'Luxurious Villa']);
    }

    public function testSortByPrice()
    {
        // Create sample properties
        Property::factory()->create(['price' => 1000]);
        Property::factory()->create(['price' => 500]);
        Property::factory()->create(['price' => 750]);

        $response = $this->getJson('/api/properties?sort=price');

        $response->assertStatus(200);
        $prices = collect($response->json('data'))->pluck('price')->toArray();
        $this->assertEquals([500, 750, 1000], $prices);
    }

    public function testSortByDateListed()
    {
        // Create sample properties
        Property::factory()->create(['created_at' => '2023-01-01']);
        Property::factory()->create(['created_at' => '2023-03-01']);
        Property::factory()->create(['created_at' => '2023-02-01']);

        $response = $this->getJson('/api/properties?sort=date_listed');

        $response->assertStatus(200);
        $dates = collect($response->json('data'))->pluck('date_listed')->toArray();
        $this->assertEquals(['2023-01-01T00:00:00.000000Z', '2023-02-01T00:00:00.000000Z', '2023-03-01T00:00:00.000000Z'], $dates);
    }

    public function testInvalidSearchQuery()
    {
        $response = $this->getJson('/api/properties?invalid=query');

        $response->assertStatus(200);
    }

    public function testMissingData()
    {
        $response = $this->getJson('/api/properties');

        $response->assertStatus(200)
            ->assertJsonMissingExact([
                'title' => null,
            ]);
    }

    public function testUnexpectedDatabaseError()
    {
        // Mock a database error by forcing an exception
        $this->mock(PropertyController::class)
            ->shouldReceive('index')
            ->andThrow(new \Exception('Database error'));

        $response = $this->getJson('/api/properties');

        $response->assertStatus(500);
    }
}
