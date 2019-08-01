<?php

namespace Tests\Unit;

use App\FormSubmission;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormSubmissionTest extends ModelTestCase
{
    /**
     * The model instance to test.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model = FormSubmission::class;

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
    public function it_belongs_to_a_form()
    {
        $this->assertInstanceOf(BelongsTo::class, app($this->model)->form());
    }
}
