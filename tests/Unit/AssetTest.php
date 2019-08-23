<?php

namespace Tests\Unit;

use App\Asset;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetTest extends ModelTestCase
{
    /**
     * The model instance to test.
     *
     * @var string
     */
    protected $model = Asset::class;

    /** @test */
    public function it_belongs_to_a_team()
    {
        $this->assertInstanceOf(BelongsTo::class, app($this->model)->team());
    }

    /** @test */
    public function it_can_get_the_hash_path_attribute()
    {
        $asset = factory(Asset::class)->create([
            'hash' => 'test-hash',
        ]);

        $hashPath = explode('?', $asset->hash_path)[1];

        $this->assertEquals('id=test-hash', $hashPath);
    }

    /** @test */
    public function it_formats_the_filesize_attribute()
    {
        $bytes = (new Asset(['filesize' => 10]))->pretty_filesize;
        $kilobytes = (new Asset(['filesize' => 10 * pow(1024, 1)]))->pretty_filesize;
        $megabytes = (new Asset(['filesize' => 10 * pow(1024, 2)]))->pretty_filesize;
        $gigabytes = (new Asset(['filesize' => 10 * pow(1024, 3)]))->pretty_filesize;
        $terabytes = (new Asset(['filesize' => 10 * pow(1024, 4)]))->pretty_filesize;
        $petabytes = (new Asset(['filesize' => 10 * pow(1024, 5)]))->pretty_filesize;

        $this->assertEquals('10 B', $bytes);
        $this->assertEquals('10 KB', $kilobytes);
        $this->assertEquals('10 MB', $megabytes);
        $this->assertEquals('10 GB', $gigabytes);
        $this->assertEquals('10 TB', $terabytes);
        $this->assertEquals('10 PB', $petabytes);
    }

    /** @test */
    public function it_can_be_unlinked()
    {
        Storage::fake();

        $asset = factory(Asset::class)->create();

        Storage::disk()->assertExists($asset->path);

        $asset->unlink();

        Storage::disk()->assertMissing($asset->path);

        $this->assertNull(Asset::find($asset->id));
    }

    /** @test */
    public function it_can_get_the_path_from_the_storage()
    {
        $this->assertEquals(Storage::disk()->path('test'), (new Asset)->getPath('test'));
    }

    /** @test */
    public function it_can_get_the_path_from_an_asset()
    {
        $asset = factory(Asset::class)->create();

        $this->assertStringEndsWith($asset->filename, $asset->getPath());
    }

    /** @test */
    public function it_returns_true_when_the_asset_is_an_image_based_on_the_mimetype()
    {
        $asset = factory(Asset::class)->create([
            'mimetype' => 'image/jpeg',
        ]);

        $this->assertTrue($asset->isImage());
    }

    /** @test */
    public function it_returns_false_when_the_asset_is_not_an_iamge_based_on_the_mimetype()
    {
        $asset = factory(Asset::class)->create([
            'mimetype' => 'application/pdf',
        ]);

        $this->assertFalse($asset->isImage());
    }

    /** @test */
    public function it_can_create_a_thumbnail_for_image_assets()
    {
        Storage::fake();

        $asset = factory(Asset::class)->create([
            'mimetype' => 'image/jpeg',
        ]);

        $asset->createThumbnail();

        $this->assertNotNull($asset->thumb_path);

        Storage::disk()->assertExists($asset->thumb_path);
    }

    /** @test */
    public function it_wont_create_a_thumbnail_for_a_non_image_asset()
    {
        Storage::fake();

        $asset = factory(Asset::class)->create([
            'mimetype' => 'application/pdf',
        ]);

        $asset->createThumbnail();

        $this->assertNull($asset->thumb_path);
    }
}
