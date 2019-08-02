<?php

namespace Tests\Feature\Http\Teams;

use App\Team;
use Tests\TestCase;
use App\Http\Response;
use Tests\ValidatesOpenAPISchema;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetTeamControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
    use ValidatesOpenAPISchema;

    /** @test */
    public function it_throws_a_404_exception_when_the_team_could_not_be_found()
    {
        $this->login();

        $response = $this->get(route('get-team', faker()->uuid));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_has_no_access_to_the_team()
    {
        $this->login();

        $team = factory(Team::class)->create();

        $response = $this->get(route('get-team', $team->getKey()));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_is_a_member_of_the_team_but_has_no_permission_to_view_the_team()
    {
        $this->login();

        $response = $this->get(route('get-team', $this->team->getKey()));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_successfully_executes_the_get_team_route()
    {
        $this->login()->forceAccess($this->role, 'team:view');

        $response = $this->get(route('get-team', $this->team->getKey()));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSchema($response, 'GetTeam', Response::HTTP_OK);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @param  string|null $uuid
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(string $uuid = null): TestResponse
    {
        return $this->get(route('get-team', $uuid ?? faker()->uuid));
    }

    /**
     * Get the route UUID used for verified route requests.
     *
     * @return string
     */
    protected function getRouteUuid(): string
    {
        return factory(Team::class)->create()->id;
    }
}
