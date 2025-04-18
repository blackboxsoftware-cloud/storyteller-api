<?php

namespace Database\Factories;

use App\Models\ServiceProvider;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceProviderFactory extends Factory
{
    protected $model = ServiceProvider::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'user_id' => '7d7ea711-8dcb-45e3-aa16-c59827bd0b10',
            'service_category_id' => '4178d086-42b5-4af6-9b02-7c737d3c2070',
            'description' => $this->faker->paragraph(),
            'website' => $this->faker->url(),
            'social_media' => json_encode([
                'facebook' => $this->faker->url(),
                'twitter' => $this->faker->url(),
                'linkedin' => $this->faker->url(),
            ]),
            'profile_image' => $this->faker->imageUrl(),
            'location' => $this->faker->city(),
            'availability' => $this->faker->randomElement(['Full-time', 'Part-time', 'Contract']),
            'preferred_genres' => json_encode($this->faker->randomElements([
                'Writer', 'Illustrator', 'Editor', 'Designer'
            ], 3)),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
