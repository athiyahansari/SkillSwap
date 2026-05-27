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
        // 1. Setup static data arrays for realistic tutor seeding
        $tutorTemplates = [
            [
                'first_name' => 'Sarah',
                'name_gender' => 'female',
                'bio' => "Hey! I'm Sarah, a college student who loves math. I specialize in breaking down tricky calculus and algebra concepts. Let's solve your homework problems and exam prep together in a relaxed, friendly environment!",
                'education' => 'A-level Graduate (Math & Physics focus)',
                'experience' => 'Private peer mentor for calculus and programming basics',
                'profile_photo' => 'images/avatars/avatar-1.svg',
                'subjects' => ['mathematics', 'physics'],
                'hourly_rate' => 22.00,
            ],
            [
                'first_name' => 'David',
                'name_gender' => 'male',
                'bio' => "Hi, I'm David! I am a self-taught web developer. I can help you build your very first HTML/CSS/JS website, explain Git basics, or help you debug React issues. Let's make coding fun and easy!",
                'education' => 'Self-taught Web Developer & Bootcamp Graduate',
                'experience' => 'Freelance web developer with 3+ years of building projects',
                'profile_photo' => 'images/avatars/avatar-2.svg',
                'subjects' => ['web-development', 'javascript-basics'],
                'hourly_rate' => 25.00,
            ],
            [
                'first_name' => 'Elena',
                'name_gender' => 'female',
                'bio' => "Hi there! I'm Elena, a graphic designer who loves Canva and Figma. I'll help you design eye-catching social media posts, presentation decks, or wireframes. No design background needed!",
                'education' => 'Graphic Design Student at local art college',
                'experience' => 'Created social media templates with 10k+ downloads',
                'profile_photo' => 'images/avatars/avatar-3.svg',
                'subjects' => ['canva-design', 'figma-basics', 'social-media-design'],
                'hourly_rate' => 20.00,
            ],
            [
                'first_name' => 'James',
                'name_gender' => 'male',
                'bio' => "Hello, I'm James! I'm a computer science undergraduate. If you are struggling with Python debugging, recursion, or understanding data structures for your assignments, I'm here for a quick screen-share session to help you fix it.",
                'education' => 'Computer Science Undergraduate',
                'experience' => 'Helped over 50 beginners write their first Python programs',
                'profile_photo' => 'images/avatars/avatar-4.svg',
                'subjects' => ['python-programming', 'javascript-basics'],
                'hourly_rate' => 24.00,
            ],
            [
                'first_name' => 'Emily',
                'name_gender' => 'female',
                'bio' => "Hey everyone! I'm Emily, a recent business graduate. I'm passionate about creative writing and literature. I can help review your college/job application essays, polish your resume, or practice presentation skills.",
                'education' => 'Business Graduate passionate about Resume Writing',
                'experience' => 'Conducted workshops on resume writing and interview prep',
                'profile_photo' => 'images/avatars/avatar-5.svg',
                'subjects' => ['english-literature', 'resume-review', 'presentation-skills'],
                'hourly_rate' => 18.00,
            ],
            [
                'first_name' => 'Alex',
                'name_gender' => 'male',
                'bio' => "Hi, I'm Alex! I make YouTube videos and love video editing. I can show you how to use CapCut or Premiere Pro, how to color grade simply, or how to trim and edit videos for social media.",
                'education' => 'Self-taught Video Editor',
                'experience' => '2+ years of editing TikToks and YouTube videos for clients',
                'profile_photo' => 'images/avatars/avatar-6.svg',
                'subjects' => ['video-editing-basics', 'social-media-design'],
                'hourly_rate' => 21.00,
            ],
            [
                'first_name' => 'Marcus',
                'name_gender' => 'male',
                'bio' => "Welcome! I'm Marcus, a freelance UI/UX designer. I offer quick feedback sessions on your Figma designs, portfolio reviews, or help you understand layout and typography basics.",
                'education' => 'Self-taught UI/UX Designer & Certified Creator',
                'experience' => 'Freelance web designer with 3+ years of building projects',
                'profile_photo' => 'images/avatars/avatar-7.svg',
                'subjects' => ['ui-ux-design', 'figma-basics'],
                'hourly_rate' => 26.00,
            ],
            [
                'first_name' => 'Clara',
                'name_gender' => 'female',
                'bio' => "Hi! I'm Clara, a pianist and music student. I can guide you through music notation, harmony, ear training, and composition. Whether you are preparing for music grades or writing your own songs, I will help you master the basics of music!",
                'education' => 'Music Theory Undergraduate',
                'experience' => '4 years of private music coaching and band instruction',
                'profile_photo' => 'images/avatars/avatar-8.svg',
                'subjects' => ['music-theory'],
                'hourly_rate' => 23.00,
            ],
            [
                'first_name' => 'Ryan',
                'name_gender' => 'male',
                'bio' => "Hi, I'm Ryan! I'm a computer science undergraduate. I love bridging the gap between mathematical logic and coding. I can help you with algebra, calculus, or programming basics, showing how math applies directly to software.",
                'education' => 'Computer Science Undergraduate',
                'experience' => 'Private peer mentor for calculus and programming basics',
                'profile_photo' => 'images/avatars/avatar-9.svg',
                'subjects' => ['mathematics', 'python-programming', 'javascript-basics'],
                'hourly_rate' => 28.00,
            ],
            [
                'first_name' => 'Chloe',
                'name_gender' => 'female',
                'bio' => "Hello! I'm Chloe. I help learners prepare for high school science quizzes and exam revision. I use interactive sketches and simple explanations to make physics and chemistry concepts click.",
                'education' => 'Biology and Chemistry Undergraduate',
                'experience' => '2 years guiding peers and high school students',
                'profile_photo' => 'images/avatars/avatar-10.svg',
                'subjects' => ['physics', 'chemistry', 'biology'],
                'hourly_rate' => 20.00,
            ]
        ];

        // 2. Preserve existing production-safe subjects
        $subjects = [
            ['name' => 'Mathematics', 'slug' => 'mathematics', 'description' => 'Calculus, algebra, geometry and school exam prep.'],
            ['name' => 'Physics', 'slug' => 'physics', 'description' => 'Understand classical physics, mechanics, and homework help.'],
            ['name' => 'Chemistry', 'slug' => 'chemistry', 'description' => 'Equations, chemical bonds, and general chemistry revision.'],
            ['name' => 'Biology', 'slug' => 'biology', 'description' => 'Cell biology, genetics, and general biology support.'],
            ['name' => 'English Literature', 'slug' => 'english-literature', 'description' => 'Essay reviews, literature analysis, and reading comprehension.'],
            ['name' => 'Web Development', 'slug' => 'web-development', 'description' => 'Build websites using HTML, CSS, JavaScript, PHP, and Laravel.'],
            ['name' => 'History', 'slug' => 'history', 'description' => 'World history, historical events, and paper writing tips.'],
            ['name' => 'Music Theory', 'slug' => 'music-theory', 'description' => 'Learn music notation, basics of harmony, and song structure.'],
            // New practical/modern skills
            ['name' => 'Canva Design', 'slug' => 'canva-design', 'description' => 'Create quick designs for flyers, decks, and graphics in Canva.'],
            ['name' => 'Figma Basics', 'slug' => 'figma-basics', 'description' => 'Learn auto-layout, components, and basic wireframing in Figma.'],
            ['name' => 'Python Programming', 'slug' => 'python-programming', 'description' => 'Beginner-friendly scripts, loops, functions, and debugging.'],
            ['name' => 'JavaScript Basics', 'slug' => 'javascript-basics', 'description' => 'Learn DOM manipulation, events, functions, and API requests.'],
            ['name' => 'Resume Review', 'slug' => 'resume-review', 'description' => 'Get peer feedback on your resume layout, wording, and job applications.'],
            ['name' => 'Presentation Skills', 'slug' => 'presentation-skills', 'description' => 'Improve public speaking, slide design, and delivery confidence.'],
            ['name' => 'UI/UX Design', 'slug' => 'ui-ux-design', 'description' => 'User interface principles, layouts, and prototyping basics.'],
            ['name' => 'Social Media Design', 'slug' => 'social-media-design', 'description' => 'Create engaging posts, stories, and templates for social platforms.'],
            ['name' => 'Video Editing Basics', 'slug' => 'video-editing-basics', 'description' => 'Basic trimming, audio syncing, transitions, and export options.'],
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
        $primaryTutorTemplate = $tutorTemplates[0];
        $primaryTutorUser = User::updateOrCreate([
            'email' => 'tutor@skillswap.com',
        ], [
            'name' => $primaryTutorTemplate['first_name'] . ' ' . fake()->lastName(),
            'password' => Hash::make('password'),
            'role' => 'tutor',
            'email_verified_at' => now(),
        ]);

        $primaryTutorProfile = TutorProfile::updateOrCreate([
            'user_id' => $primaryTutorUser->id,
        ], [
            'bio' => $primaryTutorTemplate['bio'],
            'hourly_rate' => $primaryTutorTemplate['hourly_rate'],
            'education' => $primaryTutorTemplate['education'],
            'experience' => $primaryTutorTemplate['experience'],
            'verification_status' => 'verified',
            'profile_photo' => $primaryTutorTemplate['profile_photo'],
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
            $template = $tutorTemplates[$i];
            $tutorUser = User::updateOrCreate([
                'email' => "tutor$i@skillswap.com",
            ], [
                'name' => $template['first_name'] . ' ' . fake()->lastName(),
                'password' => Hash::make('password'),
                'role' => 'tutor',
                'email_verified_at' => now(),
            ]);

            $profile = TutorProfile::updateOrCreate([
                'user_id' => $tutorUser->id,
            ], [
                'bio' => $template['bio'],
                'hourly_rate' => $template['hourly_rate'],
                'education' => $template['education'],
                'experience' => $template['experience'],
                'verification_status' => fake()->randomElement(['verified', 'verified', 'pending', 'rejected']),
                'profile_photo' => $template['profile_photo'],
            ]);
            $tutors[] = $profile;
        }

        // 5. Seed additional learner users
        $learners = [$primaryLearnerUser];
        for ($i = 1; $i <= 9; $i++) {
            $learnerUser = User::updateOrCreate([
                'email' => "learner$i@skillswap.com",
            ], [
                'name' => fake()->firstName() . ' ' . fake()->lastName(),
                'password' => Hash::make('password'),
                'role' => 'learner',
                'email_verified_at' => now(),
            ]);
            $learners[] = $learnerUser;
        }

        // 6. Seed subject assignments in tutor_subject pivot table
        foreach ($tutors as $index => $tutor) {
            $template = $tutorTemplates[$index];
            $subjectsToAttach = Subject::whereIn('slug', $template['subjects'])->get();
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
                        'Excellent session! The guide explained everything clearly.',
                        'Very patient. Helped me solve my problem in no time!',
                        'Great guide. Highly recommended!',
                        'Helped me work through a difficult project. Highly recommended.',
                        'Fantastic guide! Very friendly and helpful.',
                        'Explained the concept perfectly. Will book another session.',
                    ]),
                ]);
            }
        }
    }
}
