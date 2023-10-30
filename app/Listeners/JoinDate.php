<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;

class JoinDate
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
  public function handle(Registered $event)
  {
    $event->user->update([
      'JoinDate' => now(),
    ]);
  }
}
