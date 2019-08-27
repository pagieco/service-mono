<?php

namespace Tets\Unit\Policies;

use App\Page;
use Tests\TestCase;
use App\Policies\PagePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PagePolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_false_when_the_user_has_no_acess_to_list_the_pages()
    {
        $this->login();

        $this->assertFalse((new PagePolicy)->list($this->user));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_list_the_pages()
    {
        $this->login()->forceAccess($this->role, 'page:list');

        $this->assertTrue((new PagePolicy)->list($this->user));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_no_permission_to_publish_a_page()
    {
        $this->login();

        $page = factory(Page::class)->create();

        $this->assertFalse((new PagePolicy)->publish($this->user, $page));
    }

    /** @test */
    public function it_returns_false_when_the_user_has_permission_to_publish_a_page_but_doesnt_belong_to_the_current_team()
    {
        $this->login()->forceAccess($this->role, 'page:publish');

        $page = factory(Page::class)->create();

        $this->assertFalse((new PagePolicy)->publish($this->user, $page));
    }

    /** @test */
    public function it_returns_true_when_the_user_has_permission_to_publish_a_page()
    {
        $this->login()->forceAccess($this->role, 'page:publish');

        $page = factory(Page::class)->create([
            'team_id' => $this->team->id,
        ]);

        $this->assertTrue((new PagePolicy)->publish($this->user, $page));
    }
}
