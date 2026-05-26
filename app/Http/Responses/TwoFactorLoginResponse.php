<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = auth()->user();
        $redirectUrl = $user ? $user->dashboardUrl() : '/';

        return $request->wantsJson()
            ? response()->json(['two_factor' => true])
            : redirect()->intended($redirectUrl);
    }
}
