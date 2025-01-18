<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

// Ensure the User model exists
if (!class_exists(User::class)) {
    throw new \Exception("User model not found.");
}

class UserProfile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'title',
        'address',
        'city',
        'zipcode',
        'state',
        'country',
        'phone',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
