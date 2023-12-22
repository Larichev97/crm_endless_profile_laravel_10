<?php

namespace App\Enums;

enum ClientStatusEnum: int
{
    case NEW = 1;
    case ACTIVE = 2;
    case INACTIVE = 3;
    case PREMIUM = 4;

    /**
     * @return string
     */
    public function getStatusName(): string
    {
        return match($this) {
            ClientStatusEnum::NEW => 'Новый',
            ClientStatusEnum::ACTIVE => 'Активный',
            ClientStatusEnum::INACTIVE => 'Неактивный',
            ClientStatusEnum::PREMIUM => 'Премиум',
        };
    }

    /**
     *  Example in index.blade.php tables: <span class="badge bg-gradient-{ClientStatusEnum::}">...</span>
     *
     * @return string
     */
    public function getStatusGradientColor(): string
    {
        return match($this) {
            ClientStatusEnum::NEW => 'info',
            ClientStatusEnum::ACTIVE => 'success',
            ClientStatusEnum::INACTIVE => 'danger',
            ClientStatusEnum::PREMIUM => 'dark',
        };
    }

    /**
     * @return array[]
     */
    public static function getStatusesList(): array
    {
        return [
            ['id' => ClientStatusEnum::NEW->value, 'name' => ClientStatusEnum::NEW->getStatusName()],
            ['id' => ClientStatusEnum::ACTIVE->value, 'name' => ClientStatusEnum::ACTIVE->getStatusName()],
            ['id' => ClientStatusEnum::INACTIVE->value, 'name' => ClientStatusEnum::INACTIVE->getStatusName()],
            ['id' => ClientStatusEnum::PREMIUM->value, 'name' => ClientStatusEnum::PREMIUM->getStatusName()],
        ];
    }
}
