<?php

namespace App\Models;

use App\Enums\ClientStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    ];

    /**
     *  У одного клиента может быть много QR-профилей
     *
     * @return HasMany
     */
    public function qrProfiles(): HasMany
    {
        return $this->hasMany(QrProfile::class, 'id_client');
    }

    /**
     *  У одного Клиента может быть только одна Страна
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'id_country');
    }

    /**
     *  У одного Клиента может быть только однин Город
     *
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'id_city');
    }

    /**
     *  У одного Клиента может быть только один Сотрудник, который создал запись
     *
     * @return BelongsTo
     */
    public function userWhoCreated(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user_add');
    }

    /**
     *  У одного Клиента может быть только один Сотрудник, который последний редактировал запись
     *
     * @return BelongsTo
     */
    public function userWhoUpdated(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user_update');
    }

    /**
     *  Get client status name by ID via Enum
     *
     * @return string
     */
    public function getStatusNameAttribute(): string
    {
        return ClientStatusEnum::from((int) $this->id_status)->getStatusName();
    }

    /**
     *  Get client status gradient color by ID via Enum
     *
     * @param int $id_status
     * @return string
     */
    public function getStatusGradientColor(int $id_status): string
    {
        return ClientStatusEnum::from($id_status)->getStatusGradientColor();
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

    /**
     * Метод преобразовывает "Дату рождения" к формату "d-m-Y" при помощи Carbon
     *
     * @return string
     */
    public function getBirthDateFormattedAttribute(): string
    {
        $result = '--';

        if (!empty($this->bdate)) {
            $result = Carbon::parse($this->bdate)->format('d.m.Y');
        }

        return $result;
    }

    /**
     * Метод преобразовывает "Дату регистрации" к формату "d-m-Y" при помощи Carbon
     *
     * @return string
     */
    public function getCreatedAtFormattedAttribute(): string
    {
        $result = '--';

        if (!empty($this->created_at)) {
            $result = Carbon::parse($this->created_at)->format('d.m.Y');
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getPhotoName(): string
    {
        return (string) $this->photo_name;
    }
}
