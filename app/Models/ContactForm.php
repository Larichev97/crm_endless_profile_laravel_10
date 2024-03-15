<?php

namespace App\Models;

use App\Enums\ContactFormStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactForm extends Model
{
    protected $table = 'contact_forms';

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
        'firstname',
        'lastname',
        'email',
        'phone_number',
        'comment',
        'id_employee',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_status' => 'integer',
        'id_employee' => 'integer',
    ];

    /**
     *  У одной заявки может быть только один Сотрудник, который последний редактировал запись
     *
     * @return BelongsTo
     */
    public function userWhoUpdated(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_employee');
    }

    /**
     *  Get contact form status name by ID via Enum
     *
     * @return string
     */
    public function getStatusNameAttribute(): string
    {
        return ContactFormStatusEnum::from((int) $this->id_status)->getStatusName();
    }

    /**
     *  Get contact form status gradient color by ID via Enum
     *
     * @return string
     */
    public function getStatusGradientColorAttribute(): string
    {
        return ContactFormStatusEnum::from((int) $this->id_status)->getStatusGradientColor();
    }
}
