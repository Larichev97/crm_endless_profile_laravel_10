<?php

namespace App\Repositories\City;

use App\Models\City as Model;
use App\Repositories\CoreRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class CityRepository extends CoreRepository
{
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
        $columns = implode(', ', ['cities.'.$fieldId, 'cities.'.$fieldName, 'countries.name AS  country_name']);

        if ($useCache) {
            $result = Cache::remember($this->getModelClass().'-getForDropdownList', $this->cacheLife,
                function () use($columns) {
                    return $this->startConditions()->query()
                        ->selectRaw($columns)
                        ->join('countries', 'cities.id_country', '=', 'countries.id')
                        ->orderBy('cities.id_country', 'asc')
                        ->orderBy('cities.name', 'asc')
                        ->toBase()
                        ->get();
                }
            );
        } else {
            $result = $this->startConditions()->query()
                ->selectRaw($columns)
                ->join('countries', 'cities.id_country', '=', 'countries.id')
                ->orderBy('cities.id_country', 'asc')
                ->orderBy('cities.name', 'asc')
                ->toBase()
                ->get();
        }

        return $result;
    }
}
