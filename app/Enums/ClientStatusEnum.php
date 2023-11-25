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
            ClientStatusEnum::INACTIVE => 'Приостановленный',
            ClientStatusEnum::PREMIUM => 'Премиум',
        };
    }
}
