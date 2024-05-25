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
            QrStatusEnum::READY => 'Готов',
            QrStatusEnum::RECEIVED => 'Получен',
            QrStatusEnum::LOST => 'Потерян',
            QrStatusEnum::WRONG_URL => 'Старый URL',
        };
    }

    /**
     *  Example in index.blade.php tables: <span class="badge bg-gradient-{QrStatusEnum::}">...</span>
     *
     * @return string
     */
    public function getStatusGradientColor(): string
    {
        return match($this) {
            QrStatusEnum::NEW => 'info',
            QrStatusEnum::IN_PROCESS => 'secondary',
            QrStatusEnum::READY => 'success',
            QrStatusEnum::RECEIVED => 'dark',
            QrStatusEnum::LOST => 'danger',
            QrStatusEnum::WRONG_URL => 'warning',
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

    /**
     * @param int $value
     * @return bool
     */
    public static function isValidStatus(int $value): bool
    {
        return in_array($value, array_column(self::cases(), 'value'), true);
    }
}
