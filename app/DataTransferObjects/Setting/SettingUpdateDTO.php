<?php

namespace App\DataTransferObjects\Setting;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Http\Requests\Setting\SettingUpdateRequest;

final class SettingUpdateDTO implements FormFieldsDtoInterface
{
    public readonly string $name;
    public readonly string $value;

    /**
     * @param SettingUpdateRequest $settingUpdateRequest
     * @param int $id_setting
     */
    public function __construct(SettingUpdateRequest $settingUpdateRequest, public readonly int $id_setting)
    {
        $this->name = (string) $settingUpdateRequest->validated('name');
        $this->value = (string) $settingUpdateRequest->validated('value');
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
