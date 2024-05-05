<?php

namespace App\Repositories\Setting;

use App\Models\Setting as Model;
use App\Repositories\CoreRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class SettingRepository extends CoreRepository
{
    /**
     * App\Models\Setting
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
        return parent::getAllWithPaginate($perPage, $page, $orderBy, $orderWay);
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
     *  Метод получает Значение {value} по Названю {name} настройки
     *
     * @param string $name
     * @param bool $useCache
     * @return string
     */
    public function getSettingValueByName(string $name, bool $useCache = true): string
    {
        $model = $this->startConditions();

        if ($useCache) {
            $result = Cache::remember('setting-name-'.strtolower($name), $this->cacheLife, function () use ($model, $name) {
                return (string) $model->query()->where('name', '=', $name)->value('value');
            });
        } else {
            $result = (string) $model->query()->where('name', '=', $name)->value('value');
        }

        return $result;
    }
}
