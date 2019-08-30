<?php

namespace App;

use Illuminate\Support\Str;
use App\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Domain extends Model
{
    use Concerns\HasUUID;
    use Concerns\BelongsToTeam;

    /**
     * The request header key where the domain's api token can be found.
     *
     * @var string
     */
    public const API_TOKEN_HEADER_KEY = 'x-domain-token';

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
        'domain_name', 'css_rules', 'css_file', 'api_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'css_rules' => 'array',
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
     * Get the profiles that belong to this domain.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class)->orderBy('created_at');
    }

    /**
     * Generate a unique api-token for this domain.
     *
     * @return string
     */
    public function generateApiToken(): string
    {
        return strtolower(Str::random(64));
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
