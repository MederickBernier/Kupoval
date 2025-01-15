<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Artwork; // Add this line

class Promotion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'discount',
        'discount_type',
        'start_date',
        'end_date',
    ];

    public function artworks(){
        return $this->belongsToMany(Artwork::class, 'artwork_promotions')->withTimestamps();
    }
}
