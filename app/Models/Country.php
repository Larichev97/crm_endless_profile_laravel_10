<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $iso_code
 * @property int $is_active
 * @property string $flag_file_name
 */
final class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';

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
        'name',
        'iso_code',
        'is_active',
        'flag_file_name',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'is_active' => 'integer',
    ];

    /**
     *  У одной Страны может быть много Клиентов
     *
     * @return HasMany
     */
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'id_country');
    }

    /**
     *  У одной Страны может быть много QR-профилей
     *
     * @return HasMany
     */
    public function qrProfiles(): HasMany
    {
        return $this->hasMany(QrProfile::class, 'id_country');
    }

    /**
     * @return string
     */
    public function getFlagName(): string
    {
        return (string) $this->flag_file_name;
    }

    /**
     * @param string $flagFilePath
     * @return void
     */
    public function setFlagFilePath(string $flagFilePath): void
    {
        $this->flagFilePath = $flagFilePath;
    }

    /**
     * @return string
     */
    public function getFlagFilePath(): string
    {
        return $this->flagFilePath;
    }
}
