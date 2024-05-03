<?php

namespace App\Services\CrudActionsServices;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\DataTransferObjects\Setting\SettingStoreDTO;
use App\DataTransferObjects\Setting\SettingUpdateDTO;
use App\Models\Setting;
use App\Repositories\CoreRepository;

final readonly class SettingService implements CoreCrudActionsInterface
{
    /**
     *  Создание записи о Настройке
     *
     * @param FormFieldsDtoInterface $dto
     * @return bool
     */
    public function processStore(FormFieldsDtoInterface $dto): bool
    {
        /** @var SettingStoreDTO $dto */

        $settingModel = Setting::query()->create(attributes: $dto->getFormFieldsArray());

        return (bool) $settingModel;
    }

    /**
     *  Обновление записи о Настройке
     *
     * @param FormFieldsDtoInterface $dto
     * @param CoreRepository $repository
     * @return bool
     */
    public function processUpdate(FormFieldsDtoInterface $dto, CoreRepository $repository): bool
    {
        /** @var SettingUpdateDTO $dto */

        $settingModel = $repository->getForEditModel(id: (int) $dto->id_setting, useCache: true);

        if (empty($settingModel)) {
            return false;
        }

        /** @var Setting $settingModel */

        $updateSetting = $settingModel->update(attributes: $dto->getFormFieldsArray());

        return (bool) $updateSetting;
    }

    /**
     *  Полное удаление записи о Настройке
     *
     * @param $id
     * @param CoreRepository $repository
     * @return bool
     */
    public function processDestroy($id, CoreRepository $repository): bool
    {
        $settingModel = $repository->getForEditModel(id: (int) $id, useCache: true);

        if (!empty($settingModel)) {
            /** @var Setting $settingModel */

            // Other logic...

            return (bool) $settingModel->delete();
        }

        return false;
    }
}
