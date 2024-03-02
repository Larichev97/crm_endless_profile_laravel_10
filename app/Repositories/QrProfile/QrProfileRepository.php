<?php

namespace App\Repositories\QrProfile;

use App\Models\QrProfile as Model;
use App\Repositories\CoreRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class QrProfileRepository extends CoreRepository
{
    /**
     *  Список полей, у которых поиск в значениях выполняется по "DATE(field_name) = ..."
     *
     * @var array|string[]
     */
    protected array $searchDateFieldsArray = ['birth_date', 'death_date',];

    /**
     *  Список полей, у которых поиск в значениях выполняется по "field_name LIKE %...%"
     *
     * @var array|string[]
     */
    protected array $searchLikeFieldsArray = ['name', 'firstname', 'lastname', 'surname',];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * App\Models\QrProfile
     *
     * @return string
     */
    protected function getModelClass(): string
    {
        return Model::class;
    }

    /**
     * @param int|null $perPage
     * @param int $page
     * @param string $orderBy
     * @param string $orderWay
     * @param bool $useCache
     * @param array $filterFieldsData
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate(int|null $perPage, int $page, string $orderBy = 'id', string $orderWay = 'desc', bool $useCache = true, array $filterFieldsData = []): LengthAwarePaginator
    {
        return parent::getAllWithPaginate($perPage, $page, $orderBy, $orderWay, $useCache, $filterFieldsData);
    }

    /**
     * @param string $fieldId
     * @param string $fieldName
     * @param bool $useCache
     * @return Collection
     */
    public function getForDropdownList(string $fieldId, string $fieldName, bool $useCache = true): Collection
    {
        return parent::getForDropdownList($fieldId, $fieldName, $useCache);
    }

    /**
     * @param Builder $query
     * @param array $filterFieldsData
     * @return Builder
     */
    protected function setCustomQueryFilters(Builder $query, array $filterFieldsData): Builder
    {
        if (!empty($filterFieldsData)) {
            foreach ($filterFieldsData as $filterFieldName => $filterFieldValue) {
                if (!empty($filterFieldValue)) {
                    if ((string) $filterFieldName == 'with_qr_code') { // Есть "QR-Код":
                        if ((int) $filterFieldValue == 1) { // "Да"
                            $query->havingNotNull('qr_code_file_name');
                        } else { // "2" (Нет)
                            $query->orHavingNull('qr_code_file_name');
                            $query->orHaving('qr_code_file_name', '=', '');
                        }
                    } elseif (in_array((string) $filterFieldName, $this->searchDateFieldsArray)) {
                        $query->whereDate((string) $filterFieldName, '=', (string) $filterFieldValue);
                    } elseif (in_array((string) $filterFieldName, $this->searchLikeFieldsArray)) {
                        $query->where((string) $filterFieldName, 'LIKE', '%'.$filterFieldValue.'%');
                    } else {
                        $query->where((string) $filterFieldName, '=', $filterFieldValue);
                    }
                }
            }
        }

        return $query;
    }
}
