<?php

namespace Database\Factories;

use App\Models\Photo;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhotoFactory extends Factory
{
    protected $model = Photo::class;

    public function definition()
    {
        return [
            'thumb' => $this->faker->imageUrl(150, 100),
            'search' => $this->faker->imageUrl(300, 150),
            'full' => $this->faker->imageUrl(600, 300),
        ];
    }
}
