<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ShippingCondition
 *
 * Represents a shipping condition in the application.
 *
 * This model uses the HasFactory and SoftDeletes traits.
 *
 * @property string $name The name of the shipping condition.
 * @property string $description A description of the shipping condition.
 * @property float $fee The fee associated with the shipping condition.
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingCondition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingCondition newQuery()
 * @method static \Illuminate\Database\Query\Builder|ShippingCondition onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ShippingCondition query()
 * @method static \Illuminate\Database\Query\Builder|ShippingCondition withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ShippingCondition withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ShippingCondition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'fee'
    ];

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
