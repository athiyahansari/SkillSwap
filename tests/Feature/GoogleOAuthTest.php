<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleOAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function mockSocialiteUser(array $overrides = []): SocialiteUser
    {
        $defaults = [
            'id' => 'google-123456',
            'name' => 'Test Google User',
            'email' => 'google@example.com',
        ];

        $data = array_merge($defaults, $overrides);

        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn($data['id']);
        $socialiteUser->shouldReceive('getName')->andReturn($data['name']);
        $socialiteUser->shouldReceive('getEmail')->andReturn($data['email']);
        $socialiteUser->shouldReceive('getAvatar')->andReturn(null);

        return $socialiteUser;
    }

    public function test_redirect_to_google(): void
    {
        $response = $this->get(route('auth.google.redirect'));

        $response->assertRedirect();
        $this->assertStringContainsString('accounts.google.com', $response->headers->get('Location') ?? '');
    }

    public function test_google_callback_creates_new_learner(): void
    {
        $socialiteUser = $this->mockSocialiteUser();

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn(Mockery::mock()->shouldReceive('user')->andReturn($socialiteUser)->getMock());

        $response = $this->get(route('auth.google.callback'));

        $this->assertAuthenticated();

        $user = User::where('email', 'google@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('learner', $user->role);
        $this->assertEquals('google-123456', $user->google_id);
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_google_callback_links_existing_email_user(): void
    {
        $existingUser = User::factory()->create([
            'email' => 'existing@example.com',
            'role' => 'tutor',
            'google_id' => null,
        ]);

        $socialiteUser = $this->mockSocialiteUser([
            'email' => 'existing@example.com',
            'id' => 'google-789',
        ]);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn(Mockery::mock()->shouldReceive('user')->andReturn($socialiteUser)->getMock());

        $response = $this->get(route('auth.google.callback'));

        $this->assertAuthenticatedAs($existingUser);
        $this->assertEquals('google-789', $existingUser->fresh()->google_id);
        $this->assertEquals('tutor', $existingUser->fresh()->role); // Role preserved
    }

    public function test_google_callback_logs_in_existing_google_user(): void
    {
        $existingUser = User::factory()->create([
            'email' => 'returning@example.com',
            'role' => 'learner',
            'google_id' => 'google-existing-123',
        ]);

        $socialiteUser = $this->mockSocialiteUser([
            'email' => 'returning@example.com',
            'id' => 'google-existing-123',
        ]);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn(Mockery::mock()->shouldReceive('user')->andReturn($socialiteUser)->getMock());

        $response = $this->get(route('auth.google.callback'));

        $this->assertAuthenticatedAs($existingUser);
        // No new user should be created
        $this->assertEquals(1, User::where('email', 'returning@example.com')->count());
    }

    public function test_google_callback_handles_failure_gracefully(): void
    {
        Socialite::shouldReceive('driver')
            ->with('google')
            ->andThrow(new \Exception('OAuth failed'));

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('oauth-error');
        $this->assertGuest();
    }

    public function test_google_redirect_requires_guest(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('auth.google.redirect'));

        // Should redirect away since user is authenticated
        $response->assertRedirect();
    }
}
