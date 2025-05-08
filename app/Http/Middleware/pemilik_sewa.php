<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class pemilik_sewa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->badge === "Banned") {
            return response()->view('authentication.infoBanned');
        }

        if (auth()->check() && auth()->user()->role === 'pemilik_sewa') {
            return $next($request);
        }

        if (Auth::user()) {
            return redirect()->back();
        } else {
            return redirect()->route('loginView');
        }
    }
}