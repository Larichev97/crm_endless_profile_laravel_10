<?php

namespace App\Models;

use App\Enums\QrStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class QrProfile extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'qr_profiles';

    // *** Custom fields:

    /**
     * @var string Путь к изображению QR-профиля
     */
    public string $photoPath;

    /**
     * @var string Путь к аудио файлу QR-профиля
     */
    public string $voiceMessagePath;

    /**
     * @var string Путь к изображению QR-кода профиля
     */
    public string $qrCodePath;

    // ***

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'id_status',
        'id_client',
        'id_country',
        'id_city',
        'birth_date',
        'death_date',
        'firstname',
        'lastname',
        'surname',
        'cause_death',
        'profession',
        'hobbies',
        'biography',
        'last_wish',
        'favourite_music_artist',
        'link',
        'geo_latitude',
        'geo_longitude',
        'photo_file_name',
        'voice_message_file_name',
        'qr_code_file_name',
        'id_user_add',
        'id_user_update',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_status' => 'integer',
        'id_client' => 'integer',
        'id_country' => 'integer',
        'id_city' => 'integer',
    ];

    /**
     *  У одного QR-профиля может быть только один Клиент
     *
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    /**
     *  У одного QR-профиля может быть только один Пользователь, который создал запись
     *
     * @return BelongsTo
     */
    public function userWhoCreated(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user_add');
    }

    /**
     *  У одного QR-профиля может быть только один Пользователь, который последний редактировал запись
     *
     * @return BelongsTo
     */
    public function userWhoUpdated(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user_update');
    }

    /**
     *  Get qr profile status name by ID via Enum
     *
     * @return string
     */
    public function getStatusNameAttribute(): string
    {
        return QrStatusEnum::from((int) $this->id_status)->getStatusName();
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
     * Метод преобразовывает "Дату смерти" к формату "d-m-Y" при помощи Carbon
     *
     * @return string
     */
    public function getDeathDateFormattedAttribute(): string
    {
        $result = '--';

        if (!empty($this->death_date)) {
            $result = Carbon::parse($this->death_date)->format('d.m.Y');
        }

        return $result;
    }

    /**
     * Метод преобразовывает "Дату рождения" к формату "d-m-Y" при помощи Carbon
     *
     * @return string
     */
    public function getBirthDateFormattedAttribute(): string
    {
        $result = '--';

        if (!empty($this->birth_date)) {
            $result = Carbon::parse($this->birth_date)->format('d.m.Y');
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getPhotoName(): string
    {
        return (string) $this->photo_file_name;
    }

    /**
     * @return string
     */
    public function getVoiceMessageFileName(): string
    {
        return (string) $this->voice_message_file_name;
    }

    /**
     * @return string
     */
    public function getQrCodeFileName(): string
    {
        return (string) $this->qr_code_file_name;
    }

    /**
     * @param string $photoPath
     * @return void
     */
    public function setPhotoPath(string $photoPath): void
    {
        $this->photoPath = $photoPath;
    }

    /**
     * @return string
     */
    public function getVoiceMessagePath(): string
    {
        return $this->voiceMessagePath;
    }

    /**
     * @param string $voiceMessagePath
     * @return void
     */
    public function setVoiceMessagePath(string $voiceMessagePath): void
    {
        $this->voiceMessagePath = $voiceMessagePath;
    }

    /**
     * @return string
     */
    public function getQrCodePath(): string
    {
        return $this->qrCodePath;
    }

    public function setQrCodePath(string $qrCodePath): void
    {
        $this->qrCodePath = $qrCodePath;
    }
}
