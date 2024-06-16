<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeoResource extends JsonResource
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
            'country' => $this->country,
            'province' => $this->province,
            'street' => $this->street,
        ];
    }
}
