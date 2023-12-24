<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Setting extends Model
{
    protected $table = 'settings';

    public $timestamps = true; // с полями "created_at" и "updated_at"

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'value',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];
}
