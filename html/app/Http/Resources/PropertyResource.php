<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\GeoResource;
use App\Http\Resources\PhotoResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /*
        {
            "id": 29,
            "title": "2 bedroom Condo for sale in Samut Prakan",
            "description": "Unde sunt quam doloremque nihil ut velit et ipsum. Dolorum magnam voluptas vel ipsa impedit quo repudiandae. Praesentium sapiente libero harum occaecati beatae tempora.",
            "for_sale": true,
            "for_rent": false,
            "sold": false,
            "price": 635933,
            "currency": "THB",
            "currency_symbol": "\u0e3f",
            "property_type": "Condo",
            "bedrooms": 2,
            "bathrooms": 5,
            "area": 87,
            "area_type": "sqm",
            "geo": {
                "country": "Thailand",
                "province": "Samut Prakan",
                "street": "170 Scarlett Valley Apt. 942"
            },
            "photos": {
                "thumb": "https:\/\/placehold.co\/150x100",
                "search": "https:\/\/placehold.co\/300x150",
                "full": "https:\/\/placehold.co\/600x300"
            }
        }
        */
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'for_sale' => $this->for_sale,
            'for_rent' => $this->for_rent,
            'sold' => $this->sold,
            'price' => $this->price,
            'currency' => $this->currency,
            'currency_symbol' => $this->currency_symbol,
            'property_type' => $this->property_type,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'area' => $this->area,
            'area_type' => $this->area_type,
            'geo' => new GeoResource($this->geo),
            'photos' => new PhotoResource($this->photos),
            'date_listed' => $this->created_at,
        ];
    }
}
