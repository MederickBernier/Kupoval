<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Artwork;
use Illuminate\Support\Str;

class Artist extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'bio',
        'photo',
        'email',
        'website',
        'facebook',
        'instagram',
        'twitter',
        'tiktok',
        'youtube',
        'slug',
    ];

    public function artworks()
    {
        return $this->hasMany(Artwork::class);
    }

    public static function boot(){
        parent::boot();
        static::creating(function($artist){
            $baseSlug = $artist->name ?? $artist->first_name . '-' . $artist->last_name;
            $artist->slug = Str::slug($baseSlug);

            $count = static::where('slug', 'LIKE', "{$artist->slug}%")->count();
            if ($count > 0) {
                $artist->slug .= '-' . ($count + 1);
            }
        });
    }

    public function getRouteKeyName(){
        return 'slug';
    }
}
