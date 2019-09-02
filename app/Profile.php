<?php

namespace App;

use App\Concerns;
use App\Database\Eloquent\Model;
use App\Enums\ProfileEventType;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class Profile extends Model
{
    use Concerns\HasUUID;
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

    /**
     * The events that belong to this profile.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(ProfileEvent::class);
    }

    public function recordEvent(string $eventType, array $data)
    {
        $this->events()->create([
            'event_type' => $eventType,
            'data' => $data,
        ]);
    }

    /**
     * Identify a profile by the given request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Domain $domain
     * @return \App\Profile|null
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function identify(Request $request, Domain $domain): ?Profile
    {
        if ($request->has('profile_id')) {
            return static::identifyViaProfileId($request, $domain);
        }

        if ($request->has('email')) {
            return static::identifyViaEmail($request, $domain);
        }

        if ($request->hasCookie('profile_id')) {
            return static::identifyViaCookie($request, $domain);
        }

        return null;
    }

    /**
     * Identify the profile by the given profile id.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Domain $domain
     * @return \App\Profile
     */
    protected static function identifyViaProfileId(Request $request, Domain $domain): ?Profile
    {
        return $domain->profiles()
            ->where('id', $request->get('profile_id'))
            ->first();
    }

    /**
     * Identify the profile by the given profile id.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Domain $domain
     * @return \App\Profile
     */
    protected static function identifyViaEmail(Request $request, Domain $domain): ?Profile
    {
        return $domain->profiles()
            ->where('email', $request->get('email'))
            ->first();
    }

    /**
     * Identify the profile by the profile id found in the cookies.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Domain $domain
     * @return \App\Profile|null
     */
    protected static function identifyViaCookie(Request $request, Domain $domain): ?Profile
    {
        return $domain->profiles()
            ->where('id', $request->cookie('profile_id'))
            ->first();
    }
}
