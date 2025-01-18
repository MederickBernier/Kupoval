<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'artwork_id',
        'rating',
        'comment',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function artwork(){
        return $this->belongsTo(Artwork::class);
    }
}
