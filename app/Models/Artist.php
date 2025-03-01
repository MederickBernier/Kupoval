<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Artwork;
use Illuminate\Support\Str;

/**
 * Class Artist
 *
 * This model represents an artist and includes functionality for handling
 * artist-related data and relationships.
 *
 * Properties:
 * @property string $first_name The first name of the artist.
 * @property string $last_name The last name of the artist.
 * @property string $name The full name of the artist.
 * @property string $bio A short biography of the artist.
 * @property string $photo The URL to the artist's photo.
 * @property string $email The email address of the artist.
 * @property string $website The website URL of the artist.
 * @property string $facebook The Facebook profile URL of the artist.
 * @property string $instagram The Instagram profile URL of the artist.
 * @property string $twitter The Twitter profile URL of the artist.
 * @property string $tiktok The TikTok profile URL of the artist.
 * @property string $youtube The YouTube channel URL of the artist.
 * @property string $slug The URL-friendly version of the artist's name.
 *
 * Methods:
 * @method \Illuminate\Database\Eloquent\Relations\HasMany artworks() Defines a one-to-many relationship with the Artwork model.
 * @method static void boot() Boot method to handle model events, such as creating a unique slug for the artist.
 * @method string getRouteKeyName() Returns the name of the route key for the model, which is 'slug'.
 *
 * Traits:
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory
 * @use \Illuminate\Database\Eloquent\SoftDeletes
 */
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
