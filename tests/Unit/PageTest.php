<?php

namespace Tests\Unit;

use App\Page;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageTest extends ModelTestCase
{
    /**
     * The model instance to test.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model = Page::class;

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
}
