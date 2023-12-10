<?php

namespace App\Repositories\Client;

use Illuminate\Database\Eloquent\Collection;

interface ClientRepositoryInterface
{
    public function getClientsList(): array;
}
