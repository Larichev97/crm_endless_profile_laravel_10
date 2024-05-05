<?php

namespace App\Observers\User;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserClearCacheObserver
{
    /**
     *  Событие создания записи Модели
     *
     * @param User $userModel
     * @return void
     */
    public function created(User $userModel): void
    {
        Cache::forget($userModel::class.'-getModelCollection');
        Cache::forget($userModel::class.'-getForDropdownList');
    }

    /**
     *  Событие изменения записи Модели
     *
     * @param User $userModel
     * @return void
     */
    public function updated(User $userModel): void
    {
        Cache::forget($userModel::class.'-getModelCollection');
        Cache::forget($userModel::class.'-getForEditModel-'.$userModel->getKey());
        Cache::forget($userModel::class.'-getForDropdownList');
    }

    /**
     *  Событие удаления записи Модели
     *
     * @param User $userModel
     * @return void
     */
    public function deleted(User $userModel): void
    {
        Cache::forget($userModel::class.'-getModelCollection');
        Cache::forget($userModel::class.'-getForEditModel-'.$userModel->getKey());
        Cache::forget($userModel::class.'-getForDropdownList');
    }
}
