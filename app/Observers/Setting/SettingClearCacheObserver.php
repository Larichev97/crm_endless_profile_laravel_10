<?php

namespace App\Observers\Setting;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingClearCacheObserver
{
    /**
     *  Событие создания записи Модели
     *
     * @param Setting $settingModel
     * @return void
     */
    public function created(Setting $settingModel): void
    {
        Cache::forget($settingModel::class.'-getModelCollection');
        Cache::forget($settingModel::class.'-getForDropdownList');
    }

    /**
     *  Событие изменения записи Модели
     *
     * @param Setting $settingModel
     * @return void
     */
    public function updated(Setting $settingModel): void
    {
        Cache::forget('setting-name-'.strtolower($settingModel->name));
        Cache::forget($settingModel::class.'-getModelCollection');
        Cache::forget($settingModel::class.'-getForEditModel-'.$settingModel->getKey());
        Cache::forget($settingModel::class.'-getForDropdownList');
    }

    /**
     *  Событие удаления записи Модели
     *
     * @param Setting $settingModel
     * @return void
     */
    public function deleted(Setting $settingModel): void
    {
        Cache::forget('setting-name-'.strtolower($settingModel->name));
        Cache::forget($settingModel::class.'-getModelCollection');
        Cache::forget($settingModel::class.'-getForEditModel-'.$settingModel->getKey());
        Cache::forget($settingModel::class.'-getForDropdownList');
    }
}
