<?php

namespace App\Services\CrudActionsServices;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Models\ContactForm;
use App\Repositories\CoreRepository;
use App\Repositories\Setting\SettingRepository;
use Carbon\Carbon;
use DefStudio\Telegraph\Models\TelegraphChat;

final readonly class ContactFormService implements CoreCrudActionsInterface
{
    /**
     * @param SettingRepository $settingRepository
     */
    public function __construct(
        private SettingRepository $settingRepository
    )
    {
    }

    /**
     *  Создание записи для Формы Обратной Связи
     *
     * @param FormFieldsDtoInterface $dto
     * @return bool
     */
    public function processStore(FormFieldsDtoInterface $dto): bool
    {
        $contactFormModel = ContactForm::query()->create(attributes: $dto->getFormFieldsArray());

        if ($contactFormModel) {
            $endlessProfileChannelId = $this->settingRepository->getSettingValueByName(name: 'TELEGRAM_CHANNEL_ID', useCache: true);

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
     * @param FormFieldsDtoInterface $dto
     * @param CoreRepository $repository
     * @return bool
     */
    public function processUpdate(FormFieldsDtoInterface $dto, CoreRepository $repository): bool
    {
        $contactFormModel = $repository->getForEditModel(id: (int) $dto->idContactForm, useCache: true);

        if (empty($contactFormModel)) {
            return false;
        }

        $updateContactForm = $contactFormModel->update(attributes: $dto->getFormFieldsArray());

        return (bool) $updateContactForm;
    }

    /**
     *  Частичное удаление записи Формы Обратной Связи (soft delete)
     *
     * @param $id
     * @param CoreRepository $repository
     * @return bool
     */
    public function processDestroy($id, CoreRepository $repository): bool
    {
        $contactFormModel = $repository->getForEditModel(id: (int) $id, useCache: true);

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
        $endlessProfileChannelId = (string) $this->settingRepository->getSettingValueByName(name: 'TELEGRAM_CHANNEL_ID', useCache: true);

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

        $message = "\n<b><a href=\"".route('admin.contact-forms.show', $contactFormId)."\">Новая заявка #".$contactFormId."</a></b>\n\n";

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
