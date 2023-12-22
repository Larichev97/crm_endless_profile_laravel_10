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

        if (Cache::supportsTags()) {
            Cache::tags($countryModel::class.'-getAllWithPaginate')->flush();
            Cache::tags($countryModel::class.'-getForDropdownList')->flush();
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

        if (Cache::supportsTags()) {
            Cache::tags($countryModel::class.'-getAllWithPaginate')->flush();
            Cache::tags($countryModel::class.'-getForDropdownList')->flush();
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

        if (Cache::supportsTags()) {
            Cache::tags($countryModel::class.'-getAllWithPaginate')->flush();
            Cache::tags($countryModel::class.'-getForDropdownList')->flush();
        }
    }
}
