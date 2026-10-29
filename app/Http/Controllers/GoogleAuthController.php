<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        if (! config('services.google.client_id') || ! config('services.google.client_secret')) {
            return to_route('login')->with('error', 'Google sign-in is not configured. Please use email sign-in.');
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        try {
            // Socialite verifies the OAuth state against the initiating session.
            $google = Socialite::driver('google')->user();
        } catch (\Throwable $exception) {
            report($exception);

            return to_route('login')->with('error', 'Google sign-in could not be completed. Please try again.');
        }

        if (! $google->getEmail() || ! ($google->user['email_verified'] ?? false)) {
            return to_route('login')->with('error', 'A verified Google email address is required.');
        }

        $user = User::where('google_id', $google->getId())->first();

        if (! $user) {
            // Existing accounts must authenticate before linking another identity.
            if (User::where('email', strtolower($google->getEmail()))->exists()) {
                return to_route('login')->with('error', 'This email is already registered. Please sign in with your password.')
                    ->with('form_type', 'login');
            }

            $user = User::create([
                'name' => $google->getName() ?: $google->getNickname() ?: 'Traveler',
                'email' => strtolower($google->getEmail()),
                'google_id' => $google->getId(),
                'password' => Str::random(64),
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user);
        $request->session()->forget('pending_registration');
        $request->session()->regenerate();

        return $user->first_name ? redirect()->intended(route('home')) : to_route('profile.edit');
    }
}
