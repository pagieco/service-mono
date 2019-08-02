<?php

namespace Tests\Feature\Http\Form;

use App\Form;
use Tests\TestCase;
use App\Http\Response;
use Tests\ValidatesOpenAPISchema;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetFormsControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
    use ValidatesOpenAPISchema;

    /** @test */
    public function it_returns_an_empty_response_when_no_forms_where_found()
    {
        $this->login()->forceAccess($this->role, 'form:list');

        $response = $this->get(route('get-forms', $this->domain()->id));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function it_doesnt_include_forms_from_other_teams()
    {
        $this->login()->forceAccess($this->role, 'form:list');

        $domain = $this->domain();

        factory(Form::class)->create([
            'team_id' => $this->team->id,
            'domain_id' => $domain->id,
        ]);

        factory(Form::class)->create();

        $response = $this->get(route('get-forms', $domain->id));

        $this->assertCount(1, $response->json('data'));
    }

    /** @test */
    public function it_doesnt_include_forms_from_other_domains()
    {
        $this->login()->forceAccess($this->role, 'form:list');

        $domain = $this->domain();

        factory(Form::class)->create([
            'team_id' => $this->team->id,
            'domain_id' => $domain->id,
        ]);

        factory(Form::class)->create([
            'team_id' => $this->team->id,
        ]);

        $response = $this->get(route('get-forms', $domain->id));

        $this->assertCount(1, $response->json('data'));
    }

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_has_no_access_to_list_the_assets()
    {
        $this->login();

        $response = $this->get(route('get-forms', $this->domain()->id));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_successfully_executes_the_get_forms_route()
    {
        $this->login()->forceAccess($this->role, 'form:list');

        $domain = $this->domain();

        factory(Form::class)->create([
            'team_id' => $this->team->id,
            'domain_id' => $domain->id,
        ]);

        $response = $this->get(route('get-forms', $domain->id));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSchema($response, 'GetForms', Response::HTTP_OK);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @param  string|null $uuid
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(string $uuid = null): TestResponse
    {
        return $this->get(route('get-forms', $uuid ?? faker()->uuid));
    }
}
