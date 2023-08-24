<?php 

namespace App\Customizations;
use Spatie\MediaLibrary\MediaCollections\Filesystem;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Exception;

class CustomFilesystem extends Filesystem
{
    function removeAllFiles(Media $media): void
    {
        $mediaFilePath = $this->getMediaDirectory($media).$media->file_name;
        $this->filesystem->disk($media->disk)->delete($mediaFilePath);
    }
}
