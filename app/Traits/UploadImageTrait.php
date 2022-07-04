<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait UploadImageTrait
{
    public function uploadSingleImage($request, $imageNameInView, $prefixImageName, $folderName, $imageWidth, $imageHeight)
    {
        $imageName = '';
        $imagePath = '';
        $isSuccess = false;
        if ($request->hasFile($imageNameInView)) {
            $imageTmp = $request->file($imageNameInView);
            if ($imageTmp->isValid()) {
                // Get image extension
                $imageExtension = $imageTmp->getClientOriginalExtension();

                // Generate new image name
                if (empty($prefixImageName)) {
                    $prefixImageName = '';
                }
                $imageName = Str::slug($prefixImageName) . '-' . Str::random(10) . '.' . $imageExtension;

                // Generate folder path
                $folderPath = 'image/' . $folderName;

                // Generate image path to store
                $imagePathToStore = 'app/public/' . $folderPath;

                // Check folder exist
                if (is_dir(storage_path($imagePathToStore)) == false) {
                    $this->makeDir($imagePathToStore);
                }

                if (is_dir(storage_path($imagePathToStore)) == true) {
                    // Upload the image
                    if (!empty($imageWidth) && !empty($imageHeight)) {
                        Image::make($imageTmp)->resize($imageWidth, $imageHeight)->save(storage_path($imagePathToStore . '/' . $imageName));
                    } else {
                        Image::make($imageTmp)->save(storage_path($imagePathToStore . '/' . $imageName));
                    }
                    $isSuccess = true;
                    $imagePath = 'storage/' . $folderPath . '/' . $imageName;
                } else {
                    $isSuccess = false;
                }
            } else {
                $isSuccess = false;
            }
        } else {
            $isSuccess = false;
        }

        if ($isSuccess == true) {
            return [
                'image_name' => $imageName,
                'image_path' => $imagePath
            ];
        } else {
            return false;
        }
    }

    public function uploadSingleImageForMultipleInput($imageTmp, $prefixImageName, $folderName, $imageWidth, $imageHeight)
    {
        $imageName = '';
        $imagePath = '';
        $isSuccess = false;
        if ($imageTmp->isValid()) {
            // Get image extension
            $imageExtension = $imageTmp->getClientOriginalExtension();

            // Generate new image name
            if (empty($prefixImageName)) {
                $prefixImageName = '';
            }
            $imageName = Str::slug($prefixImageName) . '-' . Str::random(10) . '.' . $imageExtension;

            // Generate folder path
            $folderPath = 'image/' . $folderName;

            // Generate image path to store
            $imagePathToStore = 'app/public/' . $folderPath;

            // Check folder exist
            if (is_dir(storage_path($imagePathToStore)) == false) {
                $this->makeDir($imagePathToStore);
            }

            if (is_dir(storage_path($imagePathToStore)) == true) {
                // Upload the image
                if (!empty($imageWidth) && !empty($imageHeight)) {
                    Image::make($imageTmp)->resize($imageWidth, $imageHeight)->save(storage_path($imagePathToStore . '/' . $imageName));
                } else {
                    Image::make($imageTmp)->save(storage_path($imagePathToStore . '/' . $imageName));
                }
                $isSuccess = true;
                $imagePath = 'storage/' . $folderPath . '/' . $imageName;
            } else {
                $isSuccess = false;
            }
        } else {
            $isSuccess = false;
        }


        if ($isSuccess == true) {
            return [
                'image_name' => $imageName,
                'image_path' => $imagePath
            ];
        } else {
            return false;
        }
    }

    private function makeDir($path)
    {
        return is_dir($path) || mkdir(storage_path($path), 0755, true);
    }
}
