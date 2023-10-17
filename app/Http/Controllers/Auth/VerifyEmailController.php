<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): \Illuminate\Http\RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            if ($request->user()->role === User::Admin) {
                return redirect()->intended(
                    config('app.admin_frontend_url').RouteServiceProvider::HOME.'?verified=1'
                );
            }
            return redirect()->intended(
                config('app.frontend_url').RouteServiceProvider::HOME.'?verified=1'
            );
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        if ($request->user()->role === User::Admin) {
            return redirect()->intended(
                config('app.admin_frontend_url').RouteServiceProvider::HOME.'?verified=1'
            );
        }
        return redirect()->intended(
            config('app.frontend_url').RouteServiceProvider::HOME.'?verified=1'
        );
    }
}
