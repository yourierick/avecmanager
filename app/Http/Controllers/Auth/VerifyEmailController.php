<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            if ($request->user()->droits === "administrateur") {
                return redirect()->intended(route('dashboard_admin', absolute: false).'?verified=1');
            }elseif ($request->user()->droits === "utilisateur") {
                return redirect()->intended(route('user_dashboard', absolute: false).'?verified=1');
            }else {
                return redirect()->intended(route('guest_dashboard', absolute: false).'?verified=1');
            }
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        if ($request->user()->droits === "administrateur") {
            return redirect()->intended(route('dashboard_admin', absolute: false).'?verified=1');
        }elseif ($request->user()->droits === "utilisateur") {
            return redirect()->intended(route('user_dashboard', absolute: false).'?verified=1');
        }else {
            return redirect()->intended(route('guest_dashboard', absolute: false).'?verified=1');
        }
    }
}
