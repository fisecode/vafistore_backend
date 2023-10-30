<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class LastLoggedIn
{
  /**
   * Create the event listener.
   */
  public function __construct()
  {
    //
  }

  /**
   * Handle the event.
   */
  public function handle(Login $event)
  {
    $event->user->update([
      'last_login' => date(now()),
    ]);
  }
}
