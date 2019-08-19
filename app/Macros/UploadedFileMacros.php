<?php

namespace App\Macros;

use Illuminate\Http\UploadedFile;

trait UploadedFileMacros
{
    public static function registerUploadedFileMacros()
    {
        UploadedFile::macro('getContentHash', function () {
            $contents = $this->openFile()->fread($this->getSize());

            return substr(md5($contents), 0, 20);
        });

        UploadedFile::macro('getExtraAttributes', function () {
            if (substr($this->getMimeType(), 0, 5) == 'image') {
                list($width, $height) = getimagesize($this);

                return [
                    'width' => $width,
                    'height' => $height,
                ];
            }
        });
    }
}
