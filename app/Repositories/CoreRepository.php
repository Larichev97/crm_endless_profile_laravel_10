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
    const INPUT_TYPE_TEXT = 'text';
    const INPUT_TYPE_TEXTAREA = 'textarea';
    const INPUT_TYPE_SELECT = 'select';
    const INPUT_TYPE_EMAIL = 'email';
    const INPUT_TYPE_DATE = 'date';
    const INPUT_TYPE_DATETIME = 'datetime';

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
        $this->model = app(abstract: $this->getModelClass()); // Создание объекта при помощи метода из Laravel
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
        $model = $this->startConditions();

        if ($useCache) {
            $result = Cache::remember($this->getModelClass().'-getForEditModel-'.$id, $this->cacheLife, function () use ($model, $id) {
                return $model->query()->find(id: $id);
            });
        } else {
            $result = $model->query()->find(id: $id);
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
        $model = $this->startConditions();

        if ($useCache) {
            $result = Cache::remember($this->getModelClass().'-getModelCollection', $this->cacheLife, function () use ($model) {
                return $model->all();
            });
        } else {
            $result = $model->all();
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
     * @param array $filterFieldsData
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate(int|null $perPage, int $page, string $orderBy = 'id', string $orderWay = 'desc', array $filterFieldsData = []): LengthAwarePaginator
    {
        $model = $this->startConditions();

        $fieldsArray = $model->getFillable();

        /** @var Builder $query */
        $query = $model->query();

        $query->select(columns: $fieldsArray);

        $this->setCustomQueryFilters(query: $query, filterFieldsData: $filterFieldsData);

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
     *  Метод формирует коллекции модели только с полями {$field_id} (для value="...") и {$field_name} (для содержимого внутри <option>"..."</option>)
     *
     * @param string $fieldId
     * @param string $fieldName
     * @param bool $useCache
     * @return Collection
     */
    public function getForDropdownList(string $fieldId, string $fieldName, bool $useCache = true): Collection
    {
        $model = $this->startConditions();

        $columns = implode(', ', [$fieldId, $fieldName]);

        if ($useCache) {
            $result = Cache::remember($this->getModelClass().'-getForDropdownList', $this->cacheLife,
                function () use($model, $columns) {
                    return $model->query()->selectRaw(expression: $columns)->orderBy(column: 'id', direction: 'asc')->toBase()->get();
                }
            );
        } else {
            $result = $model->query()->selectRaw(expression: $columns)->orderBy(column: 'id', direction: 'asc')->toBase()->get();
        }

        return $result;
    }

    /**
     *  Список полей с названиями, которые необходимо отобразить в списке (route "{group_name}.index")
     *
     * @return array|string[]
     */
    public function getDisplayedFieldsOnIndexPage(): array
    {
        return [
            'id'            => ['field' => 'id', 'field_input_type' => self::INPUT_TYPE_TEXT, 'field_title' => '#'],
            'created_at'    => ['field' => 'created_at', 'field_input_type' => self::INPUT_TYPE_DATE, 'field_title' => 'Дата создания'],
            'updated_at'    => ['field' => 'updated_at', 'field_input_type' => self::INPUT_TYPE_DATE, 'field_title' => 'Дата редактирования'],
        ];
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
                        $query->whereDate(column: (string) $filterFieldName, operator: '=', value: (string) $filterFieldValue);
                    } elseif (in_array((string) $filterFieldName, $this->searchLikeFieldsArray)) {
                        $query->where(column: (string) $filterFieldName, operator: 'LIKE', value: '%'.$filterFieldValue.'%');
                    } else {
                        $query->where(column: (string) $filterFieldName, operator: '=', value: $filterFieldValue);
                    }
                }
            }
        }

        return $query;
    }
}
