<?php

namespace App\Http\Middleware\Role;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (env('APP_ENV') != 'testing' && auth()->user()->role != Role::Admin->value) {
            abort(403);
        }

        return $next($request);
    }
}
