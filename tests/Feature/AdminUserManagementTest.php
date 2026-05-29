<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Subject;
use App\Models\TutorProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Guest cannot access the user overview route.
     */
    public function test_guest_cannot_access_user_overview(): void
    {
        $response = $this->get(route('admin.users.index'));
        $response->assertRedirect('/login');
    }

    /**
     * Learners cannot access the user overview route.
     */
    public function test_learner_cannot_access_user_overview(): void
    {
        $learner = User::factory()->create(['role' => 'learner']);
        
        $this->actingAs($learner);
        
        $response = $this->get(route('admin.users.index'));
        $response->assertRedirect(route('learner.dashboard'));
    }

    /**
     * Tutors cannot access the user overview route.
     */
    public function test_tutor_cannot_access_user_overview(): void
    {
        $tutor = User::factory()->create(['role' => 'tutor']);
        
        $this->actingAs($tutor);
        
        $response = $this->get(route('admin.users.index'));
        $response->assertRedirect(route('tutor.dashboard'));
    }

    /**
     * Admins can view the user overview page and verify it lists users.
     */
    public function test_admin_can_view_user_overview(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $learner = User::factory()->create(['name' => 'John Doe', 'role' => 'learner']);
        $tutor = User::factory()->create(['name' => 'Jane Smith', 'role' => 'tutor']);
        TutorProfile::factory()->create(['user_id' => $tutor->id]);

        $this->actingAs($admin);

        $response = $this->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('Jane Smith');
    }

    /**
     * Search filtering works correctly.
     */
    public function test_admin_can_search_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $learner1 = User::factory()->create(['name' => 'Alice Green', 'role' => 'learner']);
        $learner2 = User::factory()->create(['name' => 'Bob Brown', 'role' => 'learner']);

        $this->actingAs($admin);

        $response = $this->get(route('admin.users.index', ['search' => 'Alice']));
        $response->assertStatus(200);
        $response->assertSee('Alice Green');
        $response->assertDontSee('Bob Brown');
    }

    /**
     * Role filtering works correctly.
     */
    public function test_admin_can_filter_users_by_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $learner = User::factory()->create(['name' => 'Alice Green', 'role' => 'learner']);
        $tutor = User::factory()->create(['name' => 'Bob Brown', 'role' => 'tutor']);
        TutorProfile::factory()->create(['user_id' => $tutor->id]);

        $this->actingAs($admin);

        // Filter by learner
        $response = $this->get(route('admin.users.index', ['role' => 'learner']));
        $response->assertStatus(200);
        $response->assertSee('Alice Green');
        $response->assertDontSee('Bob Brown');

        // Filter by tutor
        $response = $this->get(route('admin.users.index', ['role' => 'tutor']));
        $response->assertStatus(200);
        $response->assertSee('Bob Brown');
        $response->assertDontSee('Alice Green');
    }

    /**
     * Session counts, tutor earnings, and learner spent amounts are computed and displayed.
     */
    public function test_user_overview_shows_computed_metrics(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $learner = User::factory()->create(['name' => 'John Learner', 'role' => 'learner']);
        $tutor = User::factory()->create(['name' => 'Jane Tutor', 'role' => 'tutor']);
        $profile = TutorProfile::factory()->create([
            'user_id' => $tutor->id,
            'hourly_rate' => 50.00
        ]);

        $subject = Subject::create(['name' => 'Physics', 'slug' => 'physics']);
        $profile->subjects()->attach($subject);

        // Create completed booking between them
        Booking::create([
            'learner_id' => $learner->id,
            'tutor_profile_id' => $profile->id,
            'subject_id' => $subject->id,
            'session_date' => now()->subDays(2)->format('Y-m-d'),
            'session_time' => '14:00:00',
            'status' => 'completed',
            'hourly_rate' => 50.00,
            'platform_fee' => 5.00,
            'tutor_earnings' => 45.00,
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('admin.users.index'));
        $response->assertStatus(200);

        // Verify metrics are visible in HTML output
        $response->assertSee('1'); // session count
        $response->assertSee('Spent: <span class="text-indigo-600">$50.00</span>', false);
        $response->assertSee('Earned: <span class="text-purple-600">$45.00</span>', false);
        $response->assertSee('Platform Fees: <span class="text-green-600">+$5.00</span>', false);
    }

    /**
     * Test the Livewire component interactivity directly.
     */
    public function test_livewire_component_interactivity(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $learner = User::factory()->create(['name' => 'Alice Green', 'role' => 'learner']);
        $tutor = User::factory()->create(['name' => 'Bob Brown', 'role' => 'tutor']);
        TutorProfile::factory()->create(['user_id' => $tutor->id]);

        $this->actingAs($admin);

        \Livewire\Livewire::test(\App\Livewire\AdminUsersList::class)
            ->assertSee('Alice Green')
            ->assertSee('Bob Brown')
            ->set('search', 'Alice')
            ->assertSee('Alice Green')
            ->assertDontSee('Bob Brown')
            ->set('search', '')
            ->set('role', 'tutor')
            ->assertSee('Bob Brown')
            ->assertDontSee('Alice Green')
            ->call('clearFilters')
            ->assertSee('Alice Green')
            ->assertSee('Bob Brown');
    }
}
