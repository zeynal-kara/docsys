<?php

namespace App\Models;

use App\Customizations\Media;
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


    public function __construct() {
        $this->author_id = auth()->user()->id;
    }

    function addMedia(string|UploadedFile $file): FileAdder
    {
        //dd(); die;
        // return $this->trAddMedia($file);

        $fileAdder = app(config('media-library.file_adder_model'));
        $fileAdder->file_category_id = $this->category_id;
        $fileAdder->setSubject($this)->setFile($file);

        return $fileAdder;
    }

    public function subjects() {
        return $this->belongsToMany(Subject::class, "doc_subject_pivots", "document_id", "subject_id");
    }

    public function author()
    {
        return $this->hasOne(User::class, "id", "author_id");
    }

    public function category()
    {
        return $this->hasOne(Category::class, "id", "category_id");
    }

    public function _media()
    {
        return $this->hasOne(Media::class, "model_id", "id");
    }
}
