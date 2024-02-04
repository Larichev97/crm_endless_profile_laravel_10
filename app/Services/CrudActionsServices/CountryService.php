<?php

namespace App\Services\CrudActionsServices;

use App\DataTransferObjects\Country\CountryStoreDTO;
use App\DataTransferObjects\Country\CountryUpdateDTO;
use App\Models\Country;
use App\Repositories\Country\CountryRepository;
use App\Services\FileService;

final class CountryService
{
    /**
     *  Создание записи о Стране
     *
     * @param CountryStoreDTO $countryStoreDTO
     * @param FileService $fileService
     * @return bool
     */
    public function processStore(CountryStoreDTO $countryStoreDTO, FileService $fileService): bool
    {
        $formDataArray = $countryStoreDTO->getFormFieldsArray();

        $countryModel = Country::query()->create(attributes: $formDataArray);

        if ($countryModel) {
            /** @var Country $countryModel */
            $countriesDirPath = 'images/countries/'.$countryModel->getKey();

            $formFilesNamesArray['flag_file_name'] = $fileService->processUploadFile(file: $countryStoreDTO->flag_file, publicDirPath: $countriesDirPath);

            return (bool) $countryModel->update(attributes: $formFilesNamesArray);
        }

        return false;
    }

    /**
     *  Обновление записи о Стране
     *
     * @param CountryUpdateDTO $countryUpdateDTO
     * @param FileService $fileService
     * @param CountryRepository $countryRepository
     * @return bool
     */
    public function processUpdate(CountryUpdateDTO $countryUpdateDTO, FileService $fileService, CountryRepository $countryRepository): bool
    {
        $countryModel = $countryRepository->getForEditModel(id: (int) $countryUpdateDTO->id_country, useCache: true);

        if (empty($countryModel)) {
            return false;
        }

        $formDataArray = $countryUpdateDTO->getFormFieldsArray();

        /** @var Country $countryModel */
        $countriesDirPath = 'images/countries/'.$countryModel->getKey();

        $formDataArray['flag_file_name'] = $fileService->processUploadFile(file: $countryUpdateDTO->flag_file, publicDirPath: $countriesDirPath, oldFileName: $countryUpdateDTO->flag_file_name, newFileName: '');

        $updateCountry = $countryModel->update(attributes: $formDataArray);

        return (bool) $updateCountry;
    }

    /**
     *  Полное удаление записи о Стране
     *
     * @param $id
     * @param CountryRepository $countryRepository
     * @return bool
     */
    public function processDestroy($id, CountryRepository $countryRepository): bool
    {
        $countryModel = $countryRepository->getForEditModel(id: (int) $id, useCache: true);

        if (!empty($countryModel)) {
            /** @var Country $countryModel */

            // Other logic...

            return (bool) $countryModel->delete();
        }

        return false;
    }
}
