<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class City extends Model
{
    protected $table = 'cities';

    public $timestamps = false; // без полей "created_at" и "updated_at"

    // *** Custom fields:

    /**
     * @var string Путь к изображению флага страны
     */
    public string $flagFilePath;

    // ***

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'id_country',
        'name',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_country' => 'integer',
        'is_active' => 'integer',
    ];

    /**
     *  У одного Города может быть много Клиентов
     *
     * @return HasMany
     */
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'id_city');
    }

    /**
     *  У одного Города может быть много QR-профилей
     *
     * @return HasMany
     */
    public function qrProfiles(): HasMany
    {
        return $this->hasMany(QrProfile::class, 'id_city');
    }

    /**
     *  У одного Города может быть только одна Страна
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'id_country');
    }
}
