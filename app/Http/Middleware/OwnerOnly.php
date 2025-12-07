<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OwnerOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->hasRole('owner')) {
            // If an authenticated cashier tries to access owner pages, redirect to POS
            if ($request->user() && $request->user()->hasRole('kasir')) {
                return redirect()->route('kasir.pos');
            }

            abort(403, 'Hanya owner yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
