<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadImageAble
{
    protected function uploadImageOrPdf(UploadedFile $image, string $removeOldImage = null, $disk = 'public'): string
    {
        if ($removeOldImage) {
            Storage::disk('public')->delete($removeOldImage);
        }
        $path = $image->store($disk);
        $fileName = Str::replace("{$disk}/", '', $path);
        return $fileName;
    }
}
