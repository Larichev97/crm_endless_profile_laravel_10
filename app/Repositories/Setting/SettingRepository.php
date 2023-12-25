<?php

namespace App\Repositories\Setting;

use App\Models\Setting as Model;
use App\Repositories\CoreRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class SettingRepository extends CoreRepository
{
    public function __construct()
    {
        parent::__construct();
    }

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
     * @param int|null $perPage
     * @param int $page
     * @param bool $useCache
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate(int|null $perPage, int $page, bool $useCache = true): LengthAwarePaginator
    {
        return parent::getAllWithPaginate($perPage, $page, $useCache);
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
     *  Метод получает Значение {value} по Названю {name} настройки
     *
     * @param string $name
     * @param bool $useCache
     * @return string
     */
    public function getSettingValueByName(string $name, bool $useCache = true): string
    {
        if ($useCache) {
            $result = Cache::remember('setting-name-'.strtolower($name), $this->cacheLife, function () use ($name) {
                return (string) $this->startConditions()->query()->where('name', '=', $name)->value('value');
            });
        } else {
            $result = (string) $this->startConditions()->query()->where('name', '=', $name)->value('value');
        }

        return $result;
    }
}
