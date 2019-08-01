<?php

namespace App;

use App\Concerns;
use Illuminate\Support\Str;
use App\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Domain extends Model
{
    use Concerns\HasUUID;
    use Concerns\BelongsToTeam;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'domains';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'domain_name',
    ];

    /**
     * Get the assets that belong to this domain.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * Get the environment this domain is attached to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function environment(): BelongsTo
    {
        return $this->belongsTo(Environment::class);
    }

    /**
     * Get the forms that belong to this domain.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function forms(): HasMany
    {
        return $this->hasMany(Form::class)->orderBy('name');
    }

    /**
     * Get the pages that belong to this domain.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class)->orderBy('name');
    }

    /**
     * Create the vanity domain name for this domain.
     *
     * @return string
     */
    public static function createVanityDomainName(): string
    {
        $random = strtolower(Str::random(30));

        return sprintf('%s.%s', $random, config('app.domain'));
    }
}
