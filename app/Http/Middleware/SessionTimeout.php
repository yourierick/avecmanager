<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    protected $timeout = 1800; //30 minutes avant l'expiration de la session;

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $lastActivity = session('last_activity_time');
            if ($lastActivity && (time() - $lastActivity > $this->timeout)) {
                Auth::logout();
                session()->flush();

                return redirect('')->withErrors(["error_message"=>'session expirée.']);
            }
            session(['last_activity_time' => time()]);
        }
        return $next($request);
    }
}
