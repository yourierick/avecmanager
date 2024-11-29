<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
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

        return view('auth.verify-email');
    }
}
