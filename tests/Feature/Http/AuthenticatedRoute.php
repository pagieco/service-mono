<?php

namespace Tests\Feature\Http;

use App\Http\Response;
use Illuminate\Foundation\Testing\TestResponse;

trait AuthenticatedRoute
{
    /** @test */
    public function it_fails_with_a_401_when_the_user_is_not_logged_in()
    {
        $response = $this->makeRequest();

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $response->assertJson([
            'message' => Response::$statusTexts[Response::HTTP_UNAUTHORIZED],
        ]);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(): TestResponse
    {
        throw new \BadMethodCallException('Please implement the `'.__METHOD__.'` method.');
    }
}
