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
        'id_client' => 'integer',
        'id_country' => 'integer',
        'id_city' => 'integer',
        //'birth_date' => 'date:d-m-Y',
        //'death_date' => 'date:d-m-Y',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id_client');
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
}
