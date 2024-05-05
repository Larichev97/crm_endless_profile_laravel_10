<?php

namespace App\Observers\ContactForm;

use App\Models\ContactForm;
use Illuminate\Support\Facades\Cache;

class ContactFormClearCacheObserver
{
    /**
     *  Событие создания записи Модели
     *
     * @param ContactForm $contactFormModel
     * @return void
     */
    public function created(ContactForm $contactFormModel): void
    {
        Cache::forget($contactFormModel::class.'-getModelCollection');
        Cache::forget($contactFormModel::class.'-getForDropdownList');
    }

    /**
     *  Событие изменения записи Модели
     *
     * @param ContactForm $contactFormModel
     * @return void
     */
    public function updated(ContactForm $contactFormModel): void
    {
        Cache::forget($contactFormModel::class.'-getModelCollection');
        Cache::forget($contactFormModel::class.'-getForEditModel-'.$contactFormModel->getKey());
        Cache::forget($contactFormModel::class.'-getForDropdownList');
    }

    /**
     *  Событие удаления записи Модели
     *
     * @param ContactForm $contactFormModel
     * @return void
     */
    public function deleted(ContactForm $contactFormModel): void
    {
        Cache::forget($contactFormModel::class.'-getModelCollection');
        Cache::forget($contactFormModel::class.'-getForEditModel-'.$contactFormModel->getKey());
        Cache::forget($contactFormModel::class.'-getForDropdownList');
    }
}
