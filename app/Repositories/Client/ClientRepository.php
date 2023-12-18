<?php

namespace App\Repositories\Client;

use App\Models\Client as Model;
use App\Repositories\CoreRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

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

    /**
     *  Deprecated (new method: $this->getForDropdownList(string $field_id, string $field_name))
     *
     * @return array
     */
    public function getClientsList(): array
    {
        try {
            /** @var Collection $clients */
            $clients = $this->startConditions()->select(['id', 'firstname', 'lastname', 'surname'])->get();

            $clientsList = $clients->map(function ($client) {
                return [
                    'id' => $client->id,
                    'name' => $client->lastname . ' ' . $client->firstname . ' ' . $client->surname,
                ];
            })->toArray();

            return $clientsList;
        } catch (Exception $exception) {
            Log::error('Error fetching clients: ' . $exception->getMessage());
            return [];
        }
    }
}
