<?php

namespace App\DataTransferObjects\Setting;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Http\Requests\Setting\SettingStoreRequest;

final readonly class SettingStoreDTO implements FormFieldsDtoInterface
{
    public string $name;
    public string $value;

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
