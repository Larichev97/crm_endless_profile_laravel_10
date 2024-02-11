<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\File;

class QrProfileImage extends Model
{
    use HasFactory;

    protected $table = 'qr_profile_images';

    public $timestamps = false; // без полей "created_at" и "updated_at"

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'id_qr_profile',
        'image_name',
        'position',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_qr_profile' => 'integer',
        'position' => 'integer',
        'is_active' => 'integer',
    ];

    /**
     *  У одного Изображения может быть только один QrProfile
     *
     * @return BelongsTo
     */
    public function qrProfile(): BelongsTo
    {
        return $this->belongsTo(related: QrProfile::class, foreignKey: 'id');
    }

    /**
     *  Метод возвращает полный путь к изображению
     *
     * @return string
     */
    public function getFullImagePathAttribute(): string
    {
        $filePath = '';

        $qrProfilePublicDirPath = 'qr/'.$this->id_qr_profile;

        if (
            (int) $this->id_qr_profile > 0 &&
            !empty($this->image_name) &&
            File::exists(storage_path('app/public/'.$qrProfilePublicDirPath.'/'.$this->image_name))
        ) {
            $filePath = $qrProfilePublicDirPath.'/'.$this->image_name;
        }

        return $filePath;
    }
}
