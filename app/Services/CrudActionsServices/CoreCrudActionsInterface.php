<?php

namespace App\Services\CrudActionsServices;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Repositories\CoreRepository;

interface CoreCrudActionsInterface
{
    public function processStore(FormFieldsDtoInterface $dto): bool;
    public function processUpdate(FormFieldsDtoInterface $dto, CoreRepository $repository): bool;
    public function processDestroy(int $id, CoreRepository $repository): bool;
}
