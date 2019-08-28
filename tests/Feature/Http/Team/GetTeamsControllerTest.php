<?php

namespace Tests\Feature\Http\Teams;

use App\Team;
use Tests\TestCase;
use App\Http\Response;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetTeamsControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;

    /** @test */
    public function it_doesnt_include_teams_where_the_user_has_no_access_to()
    {
        $this->login()->forceAccess($this->role, 'team:list');

        factory(Team::class, 4)
            ->create()
            ->each(function (Team $team) {
                $this->user->joinTeam($team);
            });

        factory(Team::class)->create();

        $this->user->refresh();

        $this->assertCount(5, $this->user->teams);

        $response = $this->get(route('get-teams'));

        $this->assertCount(5, $response->json('data'));
    }

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_has_no_access_to_list_the_teams()
    {
        $this->login();

        $response = $this->get(route('get-teams'));

        $response->assertSchema('GetTeams', Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_successfully_executes_the_get_teams_route()
    {
        $this->login()->forceAccess($this->role, 'team:list');

        factory(Team::class, 4)->create()
            ->each(function (Team $team) {
                $this->user->joinTeam($team);
            });

        $this->user->refresh();

        $this->assertCount(5, $this->user->teams);

        $response = $this->get(route('get-teams'));

        $this->assertNotNull($response->json('links'));
        $this->assertNotNull($response->json('meta'));

        $response->assertSchema('GetTeams', Response::HTTP_OK);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(): TestResponse
    {
        return $this->get(route('get-teams'));
    }
}
