<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class penyewa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->badge === "Banned") {
            Auth::logout();
            Session::flush();
            Session::regenerate(true);
            return redirect()->route('loginView')->with('error', 'Anda Telah Di Banned!');
        }

        if (auth()->check() && auth()->user()->role === 'penyewa') {
            return $next($request);
        }

        if (Auth::user()) {
            return redirect()->back();
        } else {
            return redirect()->route('loginView');
        }
    }
}