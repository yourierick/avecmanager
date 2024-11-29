<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            if ($request->user()->droits === "administrateur") {
                return redirect()->intended(route('dashboard_admin', absolute: false));
            }elseif ($request->user()->droits === "utilisateur") {
                return redirect()->intended(route('user_dashboard', absolute: false));
            }else {
                return redirect()->intended(route('guest_dashboard', absolute: false));
            }

        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
