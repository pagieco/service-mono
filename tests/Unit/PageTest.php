<?php

namespace Tests\Unit;

use App\Page;
use App\Concerns\Paginatable;
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
    public function it_correctly_implements_the_paginatable_concern()
    {
        $this->assertTrue(in_array(Paginatable::class, class_uses($this->model)));
    }

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
