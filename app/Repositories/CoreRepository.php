<?php

namespace App\Repositories;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 *  Репозиторий работы с сущностью:
 *   1) Может выдавать наборы данных;
 *   2) Не может создавать/изменять сущности;
 */
abstract class CoreRepository
{
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
     * @param $id
     * @return mixed
     */
    public function getForEditModel($id): mixed
    {
        return $this->startConditions()->find($id);
    }

    /**
     *  Метод получает все записи у модели и возвращает коллекцию
     *
     * @return Collection
     */
    public function getModelCollection(): Collection
    {
        return $this->startConditions()->all();
    }

    /**
     *  Метод получает все записи модели с доступными полями из массива {$fillable} для вывода пагинатором
     *
     * @param $perPage
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate($perPage = null): LengthAwarePaginator
    {
        $model = $this->startConditions();

        $fields_array = $model->getFillable();

        $result = $model->select($fields_array)->paginate($perPage);

        return $result;
    }

    /**
     *  Метод формирует коллекции модели только с полями {$field_id} (для value="...") и {$field_name} (для содержимого внутри <option>"..."</option>)
     *
     * @param string $field_id
     * @param string $field_name
     * @return Collection
     */
    public function getForDropdownList(string $field_id, string $field_name): Collection
    {
        $columns = implode(', ', [$field_id, $field_name]);

        $result = $this->startConditions()->selectRaw($columns)->toBase()->get();

        return $result;
    }
}
