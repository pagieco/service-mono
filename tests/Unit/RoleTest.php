<?php

namespace Tests\Unit;

use App\Role;
use App\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RoleTest extends ModelTestCase
{
    /**
     * The model instance to test.
     *
     * @var string
     */
    protected $model = Role::class;

    /** @test */
    public function it_belongs_to_a_team()
    {
        $this->assertInstanceOf(BelongsTo::class, app($this->model)->team());
    }

    /** @test */
    public function it_belongs_to_many_permissions()
    {
        $this->assertInstanceOf(BelongsToMany::class, app($this->model)->permissions());
    }

    /** @test */
    public function it_cannot_assign_a_non_existing_permission_to_the_role()
    {
        $role = factory(Role::class)->create();

        $role->assignPermission('non-existing-permission');

        $this->assertEmpty($role->permissions);
    }

    /** @test */
    public function it_can_assign_a_permission_to_the_role()
    {
        $role = factory(Role::class)->create();

        $permission = factory(Permission::class)->create();

        $role->assignPermission($permission->slug);

        $this->assertNotEmpty($role->permissions);
    }

    /** @test */
    public function it_cannot_revoke_a_non_existing_permission_from_the_role()
    {
        $role = factory(Role::class)->create();

        $permission = factory(Permission::class)->create();

        $role->assignPermission($permission->slug);

        $role->revokePermission('non-existing-permission');

        $this->assertCount(1, $role->permissions);
    }

    /** @test */
    public function it_can_revoke_a_permission_from_the_role()
    {
        $role = factory(Role::class)->create();

        $permission = factory(Permission::class)->create();

        $role->assignPermission($permission->slug);
        $role->revokePermission($permission->slug);

        $this->assertCount(0, $role->permissions);
    }
}
