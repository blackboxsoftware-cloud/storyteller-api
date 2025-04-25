<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $userId = Str::uuid()->toString();

            // Create the user
            DB::table('users')->insert([
                'id' => $userId,
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@storyteller.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('secretadmin123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create the admin
            DB::table('admins')->insert([
                'id' => Str::uuid()->toString(),
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}
