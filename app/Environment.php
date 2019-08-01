<?php

namespace App;

use App\Concerns;
use Illuminate\Support\Str;
use App\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Environment extends Model
{
    use Concerns\HasUUID;
    use Concerns\BelongsToTeam;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'environments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the domain this environment belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    /**
     * Get the slugified name attribyte.
     *
     * @return string
     */
    public function getSlugAttribute(): string
    {
        return Str::slug($this->getAttribute('name'));
    }
}
