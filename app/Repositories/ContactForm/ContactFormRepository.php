<?php

namespace App\Repositories\ContactForm;

use App\Models\ContactForm as Model;
use App\Repositories\CoreRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Pipeline;

final class ContactFormRepository extends CoreRepository
{
    /**
     * App\Models\ContactForm
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
                \App\Services\ModelQueryFilters\ByEmailFilterPipe::class,
                \App\Services\ModelQueryFilters\ByPhoneNumberFilterPipe::class,
                \App\Services\ModelQueryFilters\ByIdStatusFilterPipe::class,
                \App\Services\ModelQueryFilters\ByIdEmployeeFilterPipe::class,
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
     *  Список полей с названиями, которые необходимо отобразить в списке (route "admin.contact-forms.index")
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
            'email'         => ['field' => 'email', 'field_input_type' => self::INPUT_TYPE_EMAIL, 'field_title' => 'Email'],
            'phone_number'  => ['field' => 'phone_number', 'field_input_type' => self::INPUT_TYPE_TEXT, 'field_title' => 'Моб. номер'],
            'id_status'     => ['field' => 'id_status', 'field_input_type' => self::INPUT_TYPE_SELECT, 'field_title' => 'Статус Заявки'],
            'id_employee'   => ['field' => 'id_employee', 'field_input_type' => self::INPUT_TYPE_SELECT, 'field_title' => 'Кто редактировал'],
        ];
    }
}
