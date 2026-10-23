<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAccountLinkController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')
            ->redirectUrl(route('google.link.callback'))
            ->with(['login_hint' => request()->user()->email])
            ->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(route('google.link.callback'))
                ->user();
        } catch (Throwable $exception) {
            report($exception);
            Inertia::flash('toast', ['type' => 'error', 'message' => __('No pudimos vincular la cuenta de Google.')]);

            return to_route('security.edit');
        }

        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();
        $user = $request->user();
        $emailVerified = $googleUser->user['email_verified'] ?? $googleUser->user['verified_email'] ?? false;

        if (! is_string($googleId) || $googleId === '' || ! is_string($email) || ! filter_var($emailVerified, FILTER_VALIDATE_BOOL) || ! hash_equals(Str::lower($user->email), Str::lower($email))) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('La cuenta de Google debe usar el mismo correo que tu cuenta de VetZen.')]);

            return to_route('security.edit');
        }

        if (User::where('google_id', $googleId)->whereKeyNot($user->getKey())->exists()) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('Esa cuenta de Google ya está vinculada a otro usuario.')]);

            return to_route('security.edit');
        }

        try {
            $user->google_id = $googleId;
            $user->save();
        } catch (UniqueConstraintViolationException) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('Esa cuenta de Google ya está vinculada a otro usuario.')]);

            return to_route('security.edit');
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Cuenta de Google vinculada.')]);

        return to_route('security.edit');
    }
}
