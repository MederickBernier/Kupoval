<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Artwork;

class Artist extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'bio',
        'photo',
    ];

    public function artworks()
    {
        return $this->hasMany(Artwork::class);
    }
}
