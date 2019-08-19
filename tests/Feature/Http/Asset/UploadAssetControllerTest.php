<?php

namespace Tests\Feature\Http\Asset;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Tests\ValidatesOpenAPISchema;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UploadAssetControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;
    use ValidatesOpenAPISchema;

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_is_not_allowed_to_upload_an_asset()
    {
        Storage::fake();

        $this->login();

        $response = $this->post(route('upload-asset', $this->domain()->id), [
            'file' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertSchema($response, 'UploadAsset', Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_successfully_executes_the_upload_assets_route()
    {
        Storage::fake('uploads');

        $this->login()->forceAccess($this->role, 'asset:upload');

        $response = $this->post(route('upload-asset', $this->domain()->id), [
            'file' => UploadedFile::fake()->image('avatar.jpeg'),
        ]);

        $this->assertSchema($response, 'UploadAsset', Response::HTTP_CREATED);

        Storage::disk('uploads')->assertExists($response->json('data.path'));
    }

    /**
     * Prepare the authenticated route request.
     *
     * @param  string|null $uuid
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(string $uuid = null): TestResponse
    {
        return $this->post(route('upload-asset', $uuid ?? faker()->uuid));
    }
}
