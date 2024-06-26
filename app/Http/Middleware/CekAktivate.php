<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekAktivate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    

     public function handle(Request $request, Closure $next, ...$activate): Response
     {
         if (!in_array($request->user()->activate,$activate)) {
             return redirect('/logout')->with('error', 'Akun belum diaktifkan');
         }
         return $next($request);
     }
}
