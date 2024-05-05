<?php

namespace App\Repositories\City;

use App\Models\City as Model;
use App\Repositories\CoreRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Pipeline;

final class CityRepository extends CoreRepository
{
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
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate(int|null $perPage, int $page, string $orderBy = 'id', string $orderWay = 'desc'): LengthAwarePaginator
    {
        $model = $this->startConditions();

        $fieldsArray = $model->getFillable();

        /** @var Builder $query */
        $query = $model->query();

        $query->select(columns: $fieldsArray);

        // Custom filters via Pipes:
        Pipeline::send($query)
            ->through([
                \App\Services\ModelQueryFilters\ByIdFilterPipe::class,
                \App\Services\ModelQueryFilters\ByNameFilterPipe::class,
                \App\Services\ModelQueryFilters\ByIdCountryFilterPipe::class,
                \App\Services\ModelQueryFilters\ByIsActiveFilterPipe::class
            ])
            ->thenReturn()
        ;

        $orderBy = strtolower($orderBy);
        $orderWay = strtolower($orderWay);

        if ($orderBy !== 'id' && !in_array($orderBy, $fieldsArray)) {
            $orderBy = 'id';
        }

        if (!in_array($orderWay, ['asc', 'desc'])) {
            $orderWay = 'desc';
        }

        $query->orderBy(column: $orderBy, direction: $orderWay);

        $result = $query->paginate(perPage: $perPage, columns: $fieldsArray, pageName: 'page', page: $page);

        return $result;
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
