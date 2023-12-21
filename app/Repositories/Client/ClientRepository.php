<?php

namespace App\Repositories\Client;

use App\Models\Client as Model;
use App\Repositories\CoreRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class ClientRepository extends CoreRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * App\Models\Client
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
     * @param string $field_id
     * @param string $field_name
     * @param bool $useCache
     * @return Collection
     */
    public function getForDropdownList(string $field_id, string $field_name, bool $useCache = true): Collection
    {
        return parent::getForDropdownList($field_id, $field_name, $useCache);
    }
}
