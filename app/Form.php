<?php

namespace App;

use App\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Form extends Model
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
    protected $table = 'forms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the fields that belong to this form.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fields(): HasMany
    {
        return $this->hasMany(FormField::class)->orderBy('order');
    }

    /**
     * Get the submissions that belong to this form.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class)->orderBy('order');
    }

    /**
     * The users that are subscribed to the notifications of this form.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'form_user');
    }

    /**
     * Subscribe from form notifications.
     *
     * @param  \App\User $user
     * @return void
     */
    public function subscribeToNotifications(User $user): void
    {
        $this->subscribers()->attach($user);
    }

    /**
     * Unsubscribe from form notifications.
     *
     * @param  \App\User $user
     * @return void
     */
    public function unsubscribeFromNotifications(User $user): void
    {
        $this->subscribers()->detach($user);
    }
}
