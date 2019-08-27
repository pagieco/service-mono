<?php

namespace Tests\Unit\Policies;

use App\Asset;
use App\Policies\AssetPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssetPolicyTest extends PolicyTestCase
{
    use RefreshDatabase;

    protected $policyList = [
        'asset:list',
        'asset:view',
        'asset:upload',
        'asset:delete',
    ];

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_list_the_assets()
    {
        $this->login();

        $this->assertFalse((new AssetPolicy)->list($this->user));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_list_the_assets()
    {
        $this->login()->forceAccess($this->role, 'asset:list');

        $this->assertTrue((new AssetPolicy)->list($this->user));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_view_the_asset()
    {
        $user = $this->login();

        $asset = factory(Asset::class)->create();

        $this->assertFalse((new AssetPolicy)->view($user, $asset));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_access_to_view_the_asset_but_the_asset_is_not_from_the_users_team()
    {
        $this->login()->forceAccess($this->role, 'asset:view');

        $asset = factory(Asset::class)->create();

        $this->assertFalse((new AssetPolicy)->view($this->user, $asset));
    }

    /** @test */
    public function it_returns_true_when_to_user_has_permission_to_view_the_asset_and_the_asset_is_from_the_users_team()
    {
        $this->login()->forceAccess($this->role, 'asset:view');

        $asset = factory(Asset::class)->create([
            'team_id' => $this->team->id,
        ]);

        $this->assertTrue((new AssetPolicy)->view($this->user, $asset));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_upload_an_asset()
    {
        $this->login();

        $this->assertFalse((new AssetPolicy)->upload($this->user));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_upload_an_asset()
    {
        $this->login()->forceAccess($this->role, 'asset:upload');

        $this->assertTrue((new AssetPolicy)->upload($this->user));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_no_access_to_delete_the_asset()
    {
        $this->login();

        $asset = factory(Asset::class)->create();

        $this->assertFalse((new AssetPolicy)->delete($this->user, $asset));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_access_to_delete_the_asset_but_the_asset_is_not_from_the_users_team()
    {
        $this->login()->forceAccess($this->role, 'asset:delete');

        $asset = factory(Asset::class)->create();

        $this->assertFalse((new AssetPolicy)->delete($this->user, $asset));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_delete_an_asset()
    {
        $this->login()->forceAccess($this->role, 'asset:delete');

        $asset = factory(Asset::class)->create([
            'team_id' => $this->team->id,
        ]);

        $this->assertTrue((new AssetPolicy)->delete($this->user, $asset));
    }
}
