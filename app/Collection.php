<?php

namespace App;

use App\Concerns;
use Jenssegers\Mongodb\Eloquent\Model;

class Collection extends Model
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
    protected $collection = 'databases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
