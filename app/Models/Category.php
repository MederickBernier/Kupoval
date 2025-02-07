<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Artwork;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    public function artworks(){
        return $this->belongsToMany(Artwork::class, 'artwork_categories')->withTimestamps()->withTrashed();
    }
}
