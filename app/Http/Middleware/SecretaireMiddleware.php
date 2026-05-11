<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecretaireMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->role === 'secretaire') {
            return $next($request);
        }

        abort(403, 'Accès réservé aux secrétaires.');
    }
}
