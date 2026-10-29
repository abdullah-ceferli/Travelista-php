<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Mail\VerificationCode;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View
    {
        return view('auth_page', ['form_type' => session('form_type', 'signup')]);
    }

    public function store(AuthRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($data['form_type'] === 'login') {
            if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
                throw ValidationException::withMessages(['email' => 'Invalid email or password.']);
            }

            $request->session()->regenerate();

            return redirect()->intended(route('home'));
        }

        $code = (string) random_int(100000, 999999);

        try {
            Mail::to($data['email'])->send(new VerificationCode($code));
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error', 'Unable to send verification email. Please try again later.')
                ->withInput($request->except('password'));
        }

        $request->session()->regenerate();
        $request->session()->put('pending_registration', [
            'name' => $data['username'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(10)->timestamp,
            'attempts' => 0,
        ]);

        return to_route('verification.show');
    }

    public function showVerification(Request $request): View|RedirectResponse
    {
        if (! $this->hasPendingRegistration($request)) {
            return to_route('login')->with('error', 'Please sign up to receive a verification code.');
        }

        return view('verify');
    }

    public function verify(Request $request): RedirectResponse
    {
        if (! $this->hasPendingRegistration($request)) {
            return to_route('login')->with('error', 'Your verification code has expired. Please sign up again.');
        }

        $request->validate(['code' => ['required', 'digits:6']]);
        $pending = $request->session()->get('pending_registration');
        $request->session()->put('pending_registration.attempts', $pending['attempts'] + 1);

        if (! Hash::check($request->input('code'), $pending['code_hash'])) {
            throw ValidationException::withMessages(['code' => 'Invalid verification code.']);
        }

        if (User::where('email', $pending['email'])->exists()) {
            $request->session()->forget('pending_registration');

            return to_route('login')->with('error', 'Email is already registered. Please log in.')->with('form_type', 'login');
        }

        $user = User::create([
            'name' => $pending['name'],
            'email' => $pending['email'],
            'phone' => $pending['phone'],
            'password' => $pending['password'],
            'email_verified_at' => now(),
        ]);

        $request->session()->forget('pending_registration');
        Auth::login($user);
        $request->session()->regenerate();

        return to_route('profile.edit');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login');
    }

    private function hasPendingRegistration(Request $request): bool
    {
        $pending = $request->session()->get('pending_registration');

        if (! $pending || ($pending['expires_at'] ?? 0) < now()->timestamp || ($pending['attempts'] ?? 5) >= 5) {
            $request->session()->forget('pending_registration');

            return false;
        }

        return true;
    }
}
