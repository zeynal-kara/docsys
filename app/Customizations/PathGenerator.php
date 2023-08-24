<?php

namespace App\Customizations;

use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator as DPathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PathGenerator extends DPathGenerator
{
    public function getPath(Media $media) : string
    {
        return $this->getBasePath($media);
    }

    function getBasePath(Media $media): string
    {
        return "/doc_root/";
    }
}