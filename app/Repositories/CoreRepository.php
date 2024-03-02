<?php

namespace App\Repositories;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 *  Репозиторий работы с сущностью:
 *   1) Может выдавать наборы данных;
 *   2) Не может создавать/изменять сущности;
 */
abstract class CoreRepository implements CoreRepositoryInterface
{
    /**
     * @var int Время жизни кэша по умолчанию: 1 месяц
     */
    protected int $cacheLife = 30 * 24 * 60 * 60; // 30 дней * 24 часа * 60 минут * 60 секунд;

    /**
     *  Список полей, у которых поиск в значениях выполняется по "DATE(field_name) = ..."
     *
     * @var array
     */
    protected array $searchDateFieldsArray = [];

    /**
     *  Список полей, у которых поиск в значениях выполняется по "field_name LIKE %...%"
     *
     * @var array
     */
    protected array $searchLikeFieldsArray = [];

    /**
     * @var Model
     */
    protected Model $model;

    public function __construct()
    {
        $this->model = app($this->getModelClass()); // Создание объекта при помощи метода из Laravel
    }

    /**
     *  В каждом классе-наследнике будет вызываться необходимая модель
     *
     * @return string
     */
    abstract protected function getModelClass(): string;

    /**
     *  Метод клонирует объект конкретной модели
     *
     * @return Application|Model|\Illuminate\Foundation\Application|mixed
     */
    protected function startConditions(): mixed
    {
        return clone $this->model;
    }

    /**
     *  Метод получает одну запись по конкретному {$id} и возвращает модель с атрибутами
     *
     * @param int $id Entity's Primary Key
     * @param bool $useCache
     * @return mixed
     */
    public function getForEditModel(int $id, bool $useCache = true): mixed
    {
        if ($useCache) {
            $result = Cache::remember($this->getModelClass().'-getForEditModel-'.$id, $this->cacheLife, function () use ($id) {
                return $this->startConditions()->query()->find($id);
            });
        } else {
            $result = $this->startConditions()->query()->find($id);
        }

        return $result;
    }

    /**
     *  Метод получает все записи у модели и возвращает коллекцию
     *
     * @param bool $useCache
     * @return Collection
     */
    public function getModelCollection(bool $useCache = true): Collection
    {
        if ($useCache) {
            $result = Cache::remember($this->getModelClass().'-getModelCollection', $this->cacheLife, function () {
                return $this->startConditions()->all();
            });
        } else {
            $result = $this->startConditions()->all();
        }

        return $result;
    }

    /**
     *  Метод получает все записи модели с доступными полями из массива {$fillable} для вывода пагинатором
     *
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
        $model = $this->startConditions();

        $fields_array = $model->getFillable();

        /** @var Builder $query */
        $query = $model->query();

        $query->select($fields_array);

        $this->setCustomQueryFilters($query, $filterFieldsData);

        if ($orderBy !== 'id' && !in_array($orderBy, $fields_array)) {
            $orderBy = 'id';
        }

        if (!in_array(strtolower($orderWay), ['asc', 'desc'])) {
            $orderWay = 'desc';
        }

        $query->orderBy($orderBy, $orderWay);

        // File или Redis кэширование не поддерживает тегирование:
        if ($useCache && Cache::supportsTags()) {
            $result = Cache::tags($this->getModelClass().'-getAllWithPaginate')->remember('page-'.$page.'-perPage-'.(int) $perPage, $this->cacheLife,
                function () use($query, $fields_array, $perPage, $page) {
                    return $query->paginate($perPage, $fields_array, 'page', $page);
                }
            );
        } else {
            $result = $query->paginate($perPage, $fields_array, 'page', $page);
        }

        return $result;
    }

    /**
     *  Метод формирует коллекции модели только с полями {$field_id} (для value="...") и {$field_name} (для содержимого внутри <option>"..."</option>)
     *
     * @param string $fieldId
     * @param string $fieldName
     * @param bool $useCache
     * @return Collection
     */
    public function getForDropdownList(string $fieldId, string $fieldName, bool $useCache = true): Collection
    {
        $columns = implode(', ', [$fieldId, $fieldName]);

        if ($useCache) {
            $result = Cache::remember($this->getModelClass().'-getForDropdownList', $this->cacheLife,
                function () use($columns) {
                    return $this->startConditions()->query()->selectRaw($columns)->orderBy('id', 'asc')->toBase()->get();
                }
            );
        } else {
            $result = $this->startConditions()->query()->selectRaw($columns)->orderBy('id', 'asc')->toBase()->get();
        }

        return $result;
    }

    /**
     *  Метод очищает кэш для конкретной модели
     *
     * @return void
     */
    public function cleanCache(): void
    {
        Cache::forget($this->getModelClass().'-getModelCollection');
        Cache::forget($this->getModelClass().'-getForDropdownList');

        if (Cache::supportsTags()) {
            Cache::tags($this->getModelClass().'-getAllWithPaginate')->flush();
        }
    }

    /**
     *  Метод добавляет к запросу дополнительные условия из массива {$filterFieldsData}
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
                    if (in_array((string) $filterFieldName, $this->searchDateFieldsArray)) {
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
