<?php

namespace App;

use App\Concerns;
use App\Database\Eloquent\Model;

class Profile extends Model
{
    use Concerns\HasUUID;
    use Concerns\BelongsToTeam;
    use Concerns\BelongsToDomain;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',            // The profile's email address
        'first_name',       // The profile's first name
        'last_name',        // The profile's last name
        'address_1',        // The profile's address
        'address_2',        // An additional field for the profile's address
        'city',             // The city, town, or village in which the profile resides
        'state',            // The region in which the profile resides
        'zip',              // The postal code in which the profile resides
        'country',          // The country in which the profile resides
        'phone',            // The profiles's primary phone number
        'timezone',         // The profie's timezone
        'tags',             // An array containing one or more tags
        'custom_fields',    // An object containing custom field data
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tags' => 'array',
        'custom_fields' => 'array',
    ];
}
