<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Subject;
use App\Models\TutorProfile;
use App\Models\Booking;
use App\Models\Review;
use App\Models\AvailabilitySlot;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Generate beautiful SVG gradient avatars in the local public directory
        if (!Storage::disk('public')->exists('profile-photos')) {
            Storage::disk('public')->makeDirectory('profile-photos');
        }

        $colors = [
            ['#4f46e5', '#7c3aed'],
            ['#0ea5e9', '#2563eb'],
            ['#10b981', '#059669'],
            ['#f59e0b', '#d97706'],
            ['#ec4899', '#db2777'],
            ['#84cc16', '#65a30d'],
            ['#06b6d4', '#0891b2'],
            ['#f43f5e', '#e11d48'],
            ['#a855f7', '#7e22ce'],
            ['#64748b', '#475569'],
        ];

        for ($i = 1; $i <= 10; $i++) {
            $gradient = $colors[($i - 1) % count($colors)];
            $initial = chr(64 + $i); // A, B, C, D...
            
            $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100">
    <defs>
        <linearGradient id="grad-$i" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:{$gradient[0]};stop-opacity:1" />
            <stop offset="100%" style="stop-color:{$gradient[1]};stop-opacity:1" />
        </linearGradient>
    </defs>
    <circle cx="50" cy="50" r="50" fill="url(#grad-$i)" />
    <text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" font-family="system-ui, -apple-system, sans-serif" font-size="40" font-weight="bold" fill="#ffffff">{$initial}</text>
</svg>
SVG;
            Storage::disk('public')->put("profile-photos/avatar-$i.svg", $svg);
        }

        // 2. Preserve existing production-safe subjects
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
            Subject::updateOrCreate(['slug' => $subject['slug']], $subject);
        }

        // Truncate dependent tables to start fresh when seeding
        Schema::disableForeignKeyConstraints();
        Booking::truncate();
        Review::truncate();
        AvailabilitySlot::truncate();
        Schema::enableForeignKeyConstraints();

        // 3. Seed primary test accounts
        // 1 Admin
        User::updateOrCreate([
            'email' => 'admin@skillswap.com',
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // 1 Primary Tutor (with TutorProfile)
        $primaryTutorUser = User::updateOrCreate([
            'email' => 'tutor@skillswap.com',
        ], [
            'name' => 'Primary Tutor',
            'password' => Hash::make('password'),
            'role' => 'tutor',
            'email_verified_at' => now(),
        ]);

        $primaryTutorProfile = TutorProfile::updateOrCreate([
            'user_id' => $primaryTutorUser->id,
        ], [
            'bio' => 'Hello! I am a passionate tutor offering online lessons. I specialize in Mathematics and Web Development, with a focus on practical applications and problem solving.',
            'hourly_rate' => 45.00,
            'education' => 'M.Sc. in Computer Science from MIT',
            'experience' => '5+ years of software industry experience and private tutoring',
            'verification_status' => 'verified',
            'profile_photo' => 'profile-photos/avatar-1.svg',
        ]);

        // 1 Primary Learner
        $primaryLearnerUser = User::updateOrCreate([
            'email' => 'learner@skillswap.com',
        ], [
            'name' => 'Primary Learner',
            'password' => Hash::make('password'),
            'role' => 'learner',
            'email_verified_at' => now(),
        ]);

        // Preserve legacy test user as learner
        User::updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => Hash::make('password'),
            'role' => 'learner',
            'email_verified_at' => now(),
        ]);

        // 4. Seed additional tutor profiles with associated users
        $tutors = [$primaryTutorProfile];
        for ($i = 1; $i <= 9; $i++) {
            $tutorUser = User::updateOrCreate([
                'email' => "tutor$i@skillswap.com",
            ], [
                'name' => fake()->name(),
                'password' => Hash::make('password'),
                'role' => 'tutor',
                'email_verified_at' => now(),
            ]);

            $profile = TutorProfile::updateOrCreate([
                'user_id' => $tutorUser->id,
            ], [
                'bio' => fake()->paragraph(3),
                'hourly_rate' => fake()->randomFloat(2, 20, 75),
                'education' => fake()->randomElement([
                    'M.Sc. in Computer Science from Stanford University',
                    'Ph.D. in Mathematics from MIT',
                    'B.A. in English Literature from Oxford University',
                    'M.Sc. in Physics from University of Cambridge',
                    'B.Ed. in Secondary Education from Boston University',
                    'Certified Web Development Instructor (W3C)',
                ]),
                'experience' => fake()->randomElement([
                    '5 years of private tutoring & high school teaching',
                    '3 years of university teaching assistant experience',
                    '10+ years of professional software engineering and mentoring',
                    '4 years of online chemistry tutoring',
                    '2 years as an ESL teacher in Japan',
                ]),
                'verification_status' => fake()->randomElement(['verified', 'verified', 'pending', 'rejected']),
                'profile_photo' => 'profile-photos/avatar-' . (($i % 10) ?: 10) . '.svg',
            ]);
            $tutors[] = $profile;
        }

        // 5. Seed additional learner users
        $learners = [$primaryLearnerUser];
        for ($i = 1; $i <= 9; $i++) {
            $learnerUser = User::updateOrCreate([
                'email' => "learner$i@skillswap.com",
            ], [
                'name' => fake()->name(),
                'password' => Hash::make('password'),
                'role' => 'learner',
                'email_verified_at' => now(),
            ]);
            $learners[] = $learnerUser;
        }

        // 6. Seed subject assignments in tutor_subject pivot table
        $allSubjects = Subject::all();
        foreach ($tutors as $index => $tutor) {
            if ($index === 0) {
                // Primary tutor teaches Mathematics & Web Development
                $subjectsToAttach = $allSubjects->filter(function ($sub) {
                    return in_array($sub->slug, ['web-development', 'mathematics']);
                });
            } else {
                $subjectsToAttach = $allSubjects->random(rand(1, 3));
            }
            $tutor->subjects()->sync($subjectsToAttach->pluck('id'));
        }

        // 7. Seed availability slots for all tutors
        foreach ($tutors as $tutor) {
            $days = fake()->randomElements(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], rand(3, 5));
            foreach ($days as $day) {
                $startHour = fake()->randomElement([9, 10, 11, 13, 14, 15, 16]);
                $start_time = sprintf('%02d:00:00', $startHour);
                $end_time = sprintf('%02d:00:00', $startHour + 2);

                AvailabilitySlot::updateOrCreate([
                    'tutor_profile_id' => $tutor->id,
                    'day' => $day,
                    'start_time' => $start_time,
                ], [
                    'end_time' => $end_time,
                    'is_available' => true,
                ]);
            }
        }

        // 8. Seed bookings with realistic statuses, matching tutor-subject relations
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        for ($i = 0; $i < 30; $i++) {
            $learner = fake()->randomElement($learners);
            $tutor = fake()->randomElement($tutors);

            $tutorSubjects = $tutor->subjects;
            if ($tutorSubjects->isEmpty()) {
                continue;
            }
            $subject = $tutorSubjects->random();
            $status = fake()->randomElement($statuses);

            if ($status === 'completed') {
                $date = fake()->dateTimeBetween('-1 month', 'yesterday')->format('Y-m-d');
            } else {
                $date = fake()->dateTimeBetween('today', '+1 month')->format('Y-m-d');
            }

            $booking = Booking::create([
                'learner_id' => $learner->id,
                'tutor_profile_id' => $tutor->id,
                'subject_id' => $subject->id,
                'session_date' => $date,
                'session_time' => fake()->randomElement(['09:00:00', '10:00:00', '11:00:00', '13:00:00', '14:00:00', '15:00:00', '16:00:00']),
                'status' => $status,
                'notes' => fake()->optional(0.7)->sentence(),
            ]);

            // 9. Seed reviews ONLY for completed bookings
            if ($status === 'completed' && fake()->boolean(85)) {
                Review::create([
                    'booking_id' => $booking->id,
                    'learner_id' => $learner->id,
                    'tutor_profile_id' => $tutor->id,
                    'rating' => fake()->numberBetween(3, 5),
                    'comment' => fake()->randomElement([
                        'Excellent lesson! The tutor explained everything clearly.',
                        'Very patient and structured. I learned a lot today.',
                        'Great tutor. Highly recommended for this subject.',
                        'Helped me prepare for my exam and I feel much more confident now.',
                        'Fantastic teacher! Very engaging and knowledgeable.',
                        'Good explanation of complex topics. Will book again.',
                    ]),
                ]);
            }
        }
    }
}
