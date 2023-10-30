<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RedirectIfNotAllowedRole
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next, ...$allowedRoles): Response
  {
    if (!$request->user() || !$request->user()->hasAnyRole($allowedRoles)) {
      Auth::guard('web')->logout();
      Session::invalidate();
      return redirect()
        ->route('login')
        ->with('error', 'You do not have permission to access this page.');
    }

    return $next($request);
  }
}
