<?php

namespace App;

use App\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormField extends Model
{
    use Concerns\HasUUID;
    use Concerns\BelongsToTeam;
    use Concerns\BelongsToDomain;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'form_fields';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'slug', 'display_name', 'validations',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'validation' => 'array',
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
