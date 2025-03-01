<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Wishlist;
use App\Models\UserProfile;
use App\Models\Order;

/**
 * Class User
 *
 * This class represents a user in the application and extends the Authenticatable class.
 * It implements the MustVerifyEmail interface and uses the HasFactory, Notifiable, and SoftDeletes traits.
 *
 * @property string $email
 * @property string $username
 * @property string $password
 * @property \Carbon\Carbon|null $email_verified_at
 * @property string $role
 * @property \Carbon\Carbon|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNull(string $column)
 * @method static \Illuminate\Database\Eloquent\Builder|User get()
 *
 * @method bool isAdmin() Check if the user has an admin role.
 * @method \Illuminate\Database\Eloquent\Relations\HasOne profile() Get the user's profile.
 * @method \Illuminate\Database\Eloquent\Relations\HasMany orders() Get the user's orders.
 * @method \Illuminate\Database\Eloquent\Relations\HasMany wishlist() Get the user's wishlist items that have associated artwork.
 * @method \Illuminate\Database\Eloquent\Collection activeUsers() Get all active users (not soft deleted).
 * @method \Illuminate\Database\Eloquent\Relations\HasOne cart() Get the user's cart.
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'email',
        'username',
        'password',
        'email_verified_at',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class)->whereHas('artwork');
    }

    public function activeUsers()
    {
        return self::whereNull('deleted_at')->get();
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
}
