<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCode;

final class FileService
{
    /**
     *  Метод генерирует QR-код с URL и сохраняет его в директорию при помощи внешней библиотеки "Simplesoftwareio"
     *
     * @param string $url
     * @param string $publicDirPath
     * @param string $qrCodeFileName
     * @param string $qrCodeFileExtension
     * @param int $qrCodeSize
     * @return bool
     */
    public function processGenerateQrCodeFile(string $url, string $publicDirPath, string $qrCodeFileName, string $qrCodeFileExtension, int $qrCodeSize = 300): bool
    {
        if (!empty($url) && !empty($qrCodeFileName) && in_array($qrCodeFileExtension, ['png', 'svg', 'eps'])) {
            $publicDirPath = $this->processPreparePath($publicDirPath);

            $qrCodeFileNameWithExtension = $qrCodeFileName.'.'.$qrCodeFileExtension;
            $qrCodeFilePathWithName = storage_path(path: 'app/public/'.$publicDirPath.'/'.$qrCodeFileNameWithExtension);

            if (!File::exists(storage_path(path: 'app/public/'.$publicDirPath))) {
                File::makeDirectory(storage_path(path: 'app/public/'.$publicDirPath), mode: 0777, recursive: true);
            }

            $this->processDeleteOldFile(oldFileName: $qrCodeFileNameWithExtension, publicDirPath: $publicDirPath);

            QrCode::size($qrCodeSize)->format($qrCodeFileExtension)->errorCorrection('M')->generate($url, $qrCodeFilePathWithName);

            if (File::exists($qrCodeFilePathWithName)) {
                return true;
            }
        }

        return false;
    }

    /**
     *  Метод загружает файл и возвращает его название с расширением ('test.jpg') или пустую строку
     *
     * @param $file
     * @param string $publicDirPath
     * @param string $oldFileName Старое название файла
     * @param string $newFileName Название файла (если не указано - создаётся time()+.+extension())
     * @param bool $useOriginalFileName
     * @return string
     */
    public function processUploadFile($file, string $publicDirPath = '', string $oldFileName = '', string $newFileName = '', bool $useOriginalFileName = false): string
    {
        $fileName = '';

        if (!empty(trim($oldFileName))) {
            $fileName = $oldFileName;
        }

        if (!empty($file) && $file instanceof UploadedFile) {
            $publicDirPath = $this->processPreparePath(dirPath: $publicDirPath);

            $currentFileName = time().'.'.$file->extension();

            if (!empty(trim($newFileName))) {
                $currentFileName = $newFileName.'.'.$file->extension();
            }

            if ($useOriginalFileName) {
                $currentFileName = $file->getClientOriginalName();
            }

            if (!File::exists(storage_path(path: 'app/public/'.$publicDirPath))) {
                File::makeDirectory(storage_path(path: 'app/public/'.$publicDirPath), mode: 0777, recursive: true);
            }

            // Удаление старого файла, если он был:
            if (!empty(trim($oldFileName))) {
                $this->processDeleteOldFile(oldFileName: $oldFileName, publicDirPath: $publicDirPath);
            }

            $uploadPhoto = $file->storeAs(path: 'public/'.$publicDirPath, name: $currentFileName);

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
            $publicDirPath = $this->processPreparePath(dirPath: $publicDirPath);

            // Проверка наличия файла и его удаление, если он существует:
            $oldPhotoPath = storage_path('app/public/'.$publicDirPath.'/'.$oldFileName);

            if (File::exists($oldPhotoPath)) {
                return (bool) File::delete(paths: $oldPhotoPath);
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

        $publicDirPath = $this->processPreparePath(dirPath: $publicDirPath);

        if (!empty($fileName) && File::exists(storage_path('app/public/'.$publicDirPath.'/'.$fileName))) {
            $filePath = $publicDirPath.'/'.$fileName;
        }

        return $filePath;
    }

    /**
     *  Метод формирует полный публичный путь к файлу (application base_url + file_path)
     *
     * @param string $fileName
     * @param string $publicDirPath
     * @return string
     */
    public function processGetFrontPublicFilePath(string $fileName, string $publicDirPath = ''): string
    {
        $url = '';

        $publicDirPath = $this->processPreparePath(dirPath: $publicDirPath);

        if (!empty($fileName) && File::exists(storage_path(path: 'app/public/'.$publicDirPath.'/'.$fileName))) {
            $url = asset($publicDirPath.'/'.$fileName);
        }

        return $url;
    }

    /**
     *  Метод удаляет из начала и окончания строки "/"
     *
     * @param string $dirPath
     * @return string
     */
    public function processPreparePath(string $dirPath): string
    {
        if (str_starts_with(haystack: $dirPath, needle: '/')) {
            $dirPath = substr(string: $dirPath, offset: 1);
        }

        if (str_ends_with(haystack: $dirPath, needle: '/')) {
            $dirPath = substr(string: $dirPath, offset: 0, length: -1);
        }

        return $dirPath;
    }
}
