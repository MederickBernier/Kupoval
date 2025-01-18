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
        'total',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_country',
        'billing_zipcode',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_country',
        'shipping_zipcode',
        'recipient_name',
        'recipient_email',
        'recipient_phone',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function shippingCondition(){
        return $this->belongsTo(ShippingCondition::class, 'shipping_condition_id');
    }

    public function items(){
        return $this->hasMany(OrderItem::class);
    }
}
