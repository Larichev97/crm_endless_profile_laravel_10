<?php

namespace App\Observers\QrProfile;

use App\Models\QrProfileImage;
use Illuminate\Support\Facades\Cache;

class QrProfileImageGalleryClearCacheObserver
{
    /**
     *  Событие удаления записи Модели
     *
     * @param QrProfileImage $qrProfileImageModel
     * @return void
     */
    public function deleted(QrProfileImage $qrProfileImageModel): void
    {
        Cache::forget($qrProfileImageModel::class.'-getModelCollection');
        Cache::forget($qrProfileImageModel::class.'-getForEditModel-'.$qrProfileImageModel->getKey());
        Cache::forget($qrProfileImageModel::class.'-getForDropdownList');
    }
}
