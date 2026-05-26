<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $subjects = [
            ['name' => 'Mathematics', 'slug' => 'mathematics', 'description' => 'Algebra, Geometry, Calculus and beyond.'],
            ['name' => 'Physics', 'slug' => 'physics', 'description' => 'Classical mechanics, thermodynamics, and quantum physics.'],
            ['name' => 'Chemistry', 'slug' => 'chemistry', 'description' => 'Organic, inorganic, and physical chemistry.'],
            ['name' => 'Biology', 'slug' => 'biology', 'description' => 'Cell biology, genetics, and ecology.'],
            ['name' => 'English Literature', 'slug' => 'english-literature', 'description' => 'Classical and contemporary English prose and poetry.'],
            ['name' => 'Web Development', 'slug' => 'web-development', 'description' => 'HTML, CSS, JavaScript, PHP, Laravel and Livewire.'],
            ['name' => 'History', 'slug' => 'history', 'description' => 'World history, European history, and ancient civilizations.'],
            ['name' => 'Music Theory', 'slug' => 'music-theory', 'description' => 'Harmony, notation, and composition basics.'],
        ];

        foreach ($subjects as $subject) {
            \App\Models\Subject::updateOrCreate(['slug' => $subject['slug']], $subject);
        }
    }
}
