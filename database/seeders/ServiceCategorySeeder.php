<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;
use Illuminate\Support\Str;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
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

        foreach ($categories as $category) {
            if (!ServiceCategory::where('name', $category)->exists()) {
                ServiceCategory::create([
                    'id' => Str::uuid(),
                    'name' => $category,
                    'description' => fake()->sentence(),
                    'image_url' => fake()->imageUrl(640, 480, 'business'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
