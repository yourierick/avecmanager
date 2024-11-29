<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CustomRedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->statut == 1) {
                if ($user->droits === "administrateur"){
                    return redirect()->intended(route('dashboard_admin', absolute: false));
                }elseif ($user->droits === "utilisateur") {
                    if ($request->user()->projet_id) {
                        return redirect()->intended(route('user_dashboard', absolute: false));
                    }else {
                        Auth::guard('web')->logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();
                        return to_route('login')->with('error_message', "cet utilisateur n'est affecté à aucun projet");
                    }
                }else {
                    return redirect()->intended(route('guest_dashboard', absolute: false));
                }
            }else {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return to_route('login')->with('error_message', "ce compte est désactivé");
            }
        }
        return $next($request);
    }
}
