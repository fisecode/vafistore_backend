<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next, string ...$guards): Response
  {
    $guards = empty($guards) ? [null] : $guards;

    foreach ($guards as $guard) {
      if (Auth::guard($guard)->check()) {
        $user = Auth::user();

        // Check if the user exists and then check roles
        if ($user && $user->hasRole('super admin')) {

          return redirect(RouteServiceProvider::MEMBER);
        } elseif ($user && ($user->hasRole('admin') || $user->hasRole('basic') || $user->hasRole('reseller'))) {
          return redirect(RouteServiceProvider::MEMBER);
        }
      }
    }
    return $next($request);
  }
}
