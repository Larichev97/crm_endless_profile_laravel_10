<?php

namespace App\Repositories\User;

use App\Models\User as Model;
use App\Repositories\CoreRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class UserRepository extends CoreRepository
{
    /**
     *  Список полей, у которых поиск в значениях выполняется по "DATE(field_name) = ..."
     *
     *  [Override]
     *
     * @var array|string[]
     */
    protected array $searchDateFieldsArray = ['created_at', 'updated_at',];

    /**
     *  Список полей, у которых поиск в значениях выполняется по "field_name LIKE %...%"
     *
     *  [Override]
     *
     * @var array|string[]
     */
    protected array $searchLikeFieldsArray = ['firstname', 'lastname', 'email', 'about', 'address', 'username'];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * App\Models\User
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
     * @param string $orderBy
     * @param string $orderWay
     * @param bool $useCache
     * @param array $filterFieldsData
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate(int|null $perPage, int $page, string $orderBy = 'id', string $orderWay = 'desc', bool $useCache = true, array $filterFieldsData = []): LengthAwarePaginator
    {
        return parent::getAllWithPaginate($perPage, $page, $orderBy, $orderWay, $useCache, $filterFieldsData);
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
}
