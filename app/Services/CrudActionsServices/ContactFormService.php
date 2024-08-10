<?php

namespace App\Services\CrudActionsServices;

use App\DataTransferObjects\ContactForm\ContactFormStoreDTO;
use App\DataTransferObjects\ContactForm\ContactFormUpdateDTO;
use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Models\ContactForm;
use App\Repositories\CoreRepository;
use App\Repositories\Setting\SettingRepository;
use App\Services\RabbitMQ\RabbitMQPublisher;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Support\Facades\Pipeline;

final readonly class ContactFormService implements CoreCrudActionsInterface
{
    /**
     * @var SettingRepository
     */
    private SettingRepository $settingRepository;

    /**
     * @var RabbitMQPublisher
     */
    private RabbitMQPublisher $rabbitMQPublisher;

    public function __construct()
    {
        $this->settingRepository = new SettingRepository();
        $this->rabbitMQPublisher = new RabbitMQPublisher();
    }

    /**
     *  Создание записи для Формы Обратной Связи
     *
     * @param FormFieldsDtoInterface $dto
     * @return bool
     */
    public function processStore(FormFieldsDtoInterface $dto): bool
    {
        /** @var ContactFormStoreDTO $dto */

        $contactFormModel = ContactForm::query()->create(attributes: $dto->getFormFieldsArray());

        if ($contactFormModel instanceof ContactForm) {
            // Usage via RabbitMQ queues:
            if ($contactFormModel->id > 0) {
                $this->rabbitMQPublisher->publishTelegramMessage((string) $contactFormModel->id);
            }

            /* // Default usage:
            $this->processSendContactFormInTelegramChannel($contactFormModel);
            */

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
        /** @var ContactFormUpdateDTO $dto */

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
        $endlessProfileChannelId = $this->settingRepository->getSettingValueByName(name: 'TELEGRAM_CHANNEL_ID', useCache: true);

        if (
            env('APP_ENV') == 'production' &&
            !empty($endlessProfileChannelId) &&
            class_exists('DefStudio\Telegraph\Models\TelegraphChat')
        ) {
            $channelEndlessProfile = TelegraphChat::query()->where('chat_id', '=', $endlessProfileChannelId)->first();

            if ($channelEndlessProfile) {
                /** @var TelegraphChat $channelEndlessProfile */

                $htmlMessage = $this->processBuildTelegramHtmlMessage($contactFormModel);

                if (!empty($htmlMessage)) {
                    $channelEndlessProfile->message($htmlMessage)->send();
                }
            }
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
        $data = [
            'contact_form_object' => $contactFormModel,
            'message' => '',
        ];

        return Pipeline::send($data)
            ->through([
                \App\Services\Telegram\ContactFormMessagePipes\TitlePipe::class,
                \App\Services\Telegram\ContactFormMessagePipes\ClientPhoneNumberPipe::class,
                \App\Services\Telegram\ContactFormMessagePipes\ClientEmailPipe::class,
                \App\Services\Telegram\ContactFormMessagePipes\ClientNamePipe::class,
                \App\Services\Telegram\ContactFormMessagePipes\ClientCommentPipe::class,
                \App\Services\Telegram\ContactFormMessagePipes\DateCreatePipe::class,
            ])
            ->then(function (array $data) {
                $message = $data['message'];

                if (is_string($message) && !empty($message)) {
                    return $message;
                }

                return '';
            })
        ;
    }
}
