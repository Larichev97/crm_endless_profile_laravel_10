<?php

namespace App\DataTransferObjects\Country;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Http\Requests\Country\CountryStoreRequest;
use Illuminate\Http\UploadedFile;

final readonly class CountryStoreDTO implements FormFieldsDtoInterface
{
    public string $name;
    public string $iso_code;
    public int $is_active;
    public string $flag_file_name;
    public UploadedFile|null $flag_file;

    /**
     * @param CountryStoreRequest $countryStoreRequest
     */
    public function __construct(CountryStoreRequest $countryStoreRequest)
    {
        $this->name = (string) $countryStoreRequest->validated('name');
        $this->iso_code = strtolower($countryStoreRequest->validated('iso_code'));
        $this->is_active = (!empty($countryStoreRequest->validated('is_active'))) ? 1 : 0;
        $this->flag_file_name = (string) $countryStoreRequest->validated('flag_file_name');
        $this->flag_file = $countryStoreRequest->validated('flag_file');
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
