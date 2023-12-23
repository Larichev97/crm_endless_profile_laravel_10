<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'firstname',
        'lastname',
        'email',
        'password',
        'address',
        'city',
        'country',
        'postal',
        'about'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Always encrypt the password when it is updated.
     *
     * @param $value
    * @return string
    */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     *  У одного Пользователя может быть много созданных им Клиентов
     *
     * @return HasMany
     */
    public function clientsCreated(): HasMany
    {
        return $this->hasMany(Client::class, 'id_user_add');
    }

    /**
     *  У одного Пользователя может быть много обновлённых им Клиентов
     *
     * @return HasMany
     */
    public function clientsUpdated(): HasMany
    {
        return $this->hasMany(Client::class, 'id_user_update');
    }

    /**
     *  У одного Пользователя может быть много созданных им QR-профилей
     *
     * @return HasMany
     */
    public function qrProfilesCreated(): HasMany
    {
        return $this->hasMany(QrProfile::class, 'id_user_add');
    }

    /**
     *  У одного Пользователя может быть много обновлённых им QR-профилей
     *
     * @return HasMany
     */
    public function qrProfilesUpdated(): HasMany
    {
        return $this->hasMany(QrProfile::class, 'id_user_update');
    }

    /**
     * Метод доступа для объединения имени, фамилии
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        $fullName = $this->lastname . ' ' . $this->firstname;

        return trim($fullName);
    }
}
