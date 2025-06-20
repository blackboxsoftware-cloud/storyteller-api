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
            'Story Development & Coaching',
            'Writing Support & Accountability',
            'Research & World Building Assistance',
            'Manuscript Critique & Feedback (Beta Readers)',
            'Sensitivity Reading',
            'Developmental Editing',
            'Line Editing',
            'Copyediting',
            'Proofreading',
            'Book Formatting & Typesetting',
            'Cover Design & Illustration',
            'ISBN, Copyright, and Legal Services',
            'Publishing Services (Traditional, Self, Hybrid)',
            'Stockists',
            'Reviewers',
            'Marketing & Promotion',
            'Book Clubs',
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

