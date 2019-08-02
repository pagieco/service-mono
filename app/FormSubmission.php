<?php

namespace App;

use App\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormSubmission extends Model
{
    use Concerns\HasUUID;
    use Concerns\BelongsToTeam;
    use Concerns\BelongsToDomain;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'form_submissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'submission_data',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'submission_data' => 'array',
    ];

    /**
     * Get the form this submission is attached to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
