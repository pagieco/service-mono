<?php

namespace Tests\Unit;

use App\Collection;
use Tests\TestCase;
use App\Concerns\Paginatable;
use Tests\RefreshCollections;
use Jenssegers\Mongodb\Relations\HasMany;
use Jenssegers\Mongodb\Relations\EmbedsMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionTest extends TestCase
{
    use RefreshCollections;

    protected $model = Collection::class;

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

    /** @test */
    public function it_has_many_entries()
    {
        $this->assertInstanceOf(HasMany::class, app($this->model)->entries());
    }

    /** @test */
    public function it_embeds_many_fields()
    {
        $this->assertInstanceOf(EmbedsMany::class, app($this->model)->fields());
    }
}
