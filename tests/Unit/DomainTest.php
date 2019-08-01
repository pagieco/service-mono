<?php

namespace Tests\Unit;

use App\Domain;
use App\Environment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DomainTest extends ModelTestCase
{
    /**
     * The model instance to test.
     *
     * @var string
     */
    protected $model = Domain::class;

    /** @test */
    public function it_belongs_to_a_team()
    {
        $this->assertInstanceOf(BelongsTo::class, app($this->model)->team());
    }

    /** @test */
    public function it_belongs_to_an_environment()
    {
        $this->assertInstanceOf(BelongsTo::class, app($this->model)->environment());
    }

    /** @test */
    public function it_can_attach_an_environment()
    {
        $this->login();

        $environment = $this->team->environments()->create(
            factory(Environment::class)->raw()
        );

        $domain = factory(Domain::class)->make();
        $domain->team()->associate($this->team);
        $domain->environment()->associate($environment);
        $domain->save();

        $this->assertNotNull($domain->environment);
    }

    /** @test */
    public function it_can_create_a_vanity_domain_name()
    {
        $domainName = Domain::createVanityDomainName();

        $this->assertCount(3, explode('.', $domainName));
    }

    /** @test */
    public function the_vanity_domain_name_includes_the_apps_domain()
    {
        $domainName = Domain::createVanityDomainName();

        $this->assertContains(config('app.domain'), $domainName);
    }
}
