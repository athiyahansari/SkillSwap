<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Features;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = Auth::user();

        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        // If email verification is enabled, redirect to verification notice
        if (Features::enabled(Features::emailVerification())) {
            return redirect()->route('verification.notice');
        }

        $redirectUrl = $user ? $user->dashboardUrl() : '/';

        return redirect()->intended($redirectUrl);
    }
}
