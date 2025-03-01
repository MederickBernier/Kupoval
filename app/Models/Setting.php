<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Setting
 *
 * This model represents a setting with a key-value pair.
 * It uses the HasFactory trait for factory-based creation.
 *
 * @property string $key The key of the setting.
 * @property string $value The value of the setting.
 */
class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];
}
