<?php

namespace App;

use App\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutomationNode extends Model
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
    protected $table = 'automation_nodes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_trigger', 'trigger_type', 'trigger_data',
        'node_type', 'node_data',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_trigger' => 'bool',
        'trigger_data' => 'array',
        'node_data' => 'array',
    ];

    /**
     * The automation this step belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function automation(): BelongsTo
    {
        return $this->belongsTo(Automation::class);
    }
}
