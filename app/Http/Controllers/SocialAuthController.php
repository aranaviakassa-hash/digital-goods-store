<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthController extends Controller
{
    public function redirectToGoogle(): RedirectResponse
    {
        abort_unless(
            filled(config('services.google.client_id')) &&
            filled(config('services.google.client_secret')),
            503,
            'Google login is not configured yet.'
        );

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        abort_unless(
            filled(config('services.google.client_id')) &&
            filled(config('services.google.client_secret')),
            503,
            'Google login is not configured yet.'
        );

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('login')
                ->withErrors([
                    'social' => 'Google sign-in could not be completed. Please try again.',
                ]);
        }

        $email = strtolower(
            trim((string) $googleUser->getEmail())
        );

        if ($email === '') {
            return redirect()
                ->route('login')
                ->withErrors([
                    'social' => 'Google did not provide an email address.',
                ]);
        }

        $rawUser = $googleUser->user ?? [];

        $emailVerified = filter_var(
            $rawUser['email_verified']
                ?? $rawUser['verified_email']
                ?? false,
            FILTER_VALIDATE_BOOL
        );

        if (! $emailVerified) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'social' => 'Your Google email address could not be verified.',
                ]);
        }

        $providerUserId = (string) $googleUser->getId();

        $user = DB::transaction(function () use (
            $googleUser,
            $email,
            $providerUserId
        ) {
            $socialAccount = SocialAccount::query()
                ->where('provider', 'google')
                ->where('provider_user_id', $providerUserId)
                ->lockForUpdate()
                ->first();

            if ($socialAccount) {
                return $socialAccount->user;
            }

            $user = User::query()
                ->where('email', $email)
                ->lockForUpdate()
                ->first();

            if (! $user) {
                $user = User::create([
                    'name' => $googleUser->getName()
                        ?: Str::before($email, '@'),

                    'email' => $email,

                    'password' => Str::random(64),
                ]);

                $user->forceFill([
                    'email_verified_at' => now(),
                ])->save();
            } elseif (! $user->hasVerifiedEmail()) {
                $user->forceFill([
                    'email_verified_at' => now(),
                ])->save();
            }

            SocialAccount::firstOrCreate(
                [
                    'provider' => 'google',
                    'provider_user_id' => $providerUserId,
                ],
                [
                    'user_id' => $user->id,
                    'provider_email' => $email,
                ]
            );

            return $user;
        });

        Auth::login($user, true);

        request()->session()->regenerate();

        return redirect()
            ->intended(route('account.index'));
    }
}