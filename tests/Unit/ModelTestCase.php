<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Concerns\HasUUID;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class ModelTestCase extends TestCase
{
    use RefreshDatabase;

    /**
     * The model instance to test.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /** @test */
    public function has_a_primary_uuid_column()
    {
        $instance = new $this->model;

        $this->assertContains(HasUUID::class, class_uses($instance));

        $this->assertFalse($instance->getIncrementing());
    }
}
