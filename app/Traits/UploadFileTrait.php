<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UploadFileTrait
{
    public function uploadSingleFilePowerPoint($request, $fileNameInView, $prefixFileName, $folderName)
    {
        $fileName = '';
        $filePath = '';
        $isSuccess = false;
        if ($request->hasFile($fileNameInView)) {
            $fileTmp = $request->file($fileNameInView);
            if ($fileTmp->isValid()) {
                // Get file extension
                $fileExtension = $fileTmp->getClientOriginalExtension();

                // Generate new file name
                if (empty($prefixFileName)) {
                    $prefixFileName = '';
                }
                $fileName = Str::slug($prefixFileName) . '-' . Str::random(10) . '.' . $fileExtension;

                // Generate folder path
                $folderPath = 'power_point/' . $folderName;

                // Generate file path to store
                $filePathToStore = 'app/public/' . $folderPath;

                // Check folder exist
                if (is_dir(storage_path($filePathToStore)) == false) {
                    $this->makeDir($filePathToStore);
                }

                if (is_dir(storage_path($filePathToStore)) == true) {
                    // Generate file path to store in app/public/
                    $filePathToStore = 'public/' . $folderPath;

                    // Upload the file
                    $resultUpload = $fileTmp->storeAs($filePathToStore, $fileName);

                    if (!empty($resultUpload)) {
                        $isSuccess = true;
                        $filePath = 'storage/' . $folderPath . '/' . $fileName;
                    } else {
                        $isSuccess = false;
                    }
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
                'file_name' => $fileName,
                'file_path' => $filePath
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
