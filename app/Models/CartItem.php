<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'artwork_id', 'quantity', 'price'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function artwork()
    {
        return $this->belongsTo(Artwork::class);
    }
}
