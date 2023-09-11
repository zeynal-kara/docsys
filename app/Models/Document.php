<?php

namespace App\Models;

use App\Customizations\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\FileAdder;

class Document extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia { addMedia as protected trAddMedia; }


    public function __construct(array $attributes = []) {
        $this->author_id = auth()->user()->id;
        parent::__construct($attributes);
    }    

    function addMedia(string|UploadedFile $file): FileAdder
    {

        // $file_name = str_replace(" ", "_", $file->getClientOriginalName());
        // $path = $file->storeAs("temp_upload", $file_name);

        // $path = $this->runPythonScript($path);

        $fileAdder = app(config('media-library.file_adder_model'));
        $fileAdder->file_key = $this->getKey();
        // $fileAdder->setSubject($this)->setFile($path);
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
