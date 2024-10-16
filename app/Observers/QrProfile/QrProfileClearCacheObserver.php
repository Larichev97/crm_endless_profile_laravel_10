<?php

namespace App\Observers\QrProfile;

use App\Models\QrProfile;
use Illuminate\Support\Facades\Cache;

class QrProfileClearCacheObserver
{
    /**
     *  Событие создания записи Модели
     *
     * @param QrProfile $qrProfileModel
     * @return void
     */
    public function created(QrProfile $qrProfileModel): void
    {
        Cache::forget($qrProfileModel::class.'-getModelCollection');
        Cache::forget($qrProfileModel::class.'-getForDropdownList');
    }

    /**
     *  Событие изменения записи Модели
     *
     * @param QrProfile $qrProfileModel
     * @return void
     */
    public function updated(QrProfile $qrProfileModel): void
    {
        Cache::forget($qrProfileModel::class.'-getModelCollection');
        Cache::forget($qrProfileModel::class.'-getForEditModel-'.$qrProfileModel->getKey());
        Cache::forget($qrProfileModel::class.'-getForDropdownList');
    }

    /**
     *  Событие удаления записи Модели
     *
     * @param QrProfile $qrProfileModel
     * @return void
     */
    public function deleted(QrProfile $qrProfileModel): void
    {
        Cache::forget($qrProfileModel::class.'-getModelCollection');
        Cache::forget($qrProfileModel::class.'-getForEditModel-'.$qrProfileModel->getKey());
        Cache::forget($qrProfileModel::class.'-getForDropdownList');
    }
}
