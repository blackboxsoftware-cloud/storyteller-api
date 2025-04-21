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
        $categories = [
            'Ghostwriting',
            'Editing & Proofreading',
            'Book Cover Design',
            'Illustration & Artwork',
            'Beta Reading',
            'Formatting & Layout',
            'Publishing Consultancy',
            'Audiobook Narration',
            'Manuscript Evaluation',
            'Content Strategy',
            'Book Coaching',
            'Copyediting',
            'Developmental Editing',
            'Marketing & Book Promotion',
            'Author Branding',
            'ISBN & Copyright Assistance',
            'Print-on-Demand Setup',
            'Self-Publishing Support',
            'Translation Services',
            'Query Letter & Synopsis Writing',
        ];


        return [
            'id' => Str::uuid(),
            'name' => $this->faker->unique()->randomElement($categories),
            'description' => $this->faker->optional()->sentence(),
            'image_url' => $this->faker->optional()->imageUrl(640, 480, 'business'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

