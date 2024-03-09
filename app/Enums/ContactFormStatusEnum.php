<?php

namespace App\Enums;

enum ContactFormStatusEnum: int
{
    case NEW = 1;
    case IN_PROCESS = 2;
    case READY = 3;

    /**
     * @return string
     */
    public function getStatusName(): string
    {
        return match($this) {
            ContactFormStatusEnum::NEW => 'Новая',
            ContactFormStatusEnum::IN_PROCESS => 'В работе',
            ContactFormStatusEnum::READY => 'Выполнена',
        };
    }

    /**
     *  Example in index.blade.php tables: <span class="badge bg-gradient-{ContactFormStatusEnum::$this}">...</span>
     *
     * @return string
     */
    public function getStatusGradientColor(): string
    {
        return match($this) {
            ContactFormStatusEnum::NEW => 'info',
            ContactFormStatusEnum::IN_PROCESS => 'secondary',
            ContactFormStatusEnum::READY => 'success',
        };
    }

    /**
     * @return array[]
     */
    public static function getStatusesList(): array
    {
        return [
            ['id' => ContactFormStatusEnum::NEW->value, 'name' => ContactFormStatusEnum::NEW->getStatusName()],
            ['id' => ContactFormStatusEnum::IN_PROCESS->value, 'name' => ContactFormStatusEnum::IN_PROCESS->getStatusName()],
            ['id' => ContactFormStatusEnum::READY->value, 'name' => ContactFormStatusEnum::READY->getStatusName()],
        ];
    }
}
