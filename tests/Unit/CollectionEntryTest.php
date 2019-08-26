<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\CollectionEntry;
use Tests\RefreshCollections;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jenssegers\Mongodb\Relations\BelongsTo as MongoDBBelongsTo;

class CollectionEntryTest extends TestCase
{
    use RefreshCollections;

    protected $model = CollectionEntry::class;

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
    public function it_belongs_to_a_collection()
    {
        $this->assertInstanceOf(MongoDBBelongsTo::class, app($this->model)->collection());
    }
}
