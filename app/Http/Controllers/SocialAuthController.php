<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to Google's OAuth consent screen.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google after authentication.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Exception $e) {
            return redirect()->route('login')
                ->with('oauth-error', 'Something went wrong with Google sign-in. Please try again or use email/password.');
        }

        // 1. Check if a user with this Google ID already exists
        $existingUserByGoogleId = User::where('google_id', $googleUser->getId())->first();

        if ($existingUserByGoogleId) {
            Auth::login($existingUserByGoogleId, remember: true);
            return redirect()->intended($existingUserByGoogleId->dashboardUrl());
        }

        // 2. Check if a user with this email already exists (link Google account)
        $existingUserByEmail = User::where('email', $googleUser->getEmail())->first();

        if ($existingUserByEmail) {
            $existingUserByEmail->update(['google_id' => $googleUser->getId()]);
            Auth::login($existingUserByEmail, remember: true);
            return redirect()->intended($existingUserByEmail->dashboardUrl());
        }

        // 3. Create a new learner account
        $newUser = User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'password' => Hash::make(Str::random(32)),
            'role' => 'learner',
            'email_verified_at' => now(), // Google-verified email
        ]);

        Auth::login($newUser, remember: true);

        return redirect()->intended($newUser->dashboardUrl());
    }
}
