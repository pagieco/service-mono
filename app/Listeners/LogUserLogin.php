<?php

namespace App\Listeners;

use App\User;

class LogUserLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        User::find($event->userId)->update([
            'last_ip' => request()->ip(),
            'last_login_at' => now(),
        ]);
    }
}
