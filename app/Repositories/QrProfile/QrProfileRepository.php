<?php

namespace App\Repositories\QrProfile;

use App\Models\QrProfile as Model;
use App\Repositories\CoreRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Pipeline;

final class QrProfileRepository extends CoreRepository
{
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
     *  [Override]
     *
     * @param int|null $perPage
     * @param int $page
     * @param string $orderBy
     * @param string $orderWay
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate(int|null $perPage, int $page, string $orderBy = 'id', string $orderWay = 'desc'): LengthAwarePaginator
    {
        $model = $this->startConditions();

        $fieldsArray = $model->getFillable();

        /** @var Builder $query */
        $query = $model->query();

        $query->select(columns: $fieldsArray);

        // Custom filters via Pipes:
        Pipeline::send($query)
            ->through([
                \App\Services\ModelQueryFilters\ByIdFilterPipe::class,
                \App\Services\ModelQueryFilters\ByFirstnameFilterPipe::class,
                \App\Services\ModelQueryFilters\ByLastnameFilterPipe::class,
                \App\Services\ModelQueryFilters\BySurnameFilterPipe::class,
                \App\Services\ModelQueryFilters\ByDeathDateFilterPipe::class,
                \App\Services\ModelQueryFilters\ByIdCountryFilterPipe::class,
                \App\Services\ModelQueryFilters\ByIdClientFilterPipe::class,
                \App\Services\ModelQueryFilters\ByWithQrCodeFilterPipe::class,
                \App\Services\ModelQueryFilters\ByIdStatusFilterPipe::class,
            ])
            ->thenReturn()
        ;

        $orderBy = strtolower($orderBy);
        $orderWay = strtolower($orderWay);

        if ($orderBy !== 'id' && !in_array($orderBy, $fieldsArray)) {
            $orderBy = 'id';
        }

        if (!in_array($orderWay, ['asc', 'desc'])) {
            $orderWay = 'desc';
        }

        $query->orderBy(column: $orderBy, direction: $orderWay);

        $result = $query->paginate(perPage: $perPage, columns: $fieldsArray, pageName: 'page', page: $page);

        return $result;
    }

    /**
     *  [Override]
     *
     * @param string $fieldId
     * @param string $fieldName
     * @param bool $useCache
     * @return Collection
     */
    public function getForDropdownList(string $fieldId, string $fieldName, bool $useCache = true): Collection
    {
        return parent::getForDropdownList($fieldId, $fieldName, $useCache);
    }

    /**
     *  Список полей с названиями, которые необходимо отобразить в списке (route "admin.qrs.index")
     *
     *  [Override]
     *
     * @return array|string[]
     */
    public function getDisplayedFieldsOnIndexPage(): array
    {
        return [
            'id'            => ['field' => 'id', 'field_input_type' => self::INPUT_TYPE_TEXT, 'field_title' => '#'],
            'firstname'     => ['field' => 'firstname', 'field_input_type' => self::INPUT_TYPE_TEXT, 'field_title' => 'Имя'],
            'lastname'      => ['field' => 'lastname', 'field_input_type' => self::INPUT_TYPE_TEXT, 'field_title' => 'Фамилия'],
            'surname'       => ['field' => 'surname', 'field_input_type' => self::INPUT_TYPE_TEXT, 'field_title' => 'Отчество'],
            'death_date'    => ['field' => 'death_date', 'field_input_type' => self::INPUT_TYPE_DATE, 'field_title' => 'Дата смерти'],
            'id_country'    => ['field' => 'id_country', 'field_input_type' => self::INPUT_TYPE_SELECT, 'field_title' => 'Страна'],
            'id_client'     => ['field' => 'id_client', 'field_input_type' => self::INPUT_TYPE_SELECT, 'field_title' => 'Принадлежит клиенту'],
            'with_qr_code'  => ['field' => 'with_qr_code', 'field_input_type' => self::INPUT_TYPE_SELECT, 'field_title' => 'QR-Код'],
            'id_status'     => ['field' => 'id_status', 'field_input_type' => self::INPUT_TYPE_SELECT, 'field_title' => 'Статус'],
        ];
    }
}
