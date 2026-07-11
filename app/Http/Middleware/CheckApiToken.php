<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('api_token')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // We can optionally attach the user from session to the request if needed, or share to views
        if (session()->has('user')) {
            view()->share('loggedUser', session('user'));
        }

        return $next($request);
    }
}
