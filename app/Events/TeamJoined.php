<?php

namespace App\Events;

use App\Team;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class TeamJoined
{
    use Dispatchable, SerializesModels;

    /**
     * The user instance.
     *
     * @var \App\User
     */
    public $user;

    /**
     * The team instance.
     *
     * @var \App\Team
     */
    public $team;

    /**
     * Create a new event instance.
     *
     * @param  \App\User $user
     * @param  \App\Team $team
     */
    public function __construct(User $user, Team $team)
    {
        $this->user = $user;
        $this->team = $team;
    }
}
