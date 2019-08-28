<?php

namespace Tests\Feature\Http\Font;

use App\Font;
use Tests\TestCase;
use App\Http\Response;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetFontListControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;

    /** @test */
    public function it_throws_a_500_internal_server_error_when_the_font_list_is_empty()
    {
        $this->login();

        $this->makeRequest()->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /** @test */
    public function it_successfully_executes_the_get_font_list_route()
    {
        $this->login();

        factory(Font::class, 5)->create();

        $response = $this->makeRequest();

        $this->assertCount(5, $response->json('data'));

        $response->assertSchema('GetFontList', Response::HTTP_OK);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(): TestResponse
    {
        return $this->get(route('get-font-list'));
    }
}
