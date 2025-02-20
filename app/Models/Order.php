<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'shipping_condition_id',
        'status',
        'subtotal',
        'total',
        'billing_address_id',
        'shipping_address_id',
        'recipient_name',
        'recipient_email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shippingCondition()
    {
        return $this->belongsTo(ShippingCondition::class, 'shipping_condition_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class)->withTrashed();
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id')->withTrashed();
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id')->withTrashed();
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
