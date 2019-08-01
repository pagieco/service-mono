<?php

namespace Tests\Unit;

use App\Form;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormTest extends ModelTestCase
{
    /**
     * The model instance to test.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model = Form::class;

    /** @test */
    public function it_belongs_to_a_team()
    {
        $this->assertInstanceOf(BelongsTo::class, app($this->model)->team());
    }

    /** @test */
    public function it_belongs_to_a_domain()
    {
        $this->assertInstanceOf(BelongsTo::class, app($this->model)->domain());
    }

    /** @test */
    public function it_has_many_fields()
    {
        $this->assertInstanceOf(HasMany::class, app($this->model)->fields());
    }

    /** @test */
    public function it_has_many_submissions()
    {
        $this->assertInstanceOf(HasMany::class, app($this->model)->submissions());
    }
}
