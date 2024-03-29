<?php

namespace App\Concerns\Medias;

use Exception;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\DiskDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;

class MediaConcern
{
    protected ?array $customProperties = null;
    protected ?string $customFileName = null;
    protected bool $deletePreviousMedia = false;
    protected ?string $collectionName = null;
    protected ?string $disk = null;

    public function __construct(
        protected readonly HasMedia       $model,
        protected readonly string|Request $source,
        protected readonly string         $inputName,
    )
    {
        if (!$this->collectionName) {
            $this->collectionName = $inputName;
        }
    }

    public function linkedMediaCollection(): void
    {
        if ($this->deletePreviousMedia) {
            if ($this->model->getMedia($this->collectionName)->count() > 0) {
                $this->model->clearMediaCollection($this->collectionName); // all media in the images collection will be deleted

                $this->handleUploadedMedia();
            }
        }
    }

    /**
     * @return void
     * @throws DiskDoesNotExist
     * @throws Exception
     */
    protected function handleUploadedMedia(): void
    {

        if (!empty($this->disk)) {
            $this->handleUploadedMediaFromDisk();
            return;
        }

        if ($this->source instanceof Request){
            $this->handleUploadedMediaFromRequest();
        }

        if (is_string($this->source)) {
            if (str($this->source)->startsWith('data:')) {
                $uploaded = $this->model
                    ->addMediaFromBase64($this->inputName);
                if ($this->customFileName) {
                    $uploaded->usingFileName($this->customFileName);
                }
                $uploaded
                    ->toMediaCollection($this->collectionName);
            }
            if (str($this->inputName)->isMatch('/https?:\/\//') || str($this->inputName)->isMatch('/http?:\/\//')) {
                $uploaded = $this->model
                    ->addMediaFromUrl($this->inputName);
                if ($this->customFileName) {
                    $uploaded->usingFileName($this->customFileName);
                }
                $uploaded
                    ->toMediaCollection($this->collectionName);
            }
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function handleUploadedMediaFromRequest(): void
    {
        if ($this->source instanceof Request) {
            if (!$this->source->hasFile($this->inputName)) {
                throw new Exception('File does not exist');
            }

            if (is_array($this->source->file($this->inputName))) {
                $this->model->addMultipleMediaFromRequest([$this->inputName])
                    ->each(function ($uploaded) {
                        if (!empty($this->customProperties)) {
                            $uploaded
                                ->withCustomProperties($this->customProperties);
                        }
                        if (!empty($this->customFileName)) {
                            $uploaded
                                ->usingFileName($this->customFileName);
                        }

                        $uploaded
                            ->toMediaCollection($this->collectionName);
                    });
            } else {
                $uploaded = $this->model->addMediaFromRequest($this->inputName);
                if (!empty($this->customProperties)) {
                    $uploaded
                        ->withCustomProperties($this->customProperties);
                }
                if (!empty($this->customFileName)) {
                    $uploaded
                        ->usingFileName($this->customFileName);
                }

                $uploaded
                    ->toMediaCollection($this->collectionName);
            }
        }
    }

    /**
     * @return void
     * @throws DiskDoesNotExist
     */
    protected function handleUploadedMediaFromDisk(): void
    {
        if (empty($this->disk)) {
            throw DiskDoesNotExist::create($this->disk);
        }
    }

//    /**
//     * @param HasMedia $model
//     * @param Request $request
//     * @param string $inputName
//     * @param string|null $collectionName
//     * @param string|null $usingFileName
//     * @param bool $deletePreviousMedia
//     * @param string|null $disk
//     */
//    public function linkedMediaCollection(
//        HasMedia $model,
//        Request $request,
//        string $inputName,
//        ?string $collectionName = null,
//        ?string $usingFileName = null,
//        bool $deletePreviousMedia = false,
//        ?string $disk = null
//    ): void
//    {
//        if (in_array($request->input('avatar_remove') ?? null, ['1', true])){
//            $model->clearMediaCollection($collectionName); // all media in the images collection will be deleted
//        }
//
//        if (!$collectionName) {
//            $collectionName = $inputName;
//        }
//
//        if (empty($usingFileName)){
//            $usingFileName = $inputName;
//        }
//
//        if ($deletePreviousMedia){
//            if ($request->hasFile($inputName)){
//                if ($model->getMedia($collectionName)->count() > 0){
//                    $model->clearMediaCollection($collectionName); // all media in the images collection will be deleted
//                }
//            }
//        }
//
//        $customProperty = [];
//
//        if (empty($disk)){
//            if (is_string($request->input($inputName))) {
//                if (str($request->input($inputName))->startsWith('data:')) {
//                    $model->addMediaFromBase64($request->input($inputName))
//                        ->usingFileName($usingFileName)
//                        ->toMediaCollection($collectionName);
//                }
//                if (str($request->input($inputName))->isMatch('/https?:\/\//')) {
//                    $model->addMediaFromUrl($request->input($inputName))
//                        ->usingFileName($usingFileName)
//                        ->toMediaCollection($collectionName);
//                }
//                if (str($request->input($inputName))->isMatch('/http?:\/\//')) {
//                    $model->addMediaFromUrl($request->input($inputName))
//                        ->usingFileName($usingFileName)
//                        ->toMediaCollection($collectionName);
//                }
//            }
//        }
//
//
//        if (is_array($request->file($inputName))){
//            if ($request->hasFile($inputName)) {
//                $model->addMultipleMediaFromRequest([$inputName])
//                    ->each(function ($fileAddr) use ($usingFileName, $inputName, $customProperty, $collectionName) {
//                        $fileAddr
//                            ->withCustomProperties($customProperty)
//                            ->usingFileName($usingFileName);
//                        $fileAddr
//                            ->toMediaCollection($collectionName);
//                    });
//            }
//        }else{
//            if ($request->hasFile($inputName)) {
//                $media = $model->addMediaFromRequest($inputName);
//                $media
//                    ->withCustomProperties($customProperty)
//                    ->usingFileName($usingFileName);
//
//                $media->toMediaCollection($collectionName);
//            }
//        }
//
//    }

    public
    function usingCustomFileName(?string $customFileName): MediaConcern
    {
        $this->customFileName = $customFileName;
        return $this;
    }

    /**
     * @param bool $deletePreviousMedia
     * @return $this
     */
    public
    function deletePreviousMedia(bool $deletePreviousMedia): MediaConcern
    {
        $this->deletePreviousMedia = $deletePreviousMedia;
        return $this;
    }

    /**
     * @param string|null $collectionName
     * @return $this
     */
    public
    function setCollectionName(?string $collectionName): MediaConcern
    {
        $this->collectionName = $collectionName;
        return $this;
    }

    /**
     * @param string|null $disk
     * @return $this
     */
    public
    function disk(?string $disk): MediaConcern
    {
        $this->disk = $disk;
        return $this;
    }

    public
    function __destruct()
    {
        $this->linkedMediaCollection();
    }

    /**
     * @param array|null $customProperties
     * @return $this
     */
    public
    function setCustomProperties(?array $customProperties): MediaConcern
    {
        $this->customProperties = $customProperties;
        return $this;
    }
}
