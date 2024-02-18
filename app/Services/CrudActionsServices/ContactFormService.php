<?php

namespace App\Services\CrudActionsServices;

use App\DataTransferObjects\ContactForm\ContactFormStoreDTO;
use App\DataTransferObjects\ContactForm\ContactFormUpdateDTO;
use App\Models\ContactForm;
use App\Repositories\ContactForm\ContactFormRepository;

final class ContactFormService
{
    /**
     *  Создание записи для Формы Обратной Связи
     *
     * @param ContactFormStoreDTO $contactFormStoreDTO
     * @return bool
     */
    public function processStore(ContactFormStoreDTO $contactFormStoreDTO): bool
    {
        $formDataArray = $contactFormStoreDTO->getFormFieldsArray();

        $contactFormModel = ContactForm::query()->create(attributes: $formDataArray);

        if ($contactFormModel) {
            return true;
        }

        return false;
    }

    /**
     *  Обновление записи Формы Обратной Связи
     *
     * @param ContactFormUpdateDTO $contactFormUpdateDTO
     * @param ContactFormRepository $contactFormRepository
     * @return bool
     */
    public function processUpdate(ContactFormUpdateDTO $contactFormUpdateDTO, ContactFormRepository $contactFormRepository): bool
    {
        $contactFormModel = $contactFormRepository->getForEditModel(id: (int) $contactFormUpdateDTO->idContactForm, useCache: true);

        if (empty($contactFormModel)) {
            return false;
        }

        $formDataArray = $contactFormUpdateDTO->getFormFieldsArray();

        $updateContactForm = $contactFormModel->update(attributes: $formDataArray);

        return (bool) $updateContactForm;
    }

    /**
     *  Частичное удаление записи Формы Обратной Связи (soft delete)
     *
     * @param $id
     * @param ContactFormRepository $contactFormRepository
     * @return bool
     */
    public function processDestroy($id, ContactFormRepository $contactFormRepository): bool
    {
        $contactFormModel = $contactFormRepository->getForEditModel(id: (int) $id, useCache: true);

        if (!empty($contactFormModel)) {
            /** @var ContactForm $contactFormModel */

            // Other logic...

            return (bool) $contactFormModel->delete();
        }

        return false;
    }
}
