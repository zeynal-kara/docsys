<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\FileAdder;

class Document extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia { addMedia as protected trAddMedia; }

    function addMedia(string|UploadedFile $file): FileAdder
    {
        //dd($this); die;
        // return $this->trAddMedia($file);

        $fileAdder = app(config('media-library.file_adder_model'));
        $fileAdder->file_category_id = 1;
        $fileAdder->setSubject($this)->setFile($file);

        return $fileAdder;
    }
}
