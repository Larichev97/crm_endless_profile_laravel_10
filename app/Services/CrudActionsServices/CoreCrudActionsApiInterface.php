<?php

namespace App\Services\CrudActionsServices;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Repositories\CoreRepository;
use Illuminate\Database\Eloquent\Model;

interface CoreCrudActionsApiInterface
{
    public function processStore(FormFieldsDtoInterface $dto): Model|false;
    public function processUpdate(FormFieldsDtoInterface $dto, CoreRepository $repository): Model|false;
    public function processDestroy(int $id, CoreRepository $repository): bool;
}
