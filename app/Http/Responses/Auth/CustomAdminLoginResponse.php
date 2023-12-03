<?php

namespace App\Http\Responses\Auth;

use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class CustomAdminLoginResponse implements LoginResponseContract
{
  public function toResponse($request)
  {
    return $request->wantsJson()
      ? new JsonResponse([], 204)
      : redirect()->route('dashboard'); // Customize the redirect for admin login
  }
}
