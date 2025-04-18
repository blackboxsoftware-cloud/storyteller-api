<?php

namespace Database\Factories;

use App\Models\StoryTeller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StoryTellerFactory extends Factory
{
    protected $model = StoryTeller::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'user_id' => '011746d6-002d-4bb0-b25c-429ceb300d6e',
            'description' => $this->faker->paragraph(),
            'social_media' => json_encode([
                'instagram' => $this->faker->url(),
                'twitter' => $this->faker->url(),
                'linkedin' => $this->faker->url(),
            ]),
            'profile_image' => $this->faker->imageUrl(),
            'location' => $this->faker->city(),
            'availability' => $this->faker->sentence(),
            'preferred_genres' => json_encode($this->faker->randomElements([
                'Writer', 'Illustrator', 'Editor', 'Designer'
            ], 3)),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
