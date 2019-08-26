<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\HasMany;
use Jenssegers\Mongodb\Relations\EmbedsMany;

class Collection extends Model
{
    use Concerns\Paginatable;
    use Concerns\BelongsToTeam;
    use Concerns\BelongsToDomain;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * The collection associated with the model.
     *
     * @var string
     */
    protected $collection = 'collections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The entries that belong to this collection.
     *
     * @return \Jenssegers\Mongodb\Relations\HasMany
     */
    public function entries(): HasMany
    {
        return $this->hasMany(CollectionEntry::class);
    }

    /**
     * The fields that belong to this collection.
     *
     * @return \Jenssegers\Mongodb\Relations\EmbedsMany
     */
    public function fields(): EmbedsMany
    {
        return $this->embedsMany(CollectionField::class);
    }
}
