<?php

namespace App;

use Illuminate\Support\Str;
use App\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image as InterventionImage;

class Asset extends Model
{
    use Concerns\HasUUID;
    use Concerns\Uploadable;
    use Concerns\BelongsToTeam;
    use Concerns\BelongsToDomain;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hash',
        'filename',
        'original_filename',
        'description',
        'extension',
        'mimetype',
        'filesize',
        'extra_attributes',
        'path',
        'thumb_path',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'extra_attributes' => 'array',
    ];

    /**
     * Get the pretty file-size of the file.
     *
     * @return string
     */
    public function getPrettyFilesizeAttribute(): string
    {
        return pretty_bytes($this->getAttribute('filesize'));
    }

    /**
     * Get the hash attribute.
     *
     * @return string
     */
    public function getHashPathAttribute(): string
    {
        $path = $this->getAttribute('path');
        $hash = $this->getAttribute('hash');

        return sprintf('%s?id=%s', $path, $hash);
    }

    /**
     * Delete this asset.
     *
     * @throws \Exception
     */
    public function unlink(): void
    {
        Storage::disk()->delete($this->path);

        $this->delete();
    }

    /**
     * Get the path to a filename.
     *
     * @param  string $filename
     * @return string
     */
    public function getPath(string $filename = null): string
    {
        if ($filename === null) {
            $filename = sprintf('%s/%s', $this->domain_id, $this->filename);
        }

        return Storage::disk()->path($filename);
    }

    /**
     * Create a thumbnail for this asset.
     *
     * @return $this|\App\Asset
     */
    public function createThumbnail()
    {
        if (! $this->isImage()) {
            return $this;
        }

        $filename = sprintf('%s/thumbnail_%s', $this->domain_id, $this->original_filename);

        Storage::disk()->put($filename, (string) $this->resizeImage(150)->encode());

        $this->update([
            'thumb_path' => $filename,
        ]);

        return $this->refresh();
    }

    /**
     * Determine that this asset is an image.
     *
     * @return bool
     */
    public function isImage(): bool
    {
        return Str::contains($this->mimetype, 'image');
    }

    /**
     * Resize this asset.
     *
     * @param  int $size
     * @return \Intervention\Image\Image
     */
    protected function resizeImage(int $size): InterventionImage
    {
        return Image::make($this->getPath($this->path))->resize(null, $size, function ($constraint) {
            $constraint->aspectRatio();
        });
    }
}
