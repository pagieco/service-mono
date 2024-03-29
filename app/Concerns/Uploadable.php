<?php

namespace App\Concerns;

use App\Team;
use App\Asset;
use App\Domain;
use Illuminate\Http\UploadedFile;

trait Uploadable
{
    /**
     * @var UploadedFile|null
     */
    protected $originFile = null;

    /**
     * Upload the current file.
     *
     * @param  string $disk
     * @return Asset
     */
    public function upload($disk = 'uploads'): Asset
    {
        $file = $this->getOriginFile();

        $filename = $file->getClientOriginalName();
        $filepath = $this->domain_id;

        $path = $file->storeAs($filepath, $filename, [
            'disk' => $disk
        ]);

        return tap($this)->fill(['path' => $path]);
    }

    /**
     * Create a new asset instance from the given uploaded file.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  \App\Domain|null $domain
     * @param  \App\Team|null $team
     * @return \App\Asset
     * @throws \Throwable
     */
    public static function fromFile(UploadedFile $file, ?Domain $domain = null, ?Team $team = null): Asset
    {
        $instance = new static([
            'hash' => $file->getContentHash(),
            'filename' => $file->getClientOriginalName(),
            'original_filename' => $file->getClientOriginalName(),
            'extension' => $file->getClientOriginalExtension(),
            'mimetype' => $file->getClientMimeType(),
            'filesize' => $file->getSize(),
            'extra_attributes' => $file->getExtraAttributes(),
        ]);

        $instance->setOriginFile($file);

        $instance->domain()->associate($domain ?? request()->route('domain'));
        $instance->team()->associate($team ?? current_team());

        return $instance;
    }

    /**
     * @return UploadedFile|null
     */
    public function getOriginFile(): ?UploadedFile
    {
        return $this->originFile;
    }

    /**
     * @param UploadedFile|null $originFile
     */
    public function setOriginFile(?UploadedFile $originFile)
    {
        $this->originFile = $originFile;
    }
}
