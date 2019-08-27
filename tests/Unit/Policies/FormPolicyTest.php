<?php

namespace Tests\Unit\Policies;

use App\Form;
use App\Policies\FormPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FormPolicyTest extends PolicyTestCase
{
    use RefreshDatabase;

    protected $policyList = [
        'form:list',
        'form:create',
        'form:view-submissions',
    ];

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_list_the_forms()
    {
        $this->login();

        $this->assertFalse((new FormPolicy)->list($this->user));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_list_the_forms()
    {
        $this->login()->forceAccess($this->role, 'form:list');

        $this->assertTrue((new FormPolicy)->list($this->user));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_create_a_new_form()
    {
        $this->login();

        $this->assertFalse((new FormPolicy)->create($this->user));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_create_a_new_form()
    {
        $this->login()->forceAccess($this->role, 'form:create');

        $this->assertTrue((new FormPolicy)->create($this->user));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_view_the_form_submissions()
    {
        $this->login();

        $form = factory(Form::class)->create();

        $this->assertFalse((new FormPolicy)->viewSubmissions($this->user, $form));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_permission_to_view_the_form_submissions_but_the_form_doesnt_belong_to_the_users_team()
    {
        $this->login();

        $form = factory(Form::class)->create();

        $this->assertFalse((new FormPolicy)->viewSubmissions($this->user, $form));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_view_the_form_submissions()
    {
        $this->login()->forceAccess($this->role, 'form:view-submissions');

        $form = factory(Form::class)->create([
            'team_id' => $this->team->id,
        ]);

        $this->assertTrue((new FormPolicy)->viewSubmissions($this->user, $form));
    }
}
