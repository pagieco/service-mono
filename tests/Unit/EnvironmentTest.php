<?php

namespace Tests\Unit;

use App\Environment;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnvironmentTest extends ModelTestCase
{
    /**
     * The model instance to test.
     *
     * @var string
     */
    protected $model = Environment::class;

    /** @test */
    public function it_belongs_to_a_team()
    {
        $this->assertInstanceOf(BelongsTo::class, app($this->model)->team());
    }

    /** @test */
    public function it_has_many_domains()
    {
        $this->assertInstanceOf(HasMany::class, app($this->model)->domains());
    }
}
