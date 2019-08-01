<?php

namespace App\Concerns;

use App\Team;
use App\Scopes\TeamScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTeam
{
    /**
     * Get the team this model belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, $this->getTeamKeyColumn());
    }

    /**
     * Get te name of the "team key" column.
     *
     * @return string
     */
    protected function getTeamKeyColumn(): string
    {
        return 'team_id';
    }

    /**
     * Boot the `belongsToTeam` trait.
     *
     * @return void
     */
    protected static function bootBelongsToTeam(): void
    {
        static::addGlobalScope(new TeamScope);
    }
}
