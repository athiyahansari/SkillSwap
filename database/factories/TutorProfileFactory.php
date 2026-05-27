<?php

namespace Database\Factories;

use App\Models\TutorProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TutorProfile>
 */
class TutorProfileFactory extends Factory
{
    protected $model = TutorProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $templates = [
            [
                'first_name' => 'Sarah',
                'bio' => "Hey! I'm Sarah, a college student who loves math. I specialize in breaking down tricky calculus and algebra concepts. Let's solve your homework problems and exam prep together in a relaxed, friendly environment!",
                'education' => 'A-level Graduate (Math & Physics focus)',
                'experience' => 'Private peer mentor for calculus and programming basics',
                'profile_photo' => 'images/avatars/avatar-1.svg',
            ],
            [
                'first_name' => 'David',
                'bio' => "Hi, I'm David! I am a self-taught web developer. I can help you build your very first HTML/CSS/JS website, explain Git basics, or help you debug React issues. Let's make coding fun and easy!",
                'education' => 'Self-taught Web Developer & Bootcamp Graduate',
                'experience' => 'Freelance web developer with 3+ years of building projects',
                'profile_photo' => 'images/avatars/avatar-2.svg',
            ],
            [
                'first_name' => 'Elena',
                'bio' => "Hi there! I'm Elena, a graphic designer who loves Canva and Figma. I'll help you design eye-catching social media posts, presentation decks, or wireframes. No design background needed!",
                'education' => 'Graphic Design Student at local art college',
                'experience' => 'Created social media templates with 10k+ downloads',
                'profile_photo' => 'images/avatars/avatar-3.svg',
            ],
            [
                'first_name' => 'James',
                'bio' => "Hello, I'm James! I'm a computer science undergraduate. If you are struggling with Python debugging, recursion, or understanding data structures for your assignments, I'm here for a quick screen-share session to help you fix it.",
                'education' => 'Computer Science Undergraduate',
                'experience' => 'Helped over 50 beginners write their first Python programs',
                'profile_photo' => 'images/avatars/avatar-4.svg',
            ],
            [
                'first_name' => 'Emily',
                'bio' => "Hey everyone! I'm Emily, a recent business graduate. I'm passionate about creative writing and literature. I can help review your college/job application essays, polish your resume, or practice presentation skills.",
                'education' => 'Business Graduate passionate about Resume Writing',
                'experience' => 'Conducted workshops on resume writing and interview prep',
                'profile_photo' => 'images/avatars/avatar-5.svg',
            ],
            [
                'first_name' => 'Alex',
                'bio' => "Hi, I'm Alex! I make YouTube videos and love video editing. I can show you how to use CapCut or Premiere Pro, how to color grade simply, or how to trim and edit videos for social media.",
                'education' => 'Self-taught Video Editor',
                'experience' => '2+ years of editing TikToks and YouTube videos for clients',
                'profile_photo' => 'images/avatars/avatar-6.svg',
            ],
            [
                'first_name' => 'Marcus',
                'bio' => "Welcome! I'm Marcus, a freelance UI/UX designer. I offer quick feedback sessions on your Figma designs, portfolio reviews, or help you understand layout and typography basics.",
                'education' => 'Self-taught UI/UX Designer & Certified Creator',
                'experience' => 'Freelance web designer with 3+ years of building projects',
                'profile_photo' => 'images/avatars/avatar-7.svg',
            ],
            [
                'first_name' => 'Clara',
                'bio' => "Hi! I'm Clara, a pianist and music student. I can guide you through music notation, harmony, ear training, and composition. Whether you are preparing for music grades or writing your own songs, I will help you master the basics of music!",
                'education' => 'Music Theory Undergraduate',
                'experience' => '4 years of private music coaching and band instruction',
                'profile_photo' => 'images/avatars/avatar-8.svg',
            ],
            [
                'first_name' => 'Ryan',
                'bio' => "Hi, I'm Ryan! I'm a computer science undergraduate. I love bridging the gap between mathematical logic and coding. I can help you with algebra, calculus, or programming basics, showing how math applies directly to software.",
                'education' => 'Computer Science Undergraduate',
                'experience' => 'Private peer mentor for calculus and programming basics',
                'profile_photo' => 'images/avatars/avatar-9.svg',
            ],
            [
                'first_name' => 'Chloe',
                'bio' => "Hello! I'm Chloe. I help learners prepare for high school science quizzes and exam revision. I use interactive sketches and simple explanations to make physics and chemistry concepts click.",
                'education' => 'Biology and Chemistry Undergraduate',
                'experience' => '2 years guiding peers and high school students',
                'profile_photo' => 'images/avatars/avatar-10.svg',
            ]
        ];

        $template = fake()->randomElement($templates);

        return [
            'user_id' => User::factory()->tutor([
                'name' => $template['first_name'] . ' ' . fake()->lastName(),
            ]),
            'bio' => $template['bio'],
            'hourly_rate' => fake()->randomFloat(2, 15, 30),
            'education' => $template['education'],
            'experience' => $template['experience'],
            'verification_status' => fake()->randomElement(['verified', 'verified', 'pending', 'rejected']),
            'profile_photo' => $template['profile_photo'],
        ];
    }

    /**
     * Indicate that the tutor profile is verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => 'verified',
        ]);
    }

    /**
     * Indicate that the tutor profile is pending verification.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => 'pending',
        ]);
    }

    /**
     * Indicate that the tutor profile is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => 'rejected',
        ]);
    }
}
