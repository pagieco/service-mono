<?php

namespace App;

use App\Database\Eloquent\Model;

class Font extends Model
{
    use Concerns\HasUUID;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fonts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'origin', 'family', 'variants', 'subsets',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'variants' => 'array',
        'subsets' => 'array',
    ];
}
