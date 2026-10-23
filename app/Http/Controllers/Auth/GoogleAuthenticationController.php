<?php

namespace App\Http\Controllers\Auth;

use App\Actions\CreateClientUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CompleteGoogleRegistrationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Events\TwoFactorAuthenticationChallenged;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthenticationController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $exception) {
            report($exception);

            return to_route('login')->with('googleError', __('No pudimos autenticarte con Google. Intentá nuevamente.'));
        }

        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();
        $name = $googleUser->getName();

        if (! is_string($googleId) || $googleId === '' || ! is_string($email) || $email === '') {
            return to_route('login')->with('googleError', __('Google no proporcionó los datos necesarios para iniciar sesión.'));
        }

        $user = User::where('google_id', $googleId)->first();

        if ($user) {
            return $this->authenticate($request, $user);
        }

        $emailVerified = $googleUser->user['email_verified'] ?? $googleUser->user['verified_email'] ?? false;

        if (! filter_var($emailVerified, FILTER_VALIDATE_BOOL)) {
            return to_route('login')->with('googleError', __('Google no confirmó el correo de esa cuenta.'));
        }

        $email = Str::lower($email);

        if (User::where('email', $email)->exists()) {
            return to_route('login')->with('googleError', __('Ya existe una cuenta con ese correo. Iniciá sesión y vinculá Google desde Seguridad.'));
        }

        $request->session()->put('google.registration', [
            'id' => $googleId,
            'name' => is_string($name) && $name !== '' ? $name : Str::before($email, '@'),
            'email' => $email,
            'issued_at' => now()->timestamp,
        ]);

        return to_route('google.registration.create');
    }

    public function create(Request $request): Response|RedirectResponse
    {
        $googleUser = $request->session()->get('google.registration');

        if (! is_array($googleUser) || ! is_int($googleUser['issued_at'] ?? null) || $googleUser['issued_at'] < now()->subMinutes(10)->timestamp) {
            $request->session()->forget('google.registration');

            return to_route('register');
        }

        return Inertia::render('auth/complete-google-registration', [
            'name' => $googleUser['name'],
            'email' => $googleUser['email'],
            'passwordRules' => Password::defaults()->toPasswordRulesString(),
        ]);
    }

    public function store(CompleteGoogleRegistrationRequest $request, CreateClientUser $createClientUser): RedirectResponse
    {
        $validated = $request->validated();
        try {
            $user = $createClientUser->create($validated, $validated['google_id']);
        } catch (UniqueConstraintViolationException) {
            $request->session()->forget('google.registration');

            return to_route('login')->with('googleError', __('La cuenta de Google o el correo ya fueron registrados. Intentá iniciar sesión.'));
        }

        $request->session()->forget('google.registration');
        event(new Registered($user));
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    private function authenticate(Request $request, User $user): RedirectResponse
    {
        if ($user->hasEnabledTwoFactorAuthentication()) {
            $request->session()->put([
                'login.id' => $user->getKey(),
                'login.remember' => false,
            ]);

            TwoFactorAuthenticationChallenged::dispatch($user);

            return to_route('two-factor.login');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
