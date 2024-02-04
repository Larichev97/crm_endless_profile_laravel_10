<?php

namespace App\Repositories\QrProfile;

use App\Models\QrProfileImage as Model;
use App\Repositories\CoreRepository;

final class QrProfileImageRepository extends CoreRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  App\Models\QrProfileImage
     *
     * @return string
     */
    protected function getModelClass(): string
    {
        return Model::class;
    }

    /**
     *  Метод возвращает число самой последней позиции изображения по конкретному {$idQrProfile}
     *
     * @param int $idQrProfile Entity's Primary Key
     * @return int
     */
    public function getLastPositionNumber(int $idQrProfile): int
    {
        return (int) $this->startConditions()
            ->query()
            ->where('id_qr_profile', $idQrProfile)
            ->max('position');
    }
}
