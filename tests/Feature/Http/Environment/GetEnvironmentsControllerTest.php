<?php

namespace Tests\Feature\Http\Environment;

use Tests\TestCase;
use App\Environment;
use App\Http\Response;
use Tests\ValidatesOpenAPISchema;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetEnvironmentsControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
    use ValidatesOpenAPISchema;

    /** @test */
    public function it_returns_an_empty_response_when_no_environments_where_found()
    {
        $this->login();

        $response = $this->get(route('get-environments'));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function it_doesnt_include_environments_from_other_teams()
    {
        $this->login();

        $this->team->environments()->create(
            factory(Environment::class)->raw()
        );

        factory(Environment::class)->create();

        $response = $this->get(route('get-environments'));

        $this->assertCount(1, $response->json('data'));
    }

    /** @test */
    public function it_successfully_executes_the_get_environments_route()
    {
        $this->login();
        $this->domain();

        $response = $this->get(route('get-environments'));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSchema($response, 'GetEnvironments', Response::HTTP_OK);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(): TestResponse
    {
        return $this->get(route('get-environments'));
    }
}
