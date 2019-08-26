<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\CollectionField;
use Tests\RefreshCollections;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionFieldTest extends TestCase
{
    use RefreshCollections;

    protected $model = CollectionField::class;

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
