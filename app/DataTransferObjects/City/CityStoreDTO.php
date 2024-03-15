<?php

namespace App\DataTransferObjects\City;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Http\Requests\City\CityStoreRequest;

final readonly class CityStoreDTO implements FormFieldsDtoInterface
{
    public int $id_country;
    public string $name;
    public int $is_active;

    /**
     * @param CityStoreRequest $cityStoreRequest
     */
    public function __construct(CityStoreRequest $cityStoreRequest)
    {
        $this->id_country = (int) $cityStoreRequest->validated('id_country');
        $this->name = (string) $cityStoreRequest->validated('name');
        $this->is_active = (!empty($cityStoreRequest->validated('is_active'))) ? 1 : 0;
    }

    /**
     * @return array
     */
    public function getFormFieldsArray(): array
    {
        return [
            'id_country' => $this->id_country,
            'name' => $this->name,
            'is_active' => $this->is_active,
        ];
    }
}
