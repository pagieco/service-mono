<?php

namespace Tests\Feature\Http\User;

use Tests\TestCase;
use App\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Http\AuthenticatedRoute;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UploadPictureControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthenticatedRoute;

    /** @test */
    public function it_throws_a_403_forbidden_exception_when_the_user_is_not_allowed_to_upload_a_picture()
    {
        Storage::fake('avatars');

        $this->login();

        $response = $this->post(route('upload-user-picture'), [
            'picture' => UploadedFile::fake()->image('picture.jpeg'),
        ]);

        $response->assertSchema('UploadUserPicture', Response::HTTP_FORBIDDEN);

        Storage::disk('avatars')->assertMissing(sprintf('%s.jpeg', $this->user->id));
    }

    /** @test */
    public function it_validates_on_required_picture_when_uploading_a_profile_picture()
    {
        $this->login();

        $response = $this->post(route('upload-user-picture'));

        $response->assertSchema('UploadUserPicture', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_validates_on_image_format_when_uploading_a_profile_picture()
    {
        $this->login();

        $response = $this->post(route('upload-user-picture'), [
            'picture' => UploadedFile::fake()->create('picture.mov'),
        ]);

        $response->assertSchema('UploadUserPicture', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_successfully_executes_the_upload_picture_route()
    {
        Storage::fake('avatars');

        $this->login()->forceAccess($this->role, 'user:upload-picture');

        $response = $this->post(route('upload-user-picture'), [
            'picture' => UploadedFile::fake()->image('picture.jpeg'),
        ]);

        $response->assertSchema('UploadUserPicture', Response::HTTP_OK);

        Storage::disk('avatars')->assertExists($this->user->id.'.jpeg');
    }

    /**
     * Prepare the authenticated route request.
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function makeRequest(): TestResponse
    {
        return $this->post(route('upload-user-picture'));
    }
}
