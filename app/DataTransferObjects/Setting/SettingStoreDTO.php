<?php

namespace App\DataTransferObjects\Setting;

use App\Http\Requests\Setting\SettingStoreRequest;

final class SettingStoreDTO
{
    public readonly string $name;
    public readonly string $value;

    /**
     * @param SettingStoreRequest $settingStoreRequest
     */
    public function __construct(SettingStoreRequest $settingStoreRequest)
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
