<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_profile_id',
        'type',
        'address',
        'city',
        'state',
        'country',
        'zipcode',
    ];

    public function userProfile()
    {
        return $this->belongsTo(UserProfile::class, 'user_profile_id');
    }
}
