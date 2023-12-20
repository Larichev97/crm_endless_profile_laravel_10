<?php

namespace App\Repositories\QrProfile;

use App\Models\QrProfile as Model;
use App\Repositories\CoreRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class QrProfileRepository extends CoreRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * App\Models\QrProfile
     *
     * @return string
     */
    protected function getModelClass(): string
    {
        return Model::class;
    }

    /**
     * @param $perPage
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate($perPage = null): LengthAwarePaginator
    {
        return parent::getAllWithPaginate($perPage);
    }

    /**
     * @param string $field_id
     * @param string $field_name
     * @return Collection
     */
    public function getForDropdownList(string $field_id, string $field_name): Collection
    {
        return parent::getForDropdownList($field_id, $field_name);
    }
}
