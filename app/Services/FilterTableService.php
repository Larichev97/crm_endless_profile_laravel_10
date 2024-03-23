<?php

namespace App\Services;

class FilterTableService
{
    /**
     * @param array $allFieldsData
     * @return array
     */
    public function processPrepareFilterFieldsArray(array $allFieldsData): array
    {
        $filterFieldsArray = [];

        if (!empty($allFieldsData)) {
            foreach ($allFieldsData as $fieldName => $fieldValue) {
                if (str_starts_with(haystack: $fieldName, needle: 'filter_') && !empty($fieldValue)) {
                    $defaultFieldName = str_replace(search: 'filter_', replace: '', subject: $fieldName);
                    $filterFieldsArray[$defaultFieldName] = $fieldValue;
                }
            }
        }

        return $filterFieldsArray;
    }

    /**
     * @param array $filterFieldsArray
     * @return object
     */
    public function processConvertFilterFieldsToObject(array $filterFieldsArray):object
    {
        return (object) json_decode(json: json_encode(value: $filterFieldsArray), associative: false);
    }
}
