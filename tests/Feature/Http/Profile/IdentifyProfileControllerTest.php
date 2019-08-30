<?php

namespace Tests\Feature\Http\Profile;

use App\Domain;
use App\Profile;
use Tests\TestCase;
use App\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IdentifyProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_the_email_field_when_the_profiel_id_is_not_present()
    {
        $response = $this->post(route('identify-profile'));

        $response->assertSchema('IdentifyProfile', Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertEquals('The email field is required when profile id is not present.', $response->json('errors.email.0'));
    }

    /** @test */
    public function it_requires_the_new_email_field_when_non_of_email_profile_id_are_present()
    {
        $response = $this->post(route('identify-profile'));

        $response->assertSchema('IdentifyProfile', Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertEquals('The email field is required when profile id is not present.', $response->json('errors.email.0'));
        $this->assertEquals('The new email field is required when none of email / profile id are present.', $response->json('errors.new_email.0'));
        $this->assertEquals('The profile id field is required when email is not present.', $response->json('errors.profile_id.0'));
    }

    /** @test */
    public function it_requires_the_profile_id_field_when_the_email_field_is_not_present()
    {
        $response = $this->post(route('identify-profile'));

        $response->assertSchema('IdentifyProfile', Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertNotNull($response->json('errors.profile_id'));
    }

    /** @test */
    public function it_validates_on_valid_email_for_the_email_field()
    {
        $response = $this->post(route('identify-profile'), [
            'email' => 'invalid email address',
        ]);

        $response->assertSchema('IdentifyProfile', Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertEquals('The email must be a valid email address.', $response->json('errors.email.0'));
    }

    /** @test */
    public function it_validates_on_valid_email_for_the_new_email_field()
    {
        $response = $this->post(route('identify-profile'), [
            'new_email' => 'invalid email address',
        ]);

        $response->assertSchema('IdentifyProfile', Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertEquals('The new email must be a valid email address.', $response->json('errors.new_email.0'));
    }

    /** @test */
    public function it_validates_on_valid_uuid_for_the_profile_id_field()
    {
        $response = $this->post(route('identify-profile'), [
            'profile_id' => 'invalid uuid',
        ]);

        $response->assertSchema('IdentifyProfile', Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertEquals('The profile id must be a valid UUID.', $response->json('errors.profile_id.0'));
    }

    /** @test */
    public function it_throws_a_400_bad_request_exception_when_thje_domain_token_is_not_present()
    {
        $response = $this->post(route('identify-profile'), [
            'email' => faker()->email,
        ]);

        $response->assertSchema('IdentifyProfile', Response::HTTP_BAD_REQUEST);
    }

    /** @test */
    public function it_wont_return_profiles_from_other_domains()
    {
        $domain = factory(Domain::class)->create();

        $profile = factory(Profile::class)->create();

        $this->defaultHeaders['x-domain-token'] = $domain->api_token;

        $response = $this->post(route('identify-profile'), [
            'profile_id' => $profile->id,
        ]);

        $response->assertSchema('IdentifyProfile', Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_returns_a_valid_profile_when_the_profile_id_can_be_retrieved_from_the_request()
    {
        $domain = factory(Domain::class)->create();

        $profile = factory(Profile::class)->create([
            'domain_id' => $domain->id,
        ]);

        $this->defaultHeaders['x-domain-token'] = $domain->api_token;

        $response = $this->post(route('identify-profile'), [
            'profile_id' => $profile->id,
        ]);

        $response->assertSchema('IdentifyProfile', Response::HTTP_OK);
    }

    /** @test */
    public function it_returns_a_valid_profile_when_the_email_address_can_be_retrieved_from_the_request()
    {
        $domain = factory(Domain::class)->create();

        $profile = factory(Profile::class)->create([
            'domain_id' => $domain->id,
        ]);

        $this->defaultHeaders['x-domain-token'] = $domain->api_token;

        $response = $this->post(route('identify-profile'), [
            'email' => $profile->email,
        ]);

        $response->assertSchema('IdentifyProfile', Response::HTTP_OK);
    }
}
