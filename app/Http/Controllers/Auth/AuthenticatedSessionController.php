<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        if ($request->user()->statut === 1) {
            $request->session()->regenerate();
            if ($request->user()->droits === "administrateur"){
                return redirect()->intended(route('dashboard_admin', absolute: false));
            }elseif ($request->user()->droits === "utilisateur") {
                if ($request->user()->projet_id) {
                    return redirect()->intended(route('user_dashboard', absolute: false));
                }else {
                    Auth::guard('web')->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return to_route('login')->with('error_message', "cet utilisateur n'est affecté à aucun projet")->onlyInput('email');
                }
            }else {
                return redirect()->intended(route('guest_dashboard', absolute: false));
            }
        }else {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return to_route('login')->with('error_message', "Ce compte est désactivé")->onlyInput('email');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
