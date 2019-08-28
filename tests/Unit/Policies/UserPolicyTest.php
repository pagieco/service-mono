<?php

namespace Tests\Unit\Policies;

use App\Policies\UserPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPolicyTest extends PolicyTestCase
{
    use RefreshDatabase;

    protected $policyList = [
        'user:upload-picture',
    ];

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_upload_a_profile_picture()
    {
        $this->login();

        $this->assertFalse((new UserPolicy)->uploadPicture($this->user));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_upload_a_profile_picture()
    {
        $this->login()->forceAccess($this->role, 'user:upload-picture');

        $this->assertTrue((new UserPolicy)->uploadPicture($this->user));
    }
}
