<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Auth;

class CustomRoleMiddleware extends RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $role, $guard = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->hasRole($role)) {
            return redirect()->route('dashboard');
        }

        return parent::handle($request, $next, $role, $guard);
    }
}
