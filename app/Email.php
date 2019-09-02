<?php

namespace App;

use App\Concerns;
use App\Database\Eloquent\Model;

class Email extends Model
{
    use Concerns\HasUUID;
    use Concerns\Paginatable;
    use Concerns\BelongsToTeam;
    use Concerns\BelongsToDomain;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'emails';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'dom',
    ];
}
