<?php

namespace App\Services\CrudActionsServices;

use App\DataTransferObjects\Setting\SettingStoreDTO;
use App\DataTransferObjects\Setting\SettingUpdateDTO;
use App\Models\Setting;
use App\Repositories\Setting\SettingRepository;

final class SettingService
{
    /**
     *  Создание записи о Настройке
     *
     * @param SettingStoreDTO $settingStoreDTO
     * @return bool
     */
    public function processStore(SettingStoreDTO $settingStoreDTO): bool
    {
        $formDataArray = $settingStoreDTO->getFormFieldsArray();

        $settingModel = Setting::query()->create(attributes: $formDataArray);

        return (bool) $settingModel;
    }

    /**
     *  Обновление записи о Настройке
     *
     * @param SettingUpdateDTO $settingUpdateDTO
     * @param SettingRepository $settingRepository
     * @return bool
     */
    public function processUpdate(SettingUpdateDTO $settingUpdateDTO, SettingRepository $settingRepository): bool
    {
        $settingModel = $settingRepository->getForEditModel(id: (int) $settingUpdateDTO->id_setting, useCache: true);

        if (empty($settingModel)) {
            return false;
        }

        $formDataArray = $settingUpdateDTO->getFormFieldsArray();

        /** @var Setting $settingModel */

        $updateSetting = $settingModel->update(attributes: $formDataArray);

        return (bool) $updateSetting;
    }

    /**
     *  Полное удаление записи о Настройке
     *
     * @param $id
     * @param SettingRepository $settingRepository
     * @return bool
     */
    public function processDestroy($id, SettingRepository $settingRepository): bool
    {
        $settingModel = $settingRepository->getForEditModel(id: (int) $id, useCache: true);

        if (!empty($settingModel)) {
            /** @var Setting $settingModel */

            // Other logic...

            return (bool) $settingModel->delete();
        }

        return false;
    }
}
