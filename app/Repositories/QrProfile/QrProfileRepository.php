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
     *  [Override]
     *
     * @var array|string[]
     */
    protected array $searchDateFieldsArray = ['birth_date', 'death_date',];

    /**
     *  Список полей, у которых поиск в значениях выполняется по "field_name LIKE %...%"
     *
     *  [Override]
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
     *  [Override]
     *
     * @param int|null $perPage
     * @param int $page
     * @param string $orderBy
     * @param string $orderWay
     * @param array $filterFieldsData
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate(int|null $perPage, int $page, string $orderBy = 'id', string $orderWay = 'desc', array $filterFieldsData = []): LengthAwarePaginator
    {
        return parent::getAllWithPaginate($perPage, $page, $orderBy, $orderWay, $filterFieldsData);
    }

    /**
     *  [Override]
     *
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
     *  [Override]
     *
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

    /**
     *  Список полей с названиями, которые необходимо отобразить в списке (route "admin.qrs.index")
     *
     *  [Override]
     *
     * @return array|string[]
     */
    public function getDisplayedFieldsOnIndexPage(): array
    {
        return [
            'id'            => ['field' => 'id', 'field_input_type' => self::INPUT_TYPE_TEXT, 'field_title' => '#'],
            'firstname'     => ['field' => 'firstname', 'field_input_type' => self::INPUT_TYPE_TEXT, 'field_title' => 'Имя'],
            'lastname'      => ['field' => 'lastname', 'field_input_type' => self::INPUT_TYPE_TEXT, 'field_title' => 'Фамилия'],
            'surname'       => ['field' => 'surname', 'field_input_type' => self::INPUT_TYPE_TEXT, 'field_title' => 'Отчество'],
            'death_date'    => ['field' => 'death_date', 'field_input_type' => self::INPUT_TYPE_DATE, 'field_title' => 'Дата смерти'],
            'id_country'    => ['field' => 'id_country', 'field_input_type' => self::INPUT_TYPE_SELECT, 'field_title' => 'Страна'],
            'id_client'     => ['field' => 'id_client', 'field_input_type' => self::INPUT_TYPE_SELECT, 'field_title' => 'Принадлежит клиенту'],
            'with_qr_code'  => ['field' => 'with_qr_code', 'field_input_type' => self::INPUT_TYPE_SELECT, 'field_title' => 'QR-Код'],
            'id_status'     => ['field' => 'id_status', 'field_input_type' => self::INPUT_TYPE_SELECT, 'field_title' => 'Статус'],
        ];
    }
}
