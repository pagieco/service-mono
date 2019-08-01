<?php

namespace App;

use App\Concerns;
use App\Database\Eloquent\Model;

class Page extends Model
{
    use Concerns\HasUUID;
    use Concerns\BelongsToTeam;
    use Concerns\BelongsToDomain;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'dom',
    ];
}
