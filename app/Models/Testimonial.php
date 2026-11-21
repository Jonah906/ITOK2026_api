<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['name', 'relation', 'message', 'type', 'video_path', 'approved'];
    protected $casts = ['approved' => 'boolean'];
}
