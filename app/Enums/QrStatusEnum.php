<?php

namespace App\Enums;

enum QrStatusEnum: int
{
    case NEW = 1;
    case IN_PROCESS = 2;
    case READY = 3;
    case RECEIVED = 4;
    case LOST = 5;
    case WRONG_URL = 6;

    /**
     * @return string
     */
    public function getStatusName(): string
    {
        return match($this) {
            QrStatusEnum::NEW => 'Новый',
            QrStatusEnum::IN_PROCESS => 'Создаётся',
            QrStatusEnum::READY => 'Готова',
            QrStatusEnum::RECEIVED => 'Получена клиентом',
            QrStatusEnum::LOST => 'Потеряна',
            QrStatusEnum::WRONG_URL => 'Неправильный URL',
        };
    }

    /**
     * @return array[]
     */
    public static function getStatusesList(): array
    {
        return [
            ['id' => QrStatusEnum::NEW->value, 'name' => QrStatusEnum::NEW->getStatusName()],
            ['id' => QrStatusEnum::IN_PROCESS->value, 'name' => QrStatusEnum::IN_PROCESS->getStatusName()],
            ['id' => QrStatusEnum::READY->value, 'name' => QrStatusEnum::READY->getStatusName()],
            ['id' => QrStatusEnum::RECEIVED->value, 'name' => QrStatusEnum::RECEIVED->getStatusName()],
            ['id' => QrStatusEnum::LOST->value, 'name' => QrStatusEnum::LOST->getStatusName()],
            ['id' => QrStatusEnum::WRONG_URL->value, 'name' => QrStatusEnum::WRONG_URL->getStatusName()],
        ];
    }
}
