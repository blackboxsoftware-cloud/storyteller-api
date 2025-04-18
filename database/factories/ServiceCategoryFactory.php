<?php

namespace Database\Factories;

use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceCategoryFactory extends Factory
{
    protected $model = ServiceCategory::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'name' => $this->faker->unique()->jobTitle(), // or custom names if you want
            'description' => $this->faker->optional()->sentence(),
            'image_url' => $this->faker->optional()->imageUrl(640, 480, 'business'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

