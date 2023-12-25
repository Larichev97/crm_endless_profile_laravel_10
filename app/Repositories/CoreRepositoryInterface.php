<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CoreRepositoryInterface
{
    public function getForEditModel(int $id, bool $useCache = true):mixed;
    public function getModelCollection(bool $useCache = true):Collection;
    public function getAllWithPaginate(int|null $perPage, int $page, bool $useCache = true): LengthAwarePaginator;
    public function getForDropdownList(string $fieldId, string $fieldName, bool $useCache = true): Collection;
}
