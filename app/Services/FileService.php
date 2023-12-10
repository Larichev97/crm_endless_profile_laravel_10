<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

final class FileService
{
    /**
     *  Метод загружает файл и возвращает его название с расширением ('test.jpg') или пустую строку
     *
     * @param $file
     * @param string $publicDirPath
     * @return string
     */
    public function processUploadFile($file, string $publicDirPath = ''): string
    {
        $fileName = '';

        if (!empty($file) && $file instanceof UploadedFile) {
            $publicDirPath = $this->processPreparePath($publicDirPath);

            $currentFileName = time().'.'.$file->extension();

            if (!File::exists(storage_path('app/public/'.$publicDirPath))) {
                File::makeDirectory(storage_path('app/public/'.$publicDirPath), 0777, true);
            }

            $uploadPhoto = $file->storeAs('public/'.$publicDirPath, $currentFileName);

            if ($uploadPhoto && !empty($currentFileName)) {
                $fileName = $currentFileName;
            }
        }

        return $fileName;
    }

    /**
     *  Метод удаляет файл по конкретному пути
     *
     * @param string $oldFileName
     * @param string $publicDirPath
     * @return bool
     */
    public function processDeleteOldFile(string $oldFileName, string $publicDirPath = ''): bool
    {
        if (!empty($oldFileName)) {
            $publicDirPath = $this->processPreparePath($publicDirPath);

            // Проверка наличия файла и его удаление, если он существует:
            $oldPhotoPath = storage_path('app/public/'.$publicDirPath.'/'.$oldFileName);

            if (File::exists($oldPhotoPath)) {
                return (bool) File::delete($oldPhotoPath);
            }
        }

        return false;
    }

    /**
     *  Метод формирует публичный путь к файлу
     *
     * @param string $fileName
     * @param string $publicDirPath
     * @return string
     */
    public function processGetPublicFilePath(string $fileName, string $publicDirPath = ''): string
    {
        $filePath = '';

        $publicDirPath = $this->processPreparePath($publicDirPath);

        if (!empty($fileName) && File::exists(storage_path('app/public/'.$publicDirPath.'/'.$fileName))) {
            $filePath = 'storage/'.$publicDirPath.'/'.$fileName;
        }

        return $filePath;
    }

    /**
     *  Метод удаляет из начала и окончания строки "/"
     *
     * @param string $dirPath
     * @return string
     */
    public function processPreparePath(string $dirPath): string
    {
        if (str_starts_with($dirPath, '/')) {
            $dirPath = substr($dirPath, 1);
        }

        if (str_ends_with($dirPath, '/')) {
            $dirPath = substr($dirPath, 0, -1);
        }

        return $dirPath;
    }
}
