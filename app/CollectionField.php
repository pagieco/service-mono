<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class CollectionField extends Model
{
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
    protected $collection = 'collection_fields';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'name', 'slug', 'helptext', 'is_required',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_required' => 'boolean',
    ];
}
