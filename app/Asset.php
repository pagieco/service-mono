<?php

namespace App;

use App\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
}
