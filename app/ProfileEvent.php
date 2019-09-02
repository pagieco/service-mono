<?php

namespace App;

use App\Concerns;
use Illuminate\Http\Request;
use App\Enums\ProfileEventType;
use App\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileEvent extends Model
{
    use Concerns\HasUUID;
    use Concerns\BelongsToDomain;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profile_events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_type',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    /**
     * The profile this event belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    /**
     * Record the applied tag event.
     *
     * @param  \App\Profile $profile
     * @param  string $tag
     * @return \App\ProfileEvent
     */
    public static function recordAppliedTagEvent(Profile $profile, string $tag): ProfileEvent
    {
        return $profile->events()->create([
            'event_type' => ProfileEventType::AppliedTag,
            'data' => [
                'tag' => $tag,
            ],
        ]);
    }

    public static function recordBouncedEvent(Profile $profile, Email $email): ProfileEvent
    {

    }

    public static function recordConfirmedFormSubmissionEvent(Profile $profile): ProfileEvent
    {

    }

    public static function recordIssuedSpamComplaintEvent(Profile $profile, Email $email): ProfileEvent
    {

    }

    public static function recordMarkedAsUndeliverableEvent(Profile $profile, Email $email): ProfileEvent
    {

    }

    public static function recordReceivedEmailEvent(Profile $profile, Email $email): ProfileEvent
    {

    }

    /**
     * Record the removed tag event.
     *
     * @param  \App\Profile $profile
     * @param  string $tag
     * @return \App\ProfileEvent
     */
    public static function recordRemovedTagEvent(Profile $profile, string $tag): ProfileEvent
    {
        return $profile->events()->create([
            'event_type' => ProfileEventType::RemovedTag,
            'data' => [
                'tag' => $tag,
            ],
        ]);
    }

    /**
     * Record the submitted form event.
     *
     * @param  \App\Profile $profile
     * @param  \App\Form $form
     * @param  array $formData
     * @return \App\ProfileEvent
     */
    public static function recordSubmittedFormEvent(Profile $profile, Form $form, array $formData): ProfileEvent
    {
        return $profile->events()->create([
            'event_type' => ProfileEventType::SubmittedForm,
            'data' => [
                'form_id' => $form->id,
                'form_data' => $formData,
            ],
        ]);
    }

    /**
     * Record the updated email address event.
     *
     * @param  \App\Profile $profile
     * @param  string $oldEmail
     * @param  string $newEmail
     * @return \App\ProfileEvent
     */
    public static function recordUpdatedEmailAddressEvent(Profile $profile, string $oldEmail, string $newEmail): ProfileEvent
    {
        return $profile->events()->create([
            'event_type' => ProfileEventType::UpdatedEmailAddress,
            'data' => [
                'old_email' => $oldEmail,
                'new_email' => $newEmail,
            ],
        ]);
    }

    /**
     * Record the visited page event.
     *
     * @param  \App\Profile $profile
     * @param  \Illuminate\Http\Request $request
     * @return \App\ProfileEvent
     */
    public static function recordVisitedPageEvent(Profile $profile, Request $request): ProfileEvent
    {
        return $profile->events()->create([
            'event_type' => ProfileEventType::VisitedPage,
            'data' => [
                'url' => $request->url(),
            ],
        ]);
    }
}
