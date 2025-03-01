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

/**
 * Class UserProfile
 *
 * This model represents a user's profile and contains personal information
 * such as first name, last name, title, phone, and language preferences.
 * It also manages relationships to the User model and various Address models.
 *
 * @package App\Models
 *
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $title
 * @property string $phone
 * @property string $language
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile newQuery()
 * @method static \Illuminate\Database\Query\Builder|UserProfile onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile query()
 * @method static \Illuminate\Database\Query\Builder|UserProfile withTrashed()
 * @method static \Illuminate\Database\Query\Builder|UserProfile withoutTrashed()
 *
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Address $billingAddress
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Address[] $shippingAddresses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Address[] $addresses
 */
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
