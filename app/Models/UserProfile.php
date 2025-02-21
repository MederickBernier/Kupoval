<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

// Ensure the User model exists
if (!class_exists(User::class)) {
    throw new \Exception("User model not found.");
}

class UserProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'title',
        'phone',
        'language',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function billingAddress()
    {
        return $this->hasOne(Address::class)->where('type', 'billing');
    }

    public function shippingAddresses()
    {
        return $this->hasMany(Address::class)->where('type', 'shipping');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_profile_id');
    }
}
