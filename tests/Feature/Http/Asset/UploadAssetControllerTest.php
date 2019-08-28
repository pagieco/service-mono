<?php

namespace Tests\Feature\Http\Asset;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use App\Jobs\CreateAssetThumbnail;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UploadAssetControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_is_not_allowed_to_upload_an_asset()
    {
        Storage::fake('uploads');

        $this->login();

        $response = $this->post(route('upload-asset', $this->domain()->id), [
            'file' => UploadedFile::fake()->image('avatar.jpeg'),
        ]);

        $response->assertSchema('UploadAsset', Response::HTTP_FORBIDDEN);

        $path = sprintf('%s/avatar.jpeg', $this->domain()->id);

        Storage::disk('uploads')->assertMissing($path);
    }

    /** @test */
    public function it_successfully_executes_the_upload_assets_route()
    {
        Storage::fake('uploads');

        $this->expectsJobs(CreateAssetThumbnail::class);

        $this->login()->forceAccess($this->role, 'asset:upload');

        $response = $this->post(route('upload-asset', $this->domain()->id), [
            'file' => UploadedFile::fake()->image('avatar.jpeg'),
        ]);

        $response->assertSchema('UploadAsset', Response::HTTP_CREATED);

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
