<?php

namespace App\Providers;

use App\Http\Responses\Auth\CustomAdminLoginResponse;
use App\Http\Responses\Auth\CustomMemberLoginResponse;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class FortifyServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {

    Fortify::ignoreRoutes();
    $this->app->singleton(LoginResponseContract::class, function () {
      if (request()->is('admin/login')) {
        return new CustomAdminLoginResponse();
      } elseif (request()->is('auth/login')) {
        return new CustomMemberLoginResponse();
      }

      return new LoginResponse($this->app->make('redirect'));
    });
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Fortify::createUsersUsing(CreateNewUser::class);
    Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
    Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
    Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

    RateLimiter::for('login', function (Request $request) {
      $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

      return Limit::perMinute(5)->by($throttleKey);
    });

    RateLimiter::for('two-factor', function (Request $request) {
      return Limit::perMinute(5)->by($request->session()->get('login.id'));
    });
  }
}
