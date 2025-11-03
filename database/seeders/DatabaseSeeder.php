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
        /** add admin user. */
        if (User::count() === 0) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('supersecurepassword'),
                'email_verified_at' => now(),
            ]);
        }

        /** add South African langauges. */
        $languages = [
            'Afrikaans', 'English', 'IsiXhosa', 'IsiZulu', 'Sepedi', 'Setswana',
            'Sesotho', 'Xitsonga', 'SiSwati', 'Tshivenda', 'Ndebele'
        ];

        foreach ($languages as $language) {
            \App\Models\Language::firstOrCreate(['name' => $language]);
        }

        /** add interests. */
        $interests = [
            'Reading', 'Cooking', 'Traveling', 'Music', 'Sports', 'Photography',
            'Painting', 'Gaming', 'Gardening', 'Dancing', 'Yoga', 'Cycling',
            'Hiking', 'Swimming', 'Writing', 'Coding', 'Movies', 'Meditation',
            'Fashion', 'Collecting'
        ];

        foreach ($interests as $interest) {
            \App\Models\Interest::firstOrCreate(['name' => $interest]);
        }
    }
}
