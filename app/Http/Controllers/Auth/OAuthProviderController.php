<?php

namespace App\Http\Controllers\Auth;

use App\Enums\OAuthProviderEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class OAuthProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(OAuthProviderEnum $provider): \Illuminate\Http\RedirectResponse
    {
        return Socialite::driver($provider->value)->redirect();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OAuthProviderEnum $provider): \Illuminate\Http\RedirectResponse
    {
        $socialite = Socialite::driver($provider->value)->user();

        $user = User::firstOrCreate([
            'email' => $socialite->getEmail(),
        ], [
            'full_name' => $socialite->getName(),
            'avatar' => $socialite->getAvatar(),
            'is_active' => false,
        ]);

        $user->providers()->updateOrCreate([
            'provider' => $provider,
            'provider_id' => $socialite->getId(),
        ]);

        Auth::login($user);

        return redirect(env('FRONTEND_URL'));
    }
}
