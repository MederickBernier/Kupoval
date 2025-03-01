<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PendingPayment
 *
 * This model represents a pending payment in the system.
 * It uses the HasFactory trait for factory-based creation.
 *
 * @property int $order_id The ID of the associated order.
 * @property string $transaction_id The transaction ID of the payment.
 * @property float $amount The amount of the payment.
 * @property string $status The status of the payment.
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PendingPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PendingPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PendingPayment query()
 *
 * @mixin \Eloquent
 */
class PendingPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transaction_id',
        'amount',
        'status'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
