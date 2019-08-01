<?php

namespace App\Concerns;

use App\Team;
use App\Events\TeamJoined;
use App\Events\TeamSwitched;
use Illuminate\Support\Collection;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait InteractsWithTeams
{
    /**
     * Get the teamas this user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)->orderBy('name', 'asc');
    }

    /**
     * accessor for the currentTeam method.
     *
     * @return \App\Team|null
     */
    public function getCurrentTeamAttribute(): ?Team
    {
        return $this->currentTeam();
    }

    /**
     * Get the team the user is currently viewing.
     *
     * @return \App\Team|null
     */
    public function currentTeam($debug = false): ?Team
    {
        if (is_null($this->current_team_id)) {
            abort_if($this->teams->isEmpty(), 404);

            $this->switchToTeam($this->teams->first());

            return $this->currentTeam();
        }

        $currentTeam = $this->teams->find($this->current_team_id);

        return $currentTeam ?: $this->refreshCurrentTeam();
    }

    /**
     * Join the user to the given team.
     *
     * @param  \App\Team $team
     * @return void
     */
    public function joinTeam(Team $team): void
    {
        $this->teams()->attach($team);

        $this->refresh();

        $this->currentTeam();

        event(new TeamJoined($this, $team));
    }

    /**
     * Switch the current team for the user.
     *
     * @param  \App\Team $team
     * @return void
     * @throws \Throwable
     */
    public function switchToTeam(Team $team): void
    {
        throw_if(
            ! $this->teams->map->id->contains($team->id),
            new AuthorizationException('User hasn\'t joined the team or the team doesn\'t exist.')
        );

        $this->current_team_id = $team->id;
        $this->save();

        event(new TeamSwitched($this, $team));
    }

    /**
     * @return \App\Team|null
     */
    public function refreshCurrentTeam(): ?Team
    {
        $this->current_team_id = null;

        $this->save();

        return $this->currentTeam();
    }

    /**
     * Get the user's team mates.
     *
     * @param  bool $withSelf
     * @return \Illuminate\Support\Collection
     */
    public function teamMates(bool $withSelf = false): ?Collection
    {
        $query = $this->currentTeam()->members();

        if (! $withSelf) {
            $query->where('id', '!=', $this->id);
        }

        return $query->get();
    }

    /**
     * Create the users personal team. This acts as the first the that's created after the user joins.
     *
     * @return void
     */
    public function createPersonalUserTeam(): void
    {
        $team = Team::create(['name' => 'Personal']);

        $this->joinTeam($team);
    }
}
