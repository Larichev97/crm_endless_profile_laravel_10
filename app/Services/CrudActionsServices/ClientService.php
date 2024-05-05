<?php

namespace App\Services\CrudActionsServices;

use App\DataTransferObjects\Client\ClientStoreDTO;
use App\DataTransferObjects\Client\ClientUpdateDTO;
use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Enums\ContactFormStatusEnum;
use App\Models\Client;
use App\Models\ContactForm;
use App\Repositories\ContactForm\ContactFormRepository;
use App\Repositories\CoreRepository;
use App\Services\FileService;
use Illuminate\Http\Request;

final readonly class ClientService implements CoreCrudActionsInterface
{
    /**
     * @var FileService
     */
    private FileService $fileService;

    /**
     * @var ContactFormRepository
     */
    private ContactFormRepository $contactFormRepository;

    public function __construct()
    {
        $this->fileService = new FileService();
        $this->contactFormRepository = new ContactFormRepository();
    }

    /**
     *  Создание записи о клиенте
     *
     * @param FormFieldsDtoInterface $dto
     * @return bool
     */
    public function processStore(FormFieldsDtoInterface $dto): bool
    {
        /** @var ClientStoreDTO $dto */

        $clientModel = Client::query()->create(attributes: $dto->getFormFieldsArray());

        if ($clientModel) {
            $this->processCompletionContactForm(idContactForm: $dto->id_contact_form, idEmployee: $dto->id_user_add);

            $formFilesNamesArray['photo_name'] = $this->fileService->processUploadFile(file: $dto->image, publicDirPath: 'images/clients', oldFileName: '', newFileName: '');

            return (bool) $clientModel->update(attributes: $formFilesNamesArray);
        }

        return false;
    }

    /**
     *  Обновление записи о клиенте
     *
     * @param FormFieldsDtoInterface $dto
     * @param CoreRepository $repository
     * @return bool
     */
    public function processUpdate(FormFieldsDtoInterface $dto, CoreRepository $repository): bool
    {
        /** @var ClientUpdateDTO $dto */

        $clientModel = $repository->getForEditModel(id: (int) $dto->id_client, useCache: true);

        if (empty($clientModel)) {
            return false;
        }

        /** @var Client $clientModel */

        $formDataArray = $dto->getFormFieldsArray();

        $formDataArray['photo_name'] = $this->fileService->processUploadFile(file: $dto->image, publicDirPath: 'images/clients', oldFileName: $dto->photo_name);

        $updateClient = $clientModel->update(attributes: $formDataArray);

        return (bool) $updateClient;
    }

    /**
     *  Скрытие записи о Клиенте ("deleted_at" в таблице)
     *
     * @param $id
     * @param CoreRepository $repository
     * @return bool
     */
    public function processDestroy($id, CoreRepository $repository): bool
    {
        $clientModel = $repository->getForEditModel(id: (int) $id, useCache: true);

        if (!empty($clientModel)) {
            /** @var Client $clientModel */

            // Other logic...

            return (bool) $clientModel->delete();
        }

        return false;
    }

    /**
     *  Формирование массива с полями о будущем Клиенте из формы "Обратной связи"
     *
     * @param Request $request
     * @return array
     */
    public function processGetContactFormData(Request $request): array
    {
        $contactFormData = [];

        // Сопадающие поля из формы "Обратной связи" для упрощения создания нового Клиента:
        $clientFields = ['id_contact_form', 'firstname', 'lastname', 'email', 'phone_number'];

        foreach ($clientFields as $keyClientField => $clientFieldName) {
            $currentFieldValue = $request->get(key: $clientFieldName, default: '');

            if (!empty($currentFieldValue)) {
                $contactFormData[$clientFieldName] = $currentFieldValue;
            }
        }

        return $contactFormData;
    }

    /**
     *  Закрытие формы "Обратной связи" конкретным сотрудником (преобразование в клиента)
     *
     * @param int $idContactForm
     * @param int $idEmployee
     * @return bool
     */
    public function processCompletionContactForm(int $idContactForm, int $idEmployee): bool
    {
        if ($idContactForm > 0) {
            $contactFormModel = $this->contactFormRepository->getForEditModel(id: $idContactForm);

            if (!empty($contactFormModel) && is_object($contactFormModel)) {
                /** @var ContactForm $contactFormModel */

                $contactFormDataArray = [
                    'id_status' => ContactFormStatusEnum::READY->value,
                    'id_employee' => $idEmployee,
                ];

                return $contactFormModel->update(attributes: $contactFormDataArray);
            }
        }

        return false;
    }
}
