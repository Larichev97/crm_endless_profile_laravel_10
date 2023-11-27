<?php

namespace App\Models;

use App\Enums\ClientStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
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
     *  Get client status name by ID via Enum
     *
     * @param int $id_status
     * @return string
     */
    public function getStatusName(int $id_status): string
    {
        return ClientStatusEnum::from($id_status)->getStatusName();
    }
}
