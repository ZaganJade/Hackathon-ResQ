<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin ResQ',
            'email' => 'admin@resq.id',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@resq.id',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        // Create additional regular users
        User::factory(5)->create();

        // Run specific seeders
        $this->call([
            DisasterSeeder::class,
            ArticleSeeder::class,
            GuideSeeder::class,
        ]);
    }
}
