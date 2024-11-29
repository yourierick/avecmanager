<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAccountType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,string $type_account): Response
    {
        if (!Auth::check()) {
            return redirect("login");
        }

        $user = Auth::user();
        if ($user->droits != $type_account) {
            abort(403, "Unreachable");
        }
        return $next($request);
    }
}
