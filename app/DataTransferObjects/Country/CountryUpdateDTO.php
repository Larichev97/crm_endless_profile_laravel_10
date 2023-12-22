<?php

namespace App\DataTransferObjects\Country;

use App\Http\Requests\Country\CountryUpdateRequest;
use Illuminate\Http\UploadedFile;

final class CountryUpdateDTO
{
    public readonly string $name;
    public readonly string $iso_code;
    public readonly int $is_active;
    public readonly string $flag_file_name;
    public readonly UploadedFile|null $flag_file;

    /**
     * @param CountryUpdateRequest $countryUpdateRequest
     * @param int $id_country
     */
    public function __construct(CountryUpdateRequest $countryUpdateRequest, public readonly int $id_country)
    {
        $this->name = (string) $countryUpdateRequest->validated('name');
        $this->iso_code = strtolower($countryUpdateRequest->validated('iso_code'));
        $this->is_active = (!empty($countryUpdateRequest->validated('is_active'))) ? 1 : 0;
        $this->flag_file_name = (string) $countryUpdateRequest->validated('flag_file_name');
        $this->flag_file = $countryUpdateRequest->validated('flag_file');
    }

    /**
     * @return array
     */
    public function getFormFieldsArray(): array
    {
        return [
            'name' => $this->name,
            'iso_code' => $this->iso_code,
            'is_active' => $this->is_active,
            'flag_file_name' => $this->flag_file_name,
        ];
    }
}
