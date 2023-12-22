<?php

namespace App\Services;

use App\DataTransferObjects\Country\CountryStoreDTO;
use App\DataTransferObjects\Country\CountryUpdateDTO;
use App\Models\Country;
use App\Repositories\Country\CountryRepository;

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

        $countryModel = Country::query()->create($formDataArray);

        if ($countryModel) {
            /** @var Country $countryModel */
            $countriesDirPath = 'images/countries/'.$countryModel->getKey();

            $formFilesNamesArray['flag_file_name'] = $fileService->processUploadFile($countryStoreDTO->flag_file, $countriesDirPath);

            return (bool) $countryModel->update($formFilesNamesArray);
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
        $countryModel = $countryRepository->getForEditModel((int) $countryUpdateDTO->id_country, true);

        if (empty($countryModel)) {
            return false;
        }

        $formDataArray = $countryUpdateDTO->getFormFieldsArray();

        /** @var Country $countryModel */
        $countriesDirPath = 'images/countries/'.$countryModel->getKey();

        $formDataArray['flag_file_name'] = $fileService->processUploadFile($countryUpdateDTO->flag_file, $countriesDirPath, $countryUpdateDTO->flag_file_name, '');

        $updateCountry = $countryModel->update($formDataArray);

        return $updateCountry;
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
        $countryModel = $countryRepository->getForEditModel((int) $id, true);

        if (!empty($countryModel)) {
            /** @var Country $countryModel */

            // Other logic...

            return (bool) $countryModel->delete();
        }

        return false;
    }
}
