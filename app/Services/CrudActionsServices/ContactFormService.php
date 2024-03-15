<?php

namespace App\Services\CrudActionsServices;

use App\DataTransferObjects\ContactForm\ContactFormStoreDTO;
use App\DataTransferObjects\ContactForm\ContactFormUpdateDTO;
use App\Models\ContactForm;
use App\Repositories\ContactForm\ContactFormRepository;
use App\Repositories\Setting\SettingRepository;
use Carbon\Carbon;
use DefStudio\Telegraph\Models\TelegraphChat;

final class ContactFormService
{
    /**
     *  Создание записи для Формы Обратной Связи
     *
     * @param ContactFormStoreDTO $contactFormStoreDTO
     * @param SettingRepository $settingRepository
     * @return bool
     */
    public function processStore(ContactFormStoreDTO $contactFormStoreDTO, SettingRepository $settingRepository): bool
    {
        $formDataArray = $contactFormStoreDTO->getFormFieldsArray();

        $contactFormModel = ContactForm::query()->create(attributes: $formDataArray);

        if ($contactFormModel) {
            $endlessProfileChannelId = $settingRepository->getSettingValueByName(name: 'TELEGRAM_CHANNEL_ID', useCache: true);

            // Отправка уведомления в Телеграм канал только из боевого окружения:
            if (env('APP_ENV') == 'production' && !empty($endlessProfileChannelId) && class_exists('DefStudio\Telegraph\Models\TelegraphChat')) {
                /** @var TelegraphChat $channelEndlessProfile */
                $channelEndlessProfile = TelegraphChat::query()->where('chat_id', '=', $endlessProfileChannelId)->first();

                if ($channelEndlessProfile) {
                    /** @var ContactForm $contactFormModel */
                    $htmlMessage = $this->processBuildTelegramHtmlMessage($contactFormModel);

                    $channelEndlessProfile->message($htmlMessage)->send();
                }
            }

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

    /**
     *  Отправка сообщения с данными о созданной Форме обратный связи в телеграм-канал через бота-администратора
     *
     * @param ContactForm $contactFormModel
     * @return void
     */
    public function processSendContactFormInTelegramChannel(ContactForm $contactFormModel): void
    {
        $settingRepository = new SettingRepository();

        $endlessProfileChannelId = (string) $settingRepository->getSettingValueByName(name: 'TELEGRAM_CHANNEL_ID', useCache: true);

        if (!empty($endlessProfileChannelId)) {
            $htmlMessage = $this->processBuildTelegramHtmlMessage($contactFormModel);

            /** @var TelegraphChat $channelEndlessProfile */
            $channelEndlessProfile = TelegraphChat::query()->where('chat_id', '=', $endlessProfileChannelId)->first();
            $channelEndlessProfile?->message($htmlMessage)->send();
        }
    }

    /**
     *  Формирование сообщения для отправки Ботом в канал
     *
     * @param ContactForm $contactFormModel
     * @return string
     */
    public function processBuildTelegramHtmlMessage(ContactForm $contactFormModel): string
    {
        $contactFormId = (int) $contactFormModel->getKey();

        $message = "\n<b><a href=\"".route('contact-forms.show', $contactFormId)."\">Новая заявка #".$contactFormId."</a></b>\n\n";

        if (!empty($contactFormModel->phone_number)) {
            $message .= "Номер телефона: ". $contactFormModel->phone_number."\n\n";
        }

        if (!empty($contactFormModel->email)) {
            $message .= "Email: ". $contactFormModel->email."\n\n";
        }

        if (!empty($contactFormModel->firstname)) {
            $message .= "Имя : ". $contactFormModel->firstname."\n\n";
        }

        if (!empty($contactFormModel->lastname)) {
            $message .= "Фамилия : ". $contactFormModel->lastname."\n\n";
        }

        $comment = strip_tags(trim($contactFormModel->comment));

        if (!empty($comment)) {
            $message .= "Комментарий : ". $comment."\n\n";
        }

        if (!empty($contactFormModel->created_at)) {
            $message .= "Дата создания: ". Carbon::parse($contactFormModel->created_at)->format('d.m.Y H:i:s');
        }

        return $message;
    }
}
