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

    function getMetaJson(Array $metaArr) {
        //{\'/YenMetam\': \'Test Metamm Zeyn\'}
        $json = '';
        $i = 0; $length = count($metaArr);
        foreach ($metaArr as $key => $value) {
            $i++;
            $json =  ' \'/' . $key .  '\': ' . ' \'' . $value . '\' ' 
                    . ($length > $i ? "," : "");
        }
        
        return '{ ' . $json . '} ';
    }

    function generatePyCommand(string $pyScriptpath, string $filePath, Array $metaArr) {

        $command = 'py ":py_script_path"  -path ":file_path" -meta_arr ":meta_arr"';

        $command = str_replace(":py_script_path", $pyScriptpath, $command);
        $command = str_replace(":file_path", $filePath, $command);
        $command = str_replace(":meta_arr", $this->getMetaJson($metaArr), $command);

        return  $command;
    }

    public function runPythonScript(string $path)
    {
        $py_path = Storage::disk('local')->path("py_script/add_meta.py");
        $file_path = Storage::disk('local')->path($path);

        $py_path = str_replace("\\", "/", $py_path);
        $file_path = str_replace("\\", "/", $file_path);

        $meta_arr = [
            "YenMetalarYhuu" => "Test Meat Zeynn"
        ];

        $comm = $this->generatePyCommand($py_path, $file_path,  $meta_arr);
        $process = shell_exec($comm);
        
        //dd([$comm, $process]);
        return $file_path;
    }

    function addMedia(string|UploadedFile $file): FileAdder
    {

        $file_name = str_replace(" ", "_", $file->getClientOriginalName());
        $path = $file->storeAs("temp_upload", $file_name);

        $path = $this->runPythonScript($path);

        $fileAdder = app(config('media-library.file_adder_model'));
        $fileAdder->file_category_id = $this->category_id;
        $fileAdder->setSubject($this)->setFile($path);

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
