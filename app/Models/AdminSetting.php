<?php

namespace App\Models;

use App\Traits\HasAuthor;
use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    use HasAuthor;

    protected $fillable = [
        'key',
        'value',
        'description',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'value' => 'array',
    ];
}
