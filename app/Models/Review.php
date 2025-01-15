<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Artwork;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'artwork_id',
        'review',
        'rating',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function artwork(){
        return $this->belongsTo(Artwork::class);
    }
}
