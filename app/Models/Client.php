<?php

namespace App\Models;

use App\Enums\ClientStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Client extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'id_status',
        'id_country',
        'id_city',
        'phone_number',
        'email',
        'bdate',
        'address',
        'firstname',
        'lastname',
        'surname',
        'photo_name',
        'manager_comment',
        'id_user_add',
        'id_user_update',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id_status' => 'integer',
        'id_country' => 'integer',
        'id_city' => 'integer',
        'bdate' => 'date:d-m-Y',
    ];

    /**
     *  У одного клиент может быть много QR-профилей
     *
     * @return HasMany
     */
    public function qrProfiles(): HasMany
    {
        return $this->hasMany(QrProfile::class, 'id_client');
    }

    /**
     *  Get client status name by ID via Enum
     *
     * @param int $id_status
     * @return string
     */
    public function getStatusName(int $id_status): string
    {
        return ClientStatusEnum::from($id_status)->getStatusName();
    }

    /**
     * Метод доступа для объединения имени, фамилии и отчества в ФИО.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        $fullName = $this->lastname . ' ' . $this->firstname . ' ' . $this->surname;

        return trim($fullName);
    }
}
