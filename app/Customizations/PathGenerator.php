<?php

namespace App\Customizations;

use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator as DPathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PathGenerator extends DPathGenerator
{
    public function getPath(Media $media) : string
    {
        if($media->file_category_id)
            return "/doc_root/". $media->file_category_id . "/";
        else
            return $this->getBasePath($media).'/';
    }

    function getBasePath(Media $media): string
    {
        return "/doc_root/". $media->file_category_id . "/";
    }
}