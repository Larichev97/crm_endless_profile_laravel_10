<?php

namespace App\Observers\City;

use App\Models\City;
use Illuminate\Support\Facades\Cache;

class CityClearCacheObserver
{
    /**
     *  Событие создания записи Модели
     *
     * @param City $cityModel
     * @return void
     */
    public function created(City $cityModel): void
    {
        Cache::forget($cityModel::class.'-getModelCollection');
        Cache::forget($cityModel::class.'-getForDropdownList');

        if (Cache::supportsTags()) {
            Cache::tags($cityModel::class.'-getAllWithPaginate')->flush();
        }
    }

    /**
     *  Событие изменения записи Модели
     *
     * @param City $cityModel
     * @return void
     */
    public function updated(City $cityModel): void
    {
        Cache::forget($cityModel::class.'-getModelCollection');
        Cache::forget($cityModel::class.'-getForEditModel-'.$cityModel->getKey());
        Cache::forget($cityModel::class.'-getForDropdownList');

        if (Cache::supportsTags()) {
            Cache::tags($cityModel::class.'-getAllWithPaginate')->flush();
        }
    }

    /**
     *  Событие удаления записи Модели
     *
     * @param City $cityModel
     * @return void
     */
    public function deleted(City $cityModel): void
    {
        Cache::forget($cityModel::class.'-getModelCollection');
        Cache::forget($cityModel::class.'-getForEditModel-'.$cityModel->getKey());
        Cache::forget($cityModel::class.'-getForDropdownList');

        if (Cache::supportsTags()) {
            Cache::tags($cityModel::class.'-getAllWithPaginate')->flush();
        }
    }
}
