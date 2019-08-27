<?php

namespace Tests\Feature\Http\Form;

use App\Form;
use Tests\TestCase;
use App\Http\Response;
use App\FormSubmission;
use Tests\ValidatesOpenAPISchema;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetFormSubmissionsControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
    use ValidatesOpenAPISchema;

    /** @test */
    public function it_throws_a_404_not_found_exception_when_the_form_could_not_be_found()
    {
        $this->login();

        $response = $this->get(route('get-form-submissions', [$this->domain(), faker()->uuid]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $this->assertSchema($response, 'GetFormSubmissions', Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_throws_a_forbidden_exception_when_the_user_has_no_access_to_the_form()
    {
        $this->login();

        $form = $this->createTestResource();

        $response = $this->get(route('get-form-submissions', [$this->domain(), $form->id]));

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertSchema($response, 'GetFormSubmissions', Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_throws_a_404_not_found_exception_when_the_form_exists_but_is_not_part_of_the_team()
    {
        $this->login();

        $form = factory(Form::class)->create();

        $response = $this->get(route('get-form-submissions', [$this->domain(), $form->id]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $this->assertSchema($response, 'GetFormSubmissions', Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_returns_an_empty_response_when_no_submissions_where_found()
    {
        $this->login()->forceAccess($this->role, 'form:view-submissions');

        $form = $this->createTestResource();

        $response = $this->get(route('get-form-submissions', [$this->domain(), $form->id]));

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertSchema($response, 'GetFormSubmissions', Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function it_doesnt_include_submissions_from_other_forms()
    {
        $this->login()->forceAccess($this->role, 'form:view-submissions');

        $form = $this->createTestResource();

        factory(FormSubmission::class)->create([
            'team_id' => $form->team_id,
            'domain_id' => $form->domain_id,
            'form_id' => $form->id,
        ]);

        factory(FormSubmission::class)->create([
            'team_id' => $form->team_id,
            'domain_id' => $form->domain_id,
        ]);

        $response = $this->get(route('get-form-submissions', [$this->domain(), $form->id]));

        $this->assertCount(1, $response->json('data'));
    }

    /** @test */
    public function it_successfully_executes_the_get_form_submissions_route()
    {
        $this->login()->forceAccess($this->role, 'form:view-submissions');

        $form = $this->createTestResource();

        factory(FormSubmission::class)->create([
            'team_id' => $form->team_id,
            'domain_id' => $form->domain_id,
            'form_id' => $form->id,
        ]);

        $response = $this->get(route('get-form-submissions', [$this->domain(), $form->id]));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertSchema($response, 'GetFormSubmissions', Response::HTTP_OK);
    }

    /**
     * Prepare the authenticated route request.
     *
     * @param  string|array|null $uuid
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest($uuid = null): TestResponse
    {
        if (is_array($uuid)) {
            list($domain, $form) = $uuid;
        } else {
            $domain = faker()->uuid;
            $form = faker()->uuid;
        }

        return $this->get(route('get-form-submissions', [$domain, $form]));
    }

    /**
     * Get the route UUID used for verified route requests.
     *
     * @return string|array
     */
    protected function getRouteUuid()
    {
        $testResource = $this->createTestResource();

        return [$testResource->domain_id, $testResource->id];
    }

    protected function createTestResource(): Form
    {
        return factory(Form::class)->create([
            'team_id' => $this->team->id,
            'domain_id' => $this->domain()->id,
        ]);
    }
}
