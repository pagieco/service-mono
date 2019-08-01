<?php

namespace Tests\Unit;

use App\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PermissionTest extends ModelTestCase
{
    /**
     * The model instance to test.
     *
     * @var string
     */
    protected $model = Permission::class;

    /** @test */
    public function it_belongs_to_many_roles()
    {
        $this->assertInstanceOf(BelongsToMany::class, app($this->model)->roles());
    }

    /** @test */
    public function it_can_scope_by_slug()
    {
        factory(Permission::class, 5)->create();

        $permission = factory(Permission::class)->create();

        $this->assertCount(1, Permission::bySlug($permission->slug)->get());
    }
}
