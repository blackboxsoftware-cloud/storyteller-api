<?php

namespace Database\Seeders;

use App\Models\ServiceProvider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ServiceProvider::factory()->count(10)->create();
    }
}
