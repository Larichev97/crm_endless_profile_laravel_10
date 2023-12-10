<?php

namespace App\Repositories\Client;

use App\Models\Client;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

final class ClientRepository implements ClientRepositoryInterface
{
    /**
     * @return array
     */
    public function getClientsList(): array
    {
        try {
            /** @var Collection $clients */
            $clients = Client::query()->select(['id', 'firstname', 'lastname', 'surname'])->get();

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
