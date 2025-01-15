<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Artwork;
use App\Models\ShippingCondition;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'status',
        'total_price',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function artworks(){
        return $this->belongsToMany(Artwork::class, 'order_items')->withPivot('quantity','price');
    }

    public function shippingCondition(){
        return $this->belongsTo(ShippingCondition::class);
    }
}
