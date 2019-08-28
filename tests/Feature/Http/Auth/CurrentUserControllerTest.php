<?php

namespace Tests\Feature\Http\Auth;

use Tests\TestCase;
use App\Http\Response;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CurrentUserControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;

    /** @test */
    public function it_retrieves_the_currently_logged_in_user()
    {
        $this->login();

        $response = $this->get(route('current-user'));

        $response->assertSchema('CurrentUser', Response::HTTP_OK);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(): TestResponse
    {
        return $this->get(route('current-user'));
    }
}
