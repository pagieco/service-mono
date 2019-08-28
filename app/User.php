<?php

namespace App;

use Illuminate\Http\UploadedFile;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasApiTokens;
    use Concerns\HasUUID;
    use Concerns\Paginatable;
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
        'last_ip', 'last_login_at', 'timezone',
    ];

    /**
     * The form subscriptions attached to this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function formSubscriptions(): BelongsToMany
    {
        return $this->belongsToMany(Form::class, 'form_user');
    }

    /**
     * Upload the user's profile picture.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @return string
     */
    public function uploadProfilePicture(UploadedFile $file): string
    {
        $filename = sprintf('%s.%s', $this->id, $file->getClientOriginalExtension());

        $path = $file->storeAs(null, $filename, [
            'disk' => 'avatars',
        ]);

        return $path;
    }
}
