<?php

namespace App\Repositories\City;

use App\Models\City as Model;
use App\Repositories\CoreRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class CityRepository extends CoreRepository
{
    /**
     *  Список полей, у которых поиск в значениях выполняется по "field_name LIKE %...%"
     *
     *  [Override]
     *
     * @var array|string[]
     */
    protected array $searchLikeFieldsArray = ['name',];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * App\Models\City
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
        $model = $this->startConditions();

        $columns = implode(', ', ['cities.'.$fieldId, 'cities.'.$fieldName, 'countries.name AS  country_name']);

        if ($useCache) {
            $result = Cache::remember($this->getModelClass().'-getForDropdownList', $this->cacheLife,
                function () use($model, $columns) {
                    return $model->query()
                        ->selectRaw($columns)
                        ->join('countries', 'cities.id_country', '=', 'countries.id')
                        ->orderBy('cities.id_country', 'asc')
                        ->orderBy('cities.name', 'asc')
                        ->toBase()
                        ->get();
                }
            );
        } else {
            $result = $model->query()
                ->selectRaw($columns)
                ->join('countries', 'cities.id_country', '=', 'countries.id')
                ->orderBy('cities.id_country', 'asc')
                ->orderBy('cities.name', 'asc')
                ->toBase()
                ->get();
        }

        return $result;
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
                    $filterFieldName = (string) $filterFieldName;

                    if ($filterFieldName == 'is_active') {
                        $realValue = 0;

                        if ((int) $filterFieldValue == 1) { // "Да" (1)
                            $realValue = 1;
                        }

                        $query->where($filterFieldName, '=', $realValue);
                    } elseif (in_array($filterFieldName, $this->searchDateFieldsArray)) {
                        $query->whereDate($filterFieldName, '=', (string) $filterFieldValue);
                    } elseif (in_array($filterFieldName, $this->searchLikeFieldsArray)) {
                        $query->where($filterFieldName, 'LIKE', '%'.$filterFieldValue.'%');
                    } else {
                        $query->where($filterFieldName, '=', $filterFieldValue);
                    }
                }
            }
        }

        return $query;
    }

    /**
     *  Список полей с названиями, которые необходимо отобразить в списке (route "admin.cities.index")
     *
     *  [Override]
     *
     * @return array|string[]
     */
    public function getDisplayedFieldsOnIndexPage(): array
    {
        return [
            'id'            => ['field' => 'id', 'field_input_type' => self::INPUT_TYPE_TEXT, 'field_title' => '#'],
            'name'          => ['field' => 'name', 'field_input_type' => self::INPUT_TYPE_TEXT, 'field_title' => 'Название'],
            'id_country'    => ['field' => 'id_country', 'field_input_type' => self::INPUT_TYPE_SELECT, 'field_title' => 'Страна'],
            'is_active'     => ['field' => 'is_active', 'field_input_type' => self::INPUT_TYPE_SELECT, 'field_title' => 'Включен'],
        ];
    }
}
