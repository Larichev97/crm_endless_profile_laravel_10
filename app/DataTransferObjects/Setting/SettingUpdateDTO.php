<?php

namespace App\DataTransferObjects\Setting;

use App\Http\Requests\Setting\SettingUpdateRequest;

final class SettingUpdateDTO
{
    public readonly string $name;
    public readonly string $value;

    /**
     * @param SettingUpdateRequest $settingUpdateRequest
     * @param int $id_setting
     */
    public function __construct(SettingUpdateRequest $settingStoreRequest, public readonly int $id_setting)
    {
        $this->name = (string) $settingStoreRequest->validated('name');
        $this->value = (string) $settingStoreRequest->validated('value');
    }

    /**
     * @return array
     */
    public function getFormFieldsArray(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
        ];
    }
}
