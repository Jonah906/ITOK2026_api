<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeddingSetting extends Model
{
    protected $fillable = [
        'bride_name', 'groom_name', 'wedding_date', 'venue', 'venue_address',
        'dress_code', 'our_story', 'stream_url', 'hero_image', 'gallery',
        'story_video', 'gallery_videos', 'live',
    ];

    protected $casts = [
        'gallery' => 'array',
        'gallery_videos' => 'array',
        'live' => 'boolean',
        'wedding_date' => 'datetime',
    ];
}
