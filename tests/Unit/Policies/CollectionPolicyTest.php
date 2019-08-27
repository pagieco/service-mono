<?php

namespace Tets\Unit\Policies;

use App\Collection;
use Tests\TestCase;
use App\Policies\CollectionPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CollectionPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_list_the_collections()
    {
        $this->login();

        $this->assertFalse((new CollectionPolicy)->list($this->user));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_list_the_collections()
    {
        $this->login()->forceAccess($this->role, 'collection:list');

        $this->assertTrue((new CollectionPolicy)->list($this->user));
    }

    /** @test */
    public function it_return_false_when_the_user_has_no_permission_to_create_a_new_collection()
    {
        $this->login();

        $this->assertFalse((new CollectionPolicy)->create($this->user));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_create_a_new_collection()
    {
        $this->login()->forceAccess($this->role, 'collection:create');

        $this->assertTrue((new CollectionPolicy)->create($this->user));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_view_a_collection()
    {
        $this->login();

        $collection = factory(Collection::class)->create();

        $this->assertFalse((new CollectionPolicy)->view($this->user, $collection));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_permission_to_view_a_collection_but_the_collection_does_not_belong_to_the_users_team()
    {
        $this->login()->forceAccess($this->role, 'collection:view');

        $collection = factory(Collection::class)->create();

        $this->assertFalse((new CollectionPolicy)->view($this->user, $collection));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_view_a_collection()
    {
        $this->login()->forceAccess($this->role, 'collection:view');

        $collection = factory(Collection::class)->create([
            'team_id' => $this->team->id,
        ]);

        $this->assertTrue((new CollectionPolicy)->view($this->user, $collection));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_delete_a_collection()
    {
        $this->login();

        $collection = factory(Collection::class)->create();

        $this->assertFalse((new CollectionPolicy)->delete($this->user, $collection));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_permission_to_delete_a_collection_but_the_collection_does_not_belong_to_the_users_team()
    {
        $this->login()->forceAccess($this->role, 'collection:delete');

        $collection = factory(Collection::class)->create();

        $this->assertFalse((new CollectionPolicy)->delete($this->user, $collection));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_delete_delete_a_collection()
    {
        $this->login()->forceAccess($this->role, 'collection:delete');

        $collection = factory(Collection::class)->create([
            'team_id' => $this->team->id,
        ]);

        $this->assertTrue((new CollectionPolicy)->delete($this->user, $collection));
    }
}
