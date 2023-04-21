<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;

class ImageUploader {

    public function upload(UploadedFile $image, $destination, $filename){


        if(!$image->isValid() || !in_array($image->getMimeType(), ['image/jpeg', 'image/png', 'image/gif'])){
            return null;
        }

        $filePath = $image->storeAs($destination, $filename);

        return $filePath;
    }
}
