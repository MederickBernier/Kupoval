<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Order;

class ShippingCondition extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'cost',
        'description',
    ];

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
