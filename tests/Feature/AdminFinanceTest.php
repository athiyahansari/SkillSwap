<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Subject;
use App\Models\TutorProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFinanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_finances_page()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $tutorUser = User::factory()->create(['role' => 'tutor']);
        $learner = User::factory()->create(['role' => 'learner']);
        
        $subject = Subject::create(['name' => 'Vue', 'slug' => 'vue', 'description' => 'JS Framework']);
        
        $tutorProfile = TutorProfile::factory()->create([
            'user_id' => $tutorUser->id,
            'hourly_rate' => 60.00,
            'verification_status' => 'verified',
        ]);
        $tutorProfile->subjects()->attach($subject);

        Booking::create([
            'learner_id' => $learner->id,
            'tutor_profile_id' => $tutorProfile->id,
            'subject_id' => $subject->id,
            'session_date' => now()->subDays(1)->toDateString(),
            'session_time' => '10:00:00',
            'status' => 'completed',
            'hourly_rate' => 60.00,
            'platform_fee' => 6.00,
            'tutor_earnings' => 54.00,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.finances.index'));
        $response->assertStatus(200);
        $response->assertSee('Platform Finances');

        \Livewire\Livewire::test(\App\Livewire\AdminFinances::class)
            ->assertViewHas('totalEarnings', 6.00)
            ->assertViewHas('totalTransactions', 1)
            ->assertViewHas('chartData')
            ->assertViewHas('chartLabels')
            ->assertViewHas('subjectLabels')
            ->assertViewHas('subjectData');
    }

    public function test_non_admin_cannot_view_finances_page()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $response = $this->actingAs($learner)->get(route('admin.finances.index'));
        $response->assertRedirect(route('learner.dashboard'));
    }
}
