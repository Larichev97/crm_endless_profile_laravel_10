<?php

namespace App\Observers\Country;

use App\Models\Country;
use Illuminate\Support\Facades\Cache;

class CountryClearCacheObserver
{
    /**
     *  Событие создания записи Модели
     *
     * @param Country $countryModel
     * @return void
     */
    public function created(Country $countryModel): void
    {
        Cache::forget($countryModel::class.'-getModelCollection');
        Cache::forget($countryModel::class.'-getForDropdownList');

        if (Cache::supportsTags()) {
            Cache::tags($countryModel::class.'-getAllWithPaginate')->flush();
        }
    }

    /**
     *  Событие изменения записи Модели
     *
     * @param Country $countryModel
     * @return void
     */
    public function updated(Country $countryModel): void
    {
        Cache::forget($countryModel::class.'-getModelCollection');
        Cache::forget($countryModel::class.'-getForEditModel-'.$countryModel->getKey());
        Cache::forget($countryModel::class.'-getForDropdownList');

        if (Cache::supportsTags()) {
            Cache::tags($countryModel::class.'-getAllWithPaginate')->flush();
        }
    }

    /**
     *  Событие удаления записи Модели
     *
     * @param Country $countryModel
     * @return void
     */
    public function deleted(Country $countryModel): void
    {
        Cache::forget($countryModel::class.'-getModelCollection');
        Cache::forget($countryModel::class.'-getForEditModel-'.$countryModel->getKey());
        Cache::forget($countryModel::class.'-getForDropdownList');

        if (Cache::supportsTags()) {
            Cache::tags($countryModel::class.'-getAllWithPaginate')->flush();
        }
    }
}
