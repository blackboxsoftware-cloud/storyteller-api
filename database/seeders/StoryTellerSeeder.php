<?php

namespace Database\Seeders;

use App\Models\StoryTeller;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoryTellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StoryTeller::factory()->count(10)->create();
    }
}
