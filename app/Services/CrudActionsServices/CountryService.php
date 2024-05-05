<?php

namespace App\Services\CrudActionsServices;

use App\DataTransferObjects\Country\CountryStoreDTO;
use App\DataTransferObjects\Country\CountryUpdateDTO;
use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Models\Country;
use App\Repositories\CoreRepository;
use App\Services\FileService;

final readonly class CountryService implements CoreCrudActionsInterface
{
    /**
     * @var FileService
     */
    private FileService $fileService;

    public function __construct()
    {
        $this->fileService = new FileService();
    }

    /**
     *  Создание записи о Стране
     *
     * @param FormFieldsDtoInterface $dto
     * @return bool
     */
    public function processStore(FormFieldsDtoInterface $dto): bool
    {
        /** @var CountryStoreDTO $dto */

        $countryModel = Country::query()->create(attributes: $dto->getFormFieldsArray());

        if ($countryModel) {
            /** @var Country $countryModel */
            $countriesDirPath = 'images/countries/'.$countryModel->getKey();

            $formFilesNamesArray['flag_file_name'] = $this->fileService->processUploadFile(file: $dto->flag_file, publicDirPath: $countriesDirPath);

            return (bool) $countryModel->update(attributes: $formFilesNamesArray);
        }

        return false;
    }

    /**
     *  Обновление записи о Стране
     *
     * @param FormFieldsDtoInterface $dto
     * @param CoreRepository $repository
     * @return bool
     */
    public function processUpdate(FormFieldsDtoInterface $dto, CoreRepository $repository): bool
    {
        /** @var CountryUpdateDTO $dto */

        $countryModel = $repository->getForEditModel(id: (int) $dto->id_country, useCache: true);

        if (empty($countryModel)) {
            return false;
        }

        $formDataArray = $dto->getFormFieldsArray();

        /** @var Country $countryModel */
        $countriesDirPath = 'images/countries/'.$countryModel->getKey();

        $formDataArray['flag_file_name'] = $this->fileService->processUploadFile(file: $dto->flag_file, publicDirPath: $countriesDirPath, oldFileName: $dto->flag_file_name, newFileName: '');

        $updateCountry = $countryModel->update(attributes: $formDataArray);

        return (bool) $updateCountry;
    }

    /**
     *  Полное удаление записи о Стране
     *
     * @param $id
     * @param CoreRepository $repository
     * @return bool
     */
    public function processDestroy($id, CoreRepository $repository): bool
    {
        $countryModel = $repository->getForEditModel(id: (int) $id, useCache: true);

        if (!empty($countryModel)) {
            /** @var Country $countryModel */

            // Other logic...

            return (bool) $countryModel->delete();
        }

        return false;
    }
}
