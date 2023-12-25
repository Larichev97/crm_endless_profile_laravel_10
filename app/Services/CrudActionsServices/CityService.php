<?php

namespace App\Services\CrudActionsServices;

use App\DataTransferObjects\City\CityStoreDTO;
use App\DataTransferObjects\City\CityUpdateDTO;
use App\Models\City;
use App\Repositories\City\CityRepository;

final class CityService
{
    /**
     *  Создание записи о Городе
     *
     * @param CityStoreDTO $cityStoreDTO
     * @return bool
     */
    public function processStore(CityStoreDTO $cityStoreDTO): bool
    {
        $formDataArray = $cityStoreDTO->getFormFieldsArray();

        $cityModel = City::query()->create($formDataArray);

        return (bool) $cityModel;
    }

    /**
     *  Обновление записи о Городе
     *
     * @param CityUpdateDTO $cityUpdateDTO
     * @param CityRepository $cityRepository
     * @return bool
     */
    public function processUpdate(CityUpdateDTO $cityUpdateDTO, CityRepository $cityRepository): bool
    {
        $cityModel = $cityRepository->getForEditModel((int) $cityUpdateDTO->id_city, true);

        if (empty($cityModel)) {
            return false;
        }

        $formDataArray = $cityUpdateDTO->getFormFieldsArray();

        /** @var City $cityModel */

        $updateCity = $cityModel->update($formDataArray);

        return $updateCity;
    }

    /**
     *  Полное удаление записи о Стране
     *
     * @param $id
     * @param CityRepository $cityRepository
     * @return bool
     */
    public function processDestroy($id, CityRepository $cityRepository): bool
    {
        $cityModel = $cityRepository->getForEditModel((int) $id, true);

        if (!empty($cityModel)) {
            /** @var City $cityModel */

            // Other logic...

            return (bool) $cityModel->delete();
        }

        return false;
    }
}
