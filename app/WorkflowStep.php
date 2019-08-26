<?php

namespace App;

use App\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowStep extends Model
{
    use Concerns\HasUUID;
    use Concerns\Uploadable;
    use Concerns\Paginatable;
    use Concerns\BelongsToTeam;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'workflows';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // ...
    ];

    /**
     * Get the workflow that belongs to this step.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    /**
     * Get the users that are assigned to this step.
     *
     * @return \Jenssegers\Mongodb\Relations\HasMany
     */
    public function assignees(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
