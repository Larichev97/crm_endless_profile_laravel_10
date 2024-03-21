<?php

namespace App\Repositories\Client;

use App\Models\Client as Model;
use App\Repositories\CoreRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class ClientRepository extends CoreRepository
{
    /**
     *  Список полей, у которых поиск в значениях выполняется по "field_name LIKE %...%"
     *
     * @var array|string[]
     */
    protected array $searchLikeFieldsArray = ['firstname', 'lastname', 'surname', 'email',];

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

    /**
     *  Список полей с названиями, которые необходимо отобразить в списке (route "clients.index")
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
            'email'         => ['field' => 'email', 'field_input_type' => self::INPUT_TYPE_EMAIL, 'field_title' => 'Email'],
            'phone_number'  => ['field' => 'phone_number', 'field_input_type' => self::INPUT_TYPE_TEXT, 'field_title' => 'Моб. номер'],
            'id_country'    => ['field' => 'id_country', 'field_input_type' => self::INPUT_TYPE_SELECT, 'field_title' => 'Страна'],
            'id_city'       => ['field' => 'id_city', 'field_input_type' => self::INPUT_TYPE_SELECT, 'field_title' => 'Город'],
            'id_status'     => ['field' => 'id_status', 'field_input_type' => self::INPUT_TYPE_SELECT, 'field_title' => 'Статус'],
        ];
    }
}
