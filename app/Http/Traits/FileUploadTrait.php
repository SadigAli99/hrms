<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic as Image;

trait FileUploadTrait
{
    public function fileUpload($file, string $folder)
    {
        $images = '';
        $uploadPath = public_path('uploads/' . $folder);
        if (!File::isDirectory($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        if (is_array($file)) {
            $images = [];
            foreach ($file as $f) {
                $images[] = $this->processFile($f, $uploadPath, $folder);
            }
        } else {
            $images = $this->processFile($file, $uploadPath, $folder);
        }

        return $images;
    }

    private function processFile($file, $uploadPath, $folder)
    {
        $originalExtension = $file->getClientOriginalExtension();
        $validExtensions = ['png', 'jpg', 'jpeg', 'webp'];

        $file_name = rand(10000000, 99999999) . '.' . $originalExtension;
        $filePath = $uploadPath . '/' . $file_name;

        if (in_array($originalExtension, $validExtensions)) {
            try {
                $image = Image::make($file->getRealPath());
                $webpPath = $uploadPath . '/' . rand(10000000, 99999999) . '.webp';

                if ($image->encode('webp', 80)->save($webpPath)) {
                    return 'uploads/' . $folder . '/' . basename($webpPath);
                }
            } catch (\Exception $e) {
                Log::error("WebP çevirmə xətası: " . $e->getMessage());
            }
        }

        // Alınmasa, original formatda saxla
        $file->move($uploadPath, $file_name);
        return 'uploads/' . $folder . '/' . $file_name;
    }

    public function fileDelete($file): void
    {
        $file_path = public_path($file);
        if (File::exists($file_path)) {
            File::delete($file_path);
        }
    }
}
