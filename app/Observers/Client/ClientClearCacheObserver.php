<?php

namespace App\Observers\Client;

use App\Models\Client;
use Illuminate\Support\Facades\Cache;

class ClientClearCacheObserver
{
    /**
     *  Событие создания записи Модели
     *
     * @param Client $clientModel
     * @return void
     */
    public function created(Client $clientModel): void
    {
        Cache::forget($clientModel::class.'-getModelCollection');
        Cache::forget($clientModel::class.'-getForDropdownList');

        if (Cache::supportsTags()) {
            Cache::tags($clientModel::class.'-getAllWithPaginate')->flush();
        }
    }

    /**
     *  Событие изменения записи Модели
     *
     * @param Client $clientModel
     * @return void
     */
    public function updated(Client $clientModel): void
    {
        Cache::forget($clientModel::class.'-getModelCollection');
        Cache::forget($clientModel::class.'-getForEditModel-'.$clientModel->getKey());
        Cache::forget($clientModel::class.'-getForDropdownList');

        if (Cache::supportsTags()) {
            Cache::tags($clientModel::class.'-getAllWithPaginate')->flush();
        }
    }

    /**
     *  Событие удаления записи Модели
     *
     * @param Client $clientModel
     * @return void
     */
    public function deleted(Client $clientModel): void
    {
        Cache::forget($clientModel::class.'-getModelCollection');
        Cache::forget($clientModel::class.'-getForEditModel-'.$clientModel->getKey());
        Cache::forget($clientModel::class.'-getForDropdownList');

        if (Cache::supportsTags()) {
            Cache::tags($clientModel::class.'-getAllWithPaginate')->flush();
        }
    }
}
