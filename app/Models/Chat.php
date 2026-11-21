<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['rsvp_id', 'message', 'deleted'];

    public function rsvp()
    {
        return $this->belongsTo(Rsvp::class);
    }
}
