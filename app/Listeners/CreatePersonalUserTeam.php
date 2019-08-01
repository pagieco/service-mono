<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;

class CreatePersonalUserTeam
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $event->user->createPersonalUserTeam();
    }
}
