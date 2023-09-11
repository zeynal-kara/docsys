<?php 

namespace App\Customizations;
use Spatie\MediaLibrary\MediaCollections\Filesystem;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Exception;
use Illuminate\Support\Facades\Storage;

class CustomFilesystem extends Filesystem
{
    function removeAllFiles(Media $media): void
    {
        
        try {
            parent::removeAllFiles($media);
            
            $mediaFilePath = $this->getMediaDirectory($media);
            
            array_filter(Storage::disk('files')->files($mediaFilePath), function ($item) {
                $item->delete();
            });

        } catch (\Throwable $th) {
            //throw $th;
        }

         Storage::disk('files')->put($mediaFilePath . "delete", "");
    }
}
