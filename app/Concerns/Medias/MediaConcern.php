<?php

namespace App\Concerns\Medias;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\HasMedia;
use Throwable;

trait MediaConcern
{

    /**
     * @param HasMedia $model
     * @param Request $request
     * @param string $inputName
     * @param string|null $collectionName
     * @param string|null $usingFileName
     * @param bool $deletePreviousMedia
     * @throws Throwable
     */
    public function linkedMediaCollection(
        HasMedia $model,
        Request $request,
        string $inputName,
        ?string $collectionName = null,
        ?string $usingFileName = null,
        bool $deletePreviousMedia = false,
    ): void
    {
        if (in_array($request->input('avatar_remove') ?? null, ['1', true])){
            $model->clearMediaCollection($collectionName); // all media in the images collection will be deleted
        }

        if (!$collectionName) {
            $collectionName = $inputName;
        }

        if (empty($usingFileName)){
            $usingFileName = $inputName;
        }

        if ($deletePreviousMedia){
            if ($request->hasFile($inputName)){
                if ($model->getMedia($collectionName)->count() > 0){
                    $model->clearMediaCollection($collectionName); // all media in the images collection will be deleted
                }
            }
        }

        $customProperty = [];

        if (is_string($request->input($inputName))) {
            if (str($request->input($inputName))->startsWith('data:')) {
                $model->addMediaFromBase64($request->input($inputName))
                    ->usingFileName($usingFileName)
                    ->toMediaCollection($collectionName);
            }
            if (str($request->input($inputName))->isMatch('/https?:\/\//')) {
                $model->addMediaFromUrl($request->input($inputName))
                    ->usingFileName($usingFileName)
                    ->toMediaCollection($collectionName);
            }


        }

        if (is_array($request->file($inputName))){
            if ($request->hasFile($inputName)) {
                $model->addMultipleMediaFromRequest([$inputName])
                    ->each(function ($fileAddr) use ($usingFileName, $inputName, $customProperty, $collectionName) {
                        $fileAddr
                            ->withCustomProperties($customProperty)
                            ->usingFileName($usingFileName);
                        $fileAddr
                            ->toMediaCollection($collectionName);
                    });
            }
        }else{
            if ($request->hasFile($inputName)) {
                $media = $model->addMediaFromRequest($inputName);
                $media
                    ->withCustomProperties($customProperty)
                    ->usingFileName($usingFileName);

                $media->toMediaCollection($collectionName);
            }
        }

    }
}
