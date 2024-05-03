<?php

namespace App\Services\CrudActionsServices;

use App\DataTransferObjects\City\CityStoreDTO;
use App\DataTransferObjects\City\CityUpdateDTO;
use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Models\City;
use App\Repositories\CoreRepository;

final readonly class CityService implements CoreCrudActionsInterface
{
    /**
     *  Создание записи о Городе
     *
     * @param FormFieldsDtoInterface $dto
     * @return bool
     */
    public function processStore(FormFieldsDtoInterface $dto): bool
    {
        /** @var CityStoreDTO $dto */
        $cityModel = City::query()->create(attributes: $dto->getFormFieldsArray());

        return (bool) $cityModel;
    }

    /**
     *  Обновление записи о Городе
     *
     * @param FormFieldsDtoInterface $dto
     * @param CoreRepository $repository
     * @return bool
     */
    public function processUpdate(FormFieldsDtoInterface $dto, CoreRepository $repository): bool
    {
        /** @var CityUpdateDTO $dto */

        $cityModel = $repository->getForEditModel(id: (int) $dto->id_city, useCache: true);

        if (empty($cityModel)) {
            return false;
        }

        /** @var City $cityModel */

        $updateCity = $cityModel->update(attributes: $dto->getFormFieldsArray());

        return (bool) $updateCity;
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
        $cityModel = $repository->getForEditModel(id: (int) $id, useCache: true);

        if (!empty($cityModel)) {
            /** @var City $cityModel */

            // Other logic...

            return (bool) $cityModel->delete();
        }

        return false;
    }
}
