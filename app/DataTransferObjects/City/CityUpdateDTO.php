<?php

namespace App\DataTransferObjects\City;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Http\Requests\City\CityUpdateRequest;

final readonly class CityUpdateDTO implements FormFieldsDtoInterface
{
    public int $id_country;
    public string $name;
    public int $is_active;

    /**
     * @param CityUpdateRequest $cityUpdateRequest
     * @param int $id_city
     */
    public function __construct(CityUpdateRequest $cityUpdateRequest, public int $id_city)
    {
        $this->id_country = (int) $cityUpdateRequest->validated('id_country');
        $this->name = (string) $cityUpdateRequest->validated('name');
        $this->is_active = (!empty($cityUpdateRequest->validated('is_active'))) ? 1 : 0;
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
