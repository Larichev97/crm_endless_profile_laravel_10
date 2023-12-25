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
                if (str_starts_with($fieldName, 'filter_') && !empty($fieldValue)) {
                    $defaultFieldName = str_replace('filter_', '', $fieldName);
                    $filterFieldsArray[$defaultFieldName] = $fieldValue;
                }
            }
        }

        return $filterFieldsArray;
    }
}
