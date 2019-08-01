<?php

namespace App;

use App\Concerns;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasApiTokens;
    use Concerns\HasUUID;
    use Concerns\BelongsToTeam;
    use Concerns\HasPermissions;
    use Concerns\InteractsWithTeams;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'name', 'password', 'picture',
        'last_ip', 'last_login_at',
    ];
}
