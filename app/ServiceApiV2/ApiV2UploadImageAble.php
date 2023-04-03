<?php

namespace App\ServiceApiV2;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait ApiV2UploadImageAble
{
    protected function uploadImageArticle(UploadedFile $image, $removeOldImage = null, $disk = 'public'): string
    {
        if ($removeOldImage) {
            Storage::disk('public')->delete($removeOldImage);
        }
        $path = $image->store($disk);
        $fileName = Str::replace("{$disk}/", '', $path);
        return $fileName;
    }
}
