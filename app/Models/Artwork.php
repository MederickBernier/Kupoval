<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Artist;
use App\Models\Category;
use App\Models\Event;

class Artwork extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'artist_id',
        'name',
        'description',
        'height',
        'width',
        'image',
        'initial_price',
        'is_on_sale',
        'is_featured',
        'is_for_event',
        'event_id',
    ];

    public function artist(){
        return $this->belongsTo(Artist::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class, 'artwork_categories')->withTimestamps()->withTrashed();
    }

    public function event(){
        return $this->belongsTo(Event::class);
    }
}
