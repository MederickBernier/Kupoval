<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Order
 *
 * Represents an order in the system.
 *
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property int $shipping_condition_id
 * @property string $status
 * @property float $subtotal
 * @property float $total
 * @property int $billing_address_id
 * @property int $shipping_address_id
 * @property string $recipient_name
 * @property string $recipient_email
 * @property string|null $promo_code
 * @property float|null $promo_percent
 * @property float|null $promo_discount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \App\Models\User $user
 * @property-read \App\Models\ShippingCondition $shippingCondition
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderItem[] $items
 * @property-read \App\Models\Address $billingAddress
 * @property-read \App\Models\Address $shippingAddress
 * @property-read \App\Models\Payment $payment
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Query\Builder|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingConditionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereBillingAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRecipientName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRecipientEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePromoCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePromoPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePromoDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Order withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Order withoutTrashed()
 */
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
        'promo_code',
        'promo_percent',
        'promo_discount',
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
