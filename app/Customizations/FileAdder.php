<?php

namespace App\Customizations;

use Spatie\MediaLibrary\MediaCollections\FileAdder as BFileAdder;

use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\File;
use Spatie\MediaLibrary\Support\RemoteFile;
use Spatie\MediaLibraryPro\Models\TemporaryUpload;

class FileAdder extends BFileAdder
{
    public string $file_category_id = "";

    function addCustomProperty(Media $media) : Media {
        $media->file_category_id = $this->file_category_id;
        return $media;
    }

    function toMediaCollection(string $collectionName = 'default', string $diskName = ''): Media
    {
        $sanitizedFileName = ($this->fileNameSanitizer)($this->fileName);
        $fileName = app(config('media-library.file_namer'))->originalFileName($sanitizedFileName);
        $this->fileName = $this->appendExtension($fileName, pathinfo($sanitizedFileName, PATHINFO_EXTENSION));

        if ($this->file instanceof RemoteFile) {
            return $this->toMediaCollectionFromRemote($collectionName, $diskName);
        }

        if ($this->file instanceof TemporaryUpload) {
            return $this->toMediaCollectionFromTemporaryUpload($collectionName, $diskName, $this->fileName);
        }

        if (! is_file($this->pathToFile)) {
            throw FileDoesNotExist::create($this->pathToFile);
        }

        if (filesize($this->pathToFile) > config('media-library.max_file_size')) {
            throw FileIsTooBig::create($this->pathToFile);
        }

        $mediaClass = config('media-library.media_model');
        /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media $media */
        $media = new $mediaClass();

        $media->name = $this->mediaName;

        //Custom Code...
        $this->addCustomProperty($media);
        //END

        $media->file_name = $this->fileName;

        $media->disk = $this->determineDiskName($diskName, $collectionName);
        $this->ensureDiskExists($media->disk);

        $media->conversions_disk = $this->determineConversionsDiskName($media->disk, $collectionName);
        $this->ensureDiskExists($media->conversions_disk);

        $media->collection_name = $collectionName;

        $media->mime_type = File::getMimeType($this->pathToFile);
        $media->size = filesize($this->pathToFile);

        if (! is_null($this->order)) {
            $media->order_column = $this->order;
        }

        $media->custom_properties = $this->customProperties;

        $media->generated_conversions = [];
        $media->responsive_images = [];

        $media->manipulations = $this->manipulations;

        if (filled($this->customHeaders)) {
            $media->setCustomHeaders($this->customHeaders);
        }

        $media->fill($this->properties);

        $this->attachMedia($media);

        return $media;
    }


    function toMediaCollectionFromRemote(string $collectionName = 'default', string $diskName = ''): Media
    {
        $storage = Storage::disk($this->file->getDisk());

        if (! $storage->exists($this->pathToFile)) {
            throw FileDoesNotExist::create($this->pathToFile);
        }

        if ($storage->size($this->pathToFile) > config('media-library.max_file_size')) {
            throw FileIsTooBig::create($this->pathToFile, $storage->size($this->pathToFile));
        }

        $mediaClass = config('media-library.media_model');
        /** @var \Spatie\MediaLibrary\MediaCollections\Models\Media $media */
        $media = new $mediaClass();

        $media->name = $this->mediaName;

        //Custom Code...
        $this->addCustomProperty($media);
        //END

        $sanitizedFileName = ($this->fileNameSanitizer)($this->fileName);
        $fileName = app(config('media-library.file_namer'))->originalFileName($sanitizedFileName);
        $this->fileName = $this->appendExtension($fileName, pathinfo($sanitizedFileName, PATHINFO_EXTENSION));

        $media->file_name = $this->fileName;

        $media->disk = $this->determineDiskName($diskName, $collectionName);
        $this->ensureDiskExists($media->disk);
        $media->conversions_disk = $this->determineConversionsDiskName($media->disk, $collectionName);
        $this->ensureDiskExists($media->conversions_disk);

        $media->collection_name = $collectionName;

        $media->mime_type = $storage->mimeType($this->pathToFile);
        $media->size = $storage->size($this->pathToFile);
        $media->custom_properties = $this->customProperties;

        $media->generated_conversions = [];
        $media->responsive_images = [];

        $media->manipulations = $this->manipulations;

        if (filled($this->customHeaders)) {
            $media->setCustomHeaders($this->customHeaders);
        }

        $media->fill($this->properties);

        $this->attachMedia($media);

        return $media;
    }

}
